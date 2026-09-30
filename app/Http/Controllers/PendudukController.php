<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\JenisKelamin;
use App\Enums\StatusKependudukan;
use App\Exports\PendudukExport;
use App\Exports\TemplatePendudukExport;
use App\Http\Requests\PendudukRequest;
use App\Imports\PendudukImport;
use App\Jobs\MarkExportReady;
use App\Models\ExportedFile;
use App\Models\Penduduk;
use App\Models\ProfilDesa;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PendudukController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Penduduk::class);

        $allowedSorts = ['created_at', 'nama_lengkap', 'nik', 'dusun', 'status_kependudukan'];
        $sort = in_array($request->get('sort'), $allowedSorts) ? $request->get('sort') : 'created_at';
        $order = in_array(strtolower($request->get('order') ?? ''), ['asc', 'desc']) ? $request->get('order') : 'desc';
        $limit = max(1, min((int) $request->get('limit', 10), 100));

        $searchQuery = strip_tags((string) $request->search);
        $filterQuery = strip_tags((string) $request->filter);

        $stats = Cache::remember('penduduk_stats', 3600, function () {
            return [
                'totalWarga' => Penduduk::count(),
                'totalLaki' => Penduduk::where('jenis_kelamin', JenisKelamin::LAKI_LAKI->value)->count(),
                'totalPerempuan' => Penduduk::where('jenis_kelamin', JenisKelamin::PEREMPUAN->value)->count(),
                'totalAktif' => Penduduk::where('status_kependudukan', StatusKependudukan::AKTIF->value)->count(),
            ];
        });

        $penduduk = Penduduk::with('user')
            ->search($searchQuery)
            ->filterWilayah($filterQuery)
            ->when($request->status, function ($query, $status) {
                if (StatusKependudukan::tryFrom($status)) {
                    return $query->where('status_kependudukan', $status);
                }

                return $query;
            })
            ->orderBy($sort, $order)
            ->paginate($limit)
            ->withQueryString();

        return view('penduduk.index', array_merge(compact('penduduk'), $stats));
    }

    public function create(): View
    {
        Gate::authorize('create', Penduduk::class);
        $profilDesa = ProfilDesa::first() ?? new ProfilDesa;

        return view('penduduk.create', compact('profilDesa'));
    }

    public function store(PendudukRequest $request): RedirectResponse
    {
        Gate::authorize('create', Penduduk::class);
        $validated = $request->validated();
        $uploadedPhoto = null;

        try {
            DB::beginTransaction();

            if ($request->hasFile('foto')) {
                $uploadedPhoto = $request->file('foto')->store('foto-penduduk', 'private');
                $validated['foto'] = $uploadedPhoto;
            }

            Penduduk::create($validated);
            DB::commit();

            return redirect()->route('penduduk.index')->with('success', 'Data penduduk tervalidasi dan berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($uploadedPhoto && Storage::disk('private')->exists($uploadedPhoto)) {
                Storage::disk('private')->delete($uploadedPhoto);
            }
            report($e);

            return back()->withInput()->with('error', 'Terjadi kesalahan sistem saat menyimpan data penduduk.');
        }
    }

    public function show(Penduduk $penduduk): View
    {
        Gate::authorize('view', $penduduk);
        $profilDesa = ProfilDesa::first() ?? new ProfilDesa;
        $penduduk->load('user');

        return view('penduduk.show', compact('penduduk', 'profilDesa'));
    }

    public function edit(Penduduk $penduduk): View
    {
        Gate::authorize('update', $penduduk);
        $profilDesa = ProfilDesa::first() ?? new ProfilDesa;

        return view('penduduk.edit', compact('penduduk', 'profilDesa'));
    }

    public function update(PendudukRequest $request, Penduduk $penduduk): RedirectResponse
    {
        Gate::authorize('update', $penduduk);
        $validated = $request->validated();

        $oldPhoto = $penduduk->foto;
        $newPhotoPath = null;

        try {
            DB::beginTransaction();

            if ($request->hasFile('foto')) {
                $newPhotoPath = $request->file('foto')->store('foto-penduduk', 'private');
                $validated['foto'] = $newPhotoPath;
            }

            $penduduk->update($validated);
            DB::commit();

            // FIXED: Hapus foto lama hanya JIKA kueri database sudah 100% sukses di-commit
            if ($newPhotoPath && $oldPhoto && Storage::disk('private')->exists($oldPhoto)) {
                Storage::disk('private')->delete($oldPhoto);
            }

            return redirect()->route('penduduk.index')->with('success', 'Pembaruan data warga berhasil diamankan dan disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();

            // FIXED: Hapus foto baru (yang telanjur di-upload) karena kueri database gagal
            if ($newPhotoPath && Storage::disk('private')->exists($newPhotoPath)) {
                Storage::disk('private')->delete($newPhotoPath);
            }

            return back()->withInput()->with('error', 'Terjadi kesalahan sistem saat memperbarui data.');
        }
    }

    public function destroy(Penduduk $penduduk): RedirectResponse
    {
        Gate::authorize('delete', $penduduk);
        try {
            $penduduk->delete();

            return redirect()->route('penduduk.index')->with('success', 'Data penduduk berhasil diarsipkan secara aman (Soft Delete)!');
        } catch (\Exception $e) {
            report($e);

            return redirect()->route('penduduk.index')->with('error', 'Sistem gagal menghapus data penduduk terkait.');
        }
    }

    public function print(Penduduk $penduduk): View
    {
        Gate::authorize('view', $penduduk);

        if (! $penduduk->exists) {
            abort(404, 'Data kependudukan tidak ditemukan atau tidak valid.');
        }

        $profilDesa = ProfilDesa::first() ?? new ProfilDesa;
        $namaKades = User::getNamaKepalaDesa();

        return view('penduduk.print-detail', compact('penduduk', 'profilDesa', 'namaKades'));
    }

    public function exportExcel(Request $request): RedirectResponse
    {
        return $this->queueExport($request, 'xlsx');
    }

    public function exportCsv(Request $request): RedirectResponse
    {
        return $this->queueExport($request, 'csv');
    }

    public function exports(): View
    {
        Gate::authorize('penduduk.export');
        $exports = ExportedFile::query()
            ->where('user_id', Auth::id())
            ->where('expires_at', '>', now())
            ->latest()
            ->paginate(20);

        return view('penduduk.exports', compact('exports'));
    }

    public function downloadExport(ExportedFile $exportedFile)
    {
        abort_unless((int) $exportedFile->user_id === (int) Auth::id(), 403);
        Gate::authorize('download', $exportedFile);

        abort_unless(
            $exportedFile->status === 'ready'
                && $exportedFile->expires_at?->isFuture()
                && str_starts_with($exportedFile->path, 'exports/')
                && ! str_contains($exportedFile->path, '..'),
            404
        );

        $disk = Storage::disk('private');
        abort_unless($disk->exists($exportedFile->path), 404);

        return $disk->download($exportedFile->path, $exportedFile->filename, [
            'Cache-Control' => 'private, no-store',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    private function queueExport(Request $request, string $format): RedirectResponse
    {
        Gate::authorize('penduduk.export');

        $exportedFile = ExportedFile::create([
            'user_id' => Auth::id(),
            'filename' => 'Data-Penduduk-Ridogalih-'.now()->format('Ymd-His').'.'.$format,
            'format' => $format,
            'path' => '',
            'status' => 'pending',
            'expires_at' => now()->addDay(),
        ]);
        $filePath = 'exports/'.$exportedFile->uuid.'.'.$format;
        $exportedFile->update(['path' => $filePath]);

        $export = new PendudukExport(
            strip_tags((string) $request->search),
            strip_tags((string) $request->filter)
        );

        Excel::queue(
            $export,
            $filePath,
            'private',
            $format === 'csv' ? \Maatwebsite\Excel\Excel::CSV : \Maatwebsite\Excel\Excel::XLSX
        )->chain([new MarkExportReady($exportedFile->id)]);

        return redirect()->route('penduduk.exports')
            ->with('success', 'Ekspor diproses di latar belakang. File hanya dapat diunduh oleh akun Anda selama 3 hari setelah selesai.');
    }

    public function exportPdf(Request $request)
    {
        Gate::authorize('penduduk.export');

        $penduduk = Penduduk::search(strip_tags((string) $request->search))
            ->filterWilayah(strip_tags((string) $request->filter))
            ->limit(500)
            ->get();

        if ($penduduk->isEmpty()) {
            return redirect()->back()->with('error', 'Data tidak ditemukan untuk di-export.');
        }

        $namaKades = User::getNamaKepalaDesa();
        $profilDesa = ProfilDesa::first() ?? new ProfilDesa;

        $pdf = Pdf::loadView('penduduk.pdf', compact('penduduk', 'namaKades', 'profilDesa'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('Data-Penduduk-Ridogalih.pdf');
    }

    public function downloadTemplate(): BinaryFileResponse
    {
        Gate::authorize('create', Penduduk::class);

        return Excel::download(new TemplatePendudukExport, 'Template-Import-Penduduk.xlsx');
    }

    public function importExcel(Request $request): RedirectResponse
    {
        Gate::authorize('create', Penduduk::class);

        $request->validate([
            'file_import' => 'required|mimes:xlsx,xls,csv|max:10240',
        ], [
            'file_import.required' => 'Pilih file Excel/CSV terlebih dahulu.',
            'file_import.mimes' => 'Format file harus berupa .xlsx, .xls, atau .csv.',
            'file_import.max' => 'Ukuran file maksimal adalah 10MB.',
        ]);

        try {
            $filePath = $request->file('file_import')->store('temp_imports', 'local');

            Excel::queueImport(new PendudukImport, $filePath, 'local')->chain([
                function () use ($filePath) {
                    if (Storage::disk('local')->exists($filePath)) {
                        Storage::disk('local')->delete($filePath);
                    }
                },
            ]);

            return redirect()->route('penduduk.index')->with('success', 'File telah masuk ke sistem antrean! Server akan memproses ribuan data tanpa membebani sistem.');
        } catch (\Exception $e) {
            report($e);

            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses antrean import data kependudukan.');
        }
    }
}

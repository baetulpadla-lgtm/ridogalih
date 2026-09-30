<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\SuratKeluar;
use App\Models\JenisSurat;
use App\Models\Penduduk;
use App\Models\ProfilDesa;
use App\Models\User;
use App\Http\Requests\SuratKeluarRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class SuratKeluarController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', SuratKeluar::class);
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $query = SuratKeluar::with(['jenis_surat', 'penduduk', 'user'])->latest();
        if (!$user->hasPermission('surat_keluar.update')) {
            $query->where('user_id', $user->id);
        }

        $surat_keluars = $query->get();
        return view('surat_keluar.index', compact('surat_keluars'));
    }

    public function create()
    {
        Gate::authorize('create', SuratKeluar::class);
        $jenis_surats = JenisSurat::aktif()->get();
        $penduduks = Penduduk::all();
        return view('surat_keluar.create', compact('jenis_surats', 'penduduks'));
    }

    public function store(SuratKeluarRequest $request)
    {
        Gate::authorize('create', SuratKeluar::class);
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $validated = $request->validated();

        if (!$user->hasPermission('surat_keluar.update')) {
            if (empty($user->penduduk_id)) abort(403, 'Akun Anda belum ditautkan dengan data penduduk yang sah.');
            $pendudukId = $user->penduduk_id;
        } else {
            $pendudukId = $validated['penduduk_id'];
        }

        SuratKeluar::create([
            'jenis_surat_id' => $validated['jenis_surat_id'],
            'penduduk_id'    => $pendudukId,
            'user_id'        => $user->id,
            'keperluan'      => $validated['keperluan'],
            'status'         => 'Menunggu',
        ]);

        return redirect()->route('surat-keluar.index')->with('success', 'Pengajuan surat berhasil dikirim!');
    }

    public function show(SuratKeluar $suratKeluar)
    {
        Gate::authorize('view', $suratKeluar);
        $suratKeluar->loadMissing(['jenis_surat', 'penduduk', 'user']);
        return view('surat_keluar.show', compact('suratKeluar'));
    }

    public function update(SuratKeluarRequest $request, SuratKeluar $suratKeluar)
    {
        Gate::authorize('update', $suratKeluar);
        $validated = $request->validated();
        $generatedQrPath = null;

        try {
            DB::beginTransaction();

            $nomorSuratFinal = $validated['nomor_surat'] ?? $suratKeluar->nomor_surat;
            $qrPath = $suratKeluar->qr_code_path;
            $signatureHash = $suratKeluar->digital_signature_hash;

            if (in_array($validated['status'], ['Disetujui', 'Selesai']) && empty($suratKeluar->nomor_surat)) {
                $jenis = $suratKeluar->jenis_surat;
                $tahun = date('Y');

                $suratTerakhir = SuratKeluar::where('jenis_surat_id', $jenis->id)
                                ->whereYear('created_at', $tahun)
                                ->whereNotNull('nomor_surat')
                                ->lockForUpdate() // Mencegah bentrok nomor ganda
                                ->orderBy('id', 'desc')
                                ->first();

                $nextSeq = $suratTerakhir ? (intval(trim(explode('/', $suratTerakhir->nomor_surat)[0])) + 1) : 1;
                $bulanRomawi = $this->getRomawi(date('n'));
                $nomorSuratFinal = str_pad((string)$nextSeq, 3, '0', STR_PAD_LEFT) . " / {$jenis->kode_surat} / {$bulanRomawi} / {$tahun}";

                $dataToHash = $suratKeluar->uuid . $nomorSuratFinal . $suratKeluar->penduduk_id . now();
                $signatureHash = hash('sha256', $dataToHash);
                $verificationUrl = route('validasi.surat', $suratKeluar->uuid);

                $qrFileName = 'qr-surat/' . $suratKeluar->uuid . '.png';
                $qrImage = QrCode::format('png')->size(300)->margin(1)->errorCorrection('H')->generate($verificationUrl);

                Storage::disk('private')->put($qrFileName, $qrImage);
                $qrPath = $generatedQrPath = $qrFileName;
            }

            $suratKeluar->update([
                'status'                 => $validated['status'],
                'nomor_surat'            => $nomorSuratFinal,
                'keterangan_status'      => $validated['keterangan_status'] ?? null,
                'qr_code_path'           => $qrPath,
                'digital_signature_hash' => $signatureHash,
            ]);

            DB::commit();
            return redirect()->route('surat-keluar.show', $suratKeluar->uuid)->with('success', 'Status dokumen diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($generatedQrPath && Storage::disk('private')->exists($generatedQrPath)) {
                Storage::disk('private')->delete($generatedQrPath);
            }
            report($e);
            return back()->withInput()->with('error', 'Gagal memproses persetujuan dokumen. Silakan coba kembali atau hubungi administrator.');
        }
    }

    public function print(SuratKeluar $suratKeluar)
    {
        Gate::authorize('print', $suratKeluar);
        $suratKeluar->loadMissing(['jenis_surat', 'penduduk', 'user']);

        if (!in_array($suratKeluar->status, ['Disetujui', 'Selesai'])) {
            return redirect()->back()->with('error', 'Dokumen belum mendapat persetujuan TTE, tidak dapat dicetak.');
        }

        $profil = ProfilDesa::first() ?? new ProfilDesa();
        $namaKepalaDesa = User::getNamaKepalaDesa();

        $pdf = Pdf::loadView('surat_keluar.print', compact('suratKeluar', 'profil', 'namaKepalaDesa'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('Surat_' . $suratKeluar->jenis_surat->kode_surat . '_' . ($suratKeluar->penduduk->nama_lengkap ?? 'Warga') . '.pdf');
    }

    public function destroy(SuratKeluar $suratKeluar)
    {
        Gate::authorize('delete', $suratKeluar);
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->hasPermission('surat_keluar.update')) {
            if ($suratKeluar->status !== 'Menunggu') {
                return redirect()->back()->with('error', 'Dokumen yang telah masuk tahap proses tidak dapat dibatalkan.');
            }
        }

        $suratKeluar->delete();
        return redirect()->route('surat-keluar.index')->with('success', 'Pengajuan surat ditarik (Soft Delete).');
    }

    private function getRomawi(string|int $bulan): string {
        $map = [1=>'I', 2=>'II', 3=>'III', 4=>'IV', 5=>'V', 6=>'VI', 7=>'VII', 8=>'VIII', 9=>'IX', 10=>'X', 11=>'XI', 12=>'XII'];
        return $map[(int)$bulan] ?? 'I';
    }

    public function validasi(string $uuid)
    {
        $surat = SuratKeluar::with(['jenis_surat', 'penduduk'])->withTrashed()->where('uuid', $uuid)->first();
        if (!$surat) return view('surat_keluar.validasi', ['status' => 'not_found', 'pesan' => 'Dokumen Palsu!']);
        if ($surat->trashed()) return view('surat_keluar.validasi', ['status' => 'revoked', 'pesan' => 'Dibatalkan oleh Desa!']);
        if (!in_array($surat->status, ['Disetujui', 'Selesai'])) return view('surat_keluar.validasi', ['status' => 'pending', 'pesan' => 'Dokumen Belum Sah!']);

        return view('surat_keluar.validasi', ['status' => 'valid', 'surat' => $surat]);
    }
}

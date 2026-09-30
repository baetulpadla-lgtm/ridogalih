<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use App\Http\Requests\SuratMasukRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SuratMasukController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', SuratMasuk::class);
        $query = SuratMasuk::query();

        if ($request->filled('search')) {
            $search = strip_tags((string)$request->search);
            $query->where(fn($q) => $q->where('nomor_surat', 'like', "%{$search}%")->orWhere('asal_surat', 'like', "%{$search}%")->orWhere('perihal', 'like', "%{$search}%"));
        }

        $suratMasuks = $query->latest()->paginate(10)->withQueryString();
        return view('surat_masuk.index', compact('suratMasuks'));
    }

    public function create(): View
    {
        Gate::authorize('create', SuratMasuk::class);
        return view('surat_masuk.create');
    }

    public function store(SuratMasukRequest $request): RedirectResponse
    {
        Gate::authorize('create', SuratMasuk::class);
        $validated = $request->validated();
        $validated['user_id'] = Auth::id();
        $uploadedFile = null;

        try {
            DB::beginTransaction();
            if ($request->hasFile('file_scan')) {
                $uploadedFile = $request->file('file_scan')->store('arsip-surat-masuk', 'private');
                $validated['file_scan'] = $uploadedFile;
            }

            SuratMasuk::create($validated);
            DB::commit();
            return redirect()->route('surat-masuk.index')->with('success', 'Arsip Surat Masuk berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($uploadedFile && Storage::disk('private')->exists($uploadedFile)) Storage::disk('private')->delete($uploadedFile);
            report($e);
            return back()->withInput()->with('error', 'Terjadi kesalahan sistem saat menyimpan arsip.');
        }
    }

    public function show(SuratMasuk $suratMasuk): View
    {
        Gate::authorize('view', $suratMasuk);
        return view('surat_masuk.show', compact('suratMasuk'));
    }

    public function edit(SuratMasuk $suratMasuk): View
    {
        Gate::authorize('update', $suratMasuk);
        return view('surat_masuk.edit', compact('suratMasuk'));
    }

    public function update(SuratMasukRequest $request, SuratMasuk $suratMasuk): RedirectResponse
    {
        Gate::authorize('update', $suratMasuk);
        $validated = $request->validated();
        $oldFile = $suratMasuk->file_scan;
        $newFile = null;

        try {
            DB::beginTransaction();
            if ($request->hasFile('file_scan')) {
                $newFile = $request->file('file_scan')->store('arsip-surat-masuk', 'private');
                $validated['file_scan'] = $newFile;
            }

            $suratMasuk->update($validated);
            DB::commit();

            if ($newFile && $oldFile && Storage::disk('private')->exists($oldFile)) {
                Storage::disk('private')->delete($oldFile);
            }
            return redirect()->route('surat-masuk.index')->with('success', 'Arsip Surat Masuk diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($newFile && Storage::disk('private')->exists($newFile)) Storage::disk('private')->delete($newFile);
            report($e);
            return back()->withInput()->with('error', 'Terjadi kesalahan sistem saat memperbarui arsip.');
        }
    }

    public function destroy(SuratMasuk $suratMasuk): RedirectResponse
    {
        Gate::authorize('delete', $suratMasuk);
        $suratMasuk->delete();
        return redirect()->route('surat-masuk.index')->with('success', 'Arsip Surat Masuk berhasil diarsipkan (Soft Delete).');
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\JenisSurat;
use App\Http\Requests\JenisSuratRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class JenisSuratController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('jenis_surat.view');
        $query = JenisSurat::query();

        if ($request->filled('search')) {
            $search = strip_tags((string)$request->search);
            $query->where(fn($q) => $q->where('nama_surat', 'like', "%{$search}%")->orWhere('kode_surat', 'like', "%{$search}%"));
        }

        $jenis_surats = $query->latest()->paginate(10)->withQueryString();
        return view('jenis_surat.index', compact('jenis_surats'));
    }

    public function create(): View
    {
        Gate::authorize('jenis_surat.create');
        return view('jenis_surat.create');
    }

    public function store(JenisSuratRequest $request): RedirectResponse
    {
        Gate::authorize('jenis_surat.create');
        JenisSurat::create($request->validated());
        return redirect()->route('jenis-surat.index')->with('success', 'Jenis Surat berhasil ditambahkan!');
    }

    public function show(JenisSurat $jenisSurat): View
    {
        Gate::authorize('jenis_surat.view');
        return view('jenis_surat.show', compact('jenisSurat'));
    }

    public function edit(JenisSurat $jenisSurat): View
    {
        Gate::authorize('jenis_surat.update');
        return view('jenis_surat.edit', compact('jenisSurat'));
    }

    public function update(JenisSuratRequest $request, JenisSurat $jenisSurat): RedirectResponse
    {
        Gate::authorize('jenis_surat.update');
        $jenisSurat->update($request->validated());
        return redirect()->route('jenis-surat.index')->with('success', 'Jenis Surat berhasil diperbarui!');
    }

    public function destroy(JenisSurat $jenisSurat): RedirectResponse
    {
        Gate::authorize('jenis_surat.delete');
        $jenisSurat->delete();
        return redirect()->route('jenis-surat.index')->with('success', 'Jenis Surat berhasil dihapus!');
    }
}

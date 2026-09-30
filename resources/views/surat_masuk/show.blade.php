@extends('layouts.app')

@section('title', 'Detail Arsip Surat Masuk')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6 animate-fade-in">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm transition-all">
        <div class="flex items-start gap-4">
            <div class="p-3 bg-teal-100 dark:bg-teal-900/40 rounded-xl text-teal-600 dark:text-teal-400 shrink-0">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-slate-800 dark:text-white tracking-tight">Detail Arsip Surat Masuk</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">Informasi lengkap surat masuk, catatan disposisi, dan dokumen fisik digital yang di-scan.</p>
            </div>
        </div>
        <div class="flex items-center gap-2 shrink-0 w-full sm:w-auto justify-end">
            <a href="{{ route('surat-masuk.edit', $suratMasuk->id) }}" class="px-4 py-2.5 bg-amber-500 text-white font-semibold rounded-xl hover:bg-amber-600 transition text-sm flex items-center gap-2 shadow-sm focus:ring-2 focus:ring-amber-400 focus:outline-none">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                Edit Surat
            </a>
            <a href="{{ route('surat-masuk.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-600 dark:text-slate-300 font-semibold rounded-xl transition text-sm flex items-center gap-2 focus:ring-2 focus:ring-slate-400 focus:outline-none">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 shadow-sm rounded-2xl border border-slate-200 dark:border-slate-700 p-6 md:p-8 space-y-6">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-b border-slate-100 dark:border-slate-700 pb-6">
            <div class="space-y-1">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    Nomor Surat
                </span>
                <p class="text-lg font-extrabold text-slate-900 dark:text-white font-mono">{{ $suratMasuk->nomor_surat }}</p>
            </div>
            <div class="space-y-1">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    Asal Surat / Instansi
                </span>
                <p class="text-base font-extrabold text-blue-600 dark:text-blue-400 mt-1">{{ $suratMasuk->asal_surat }}</p>
            </div>
        </div>

        <div>
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-2 flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                Perihal Surat
            </span>
            <div class="text-slate-800 dark:text-slate-200 bg-slate-50 dark:bg-slate-900/50 p-4 rounded-xl border border-slate-200 dark:border-slate-700/60 leading-relaxed font-medium">
                {{ $suratMasuk->perihal }}
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-slate-100 dark:border-slate-700 pt-6">
            <div class="space-y-1">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Tanggal Tertera pada Surat
                </span>
                <p class="text-slate-800 dark:text-slate-200 font-semibold mt-1">{{ \Carbon\Carbon::parse($suratMasuk->tanggal_surat)->translatedFormat('d F Y') }}</p>
            </div>
            <div class="space-y-1">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    Tanggal Diterima di Desa
                </span>
                <p class="text-slate-800 dark:text-slate-200 font-semibold mt-1">{{ \Carbon\Carbon::parse($suratMasuk->tanggal_diterima)->translatedFormat('d F Y') }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-slate-100 dark:border-slate-700 pt-6">
            <div class="space-y-1">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Disposisi Kepada
                </span>
                <div class="mt-1">
                    @if($suratMasuk->disposisi_kepada)
                        <span class="px-3 py-1 bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 rounded-xl font-bold border border-amber-200 dark:border-amber-800/50 shadow-sm inline-block text-xs">
                            {{ $suratMasuk->disposisi_kepada }}
                        </span>
                    @else
                        <span class="text-sm text-slate-400 italic">Belum ada disposisi</span>
                    @endif
                </div>
            </div>
            <div class="space-y-1">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Keterangan Tambahan
                </span>
                <p class="text-sm text-slate-800 dark:text-slate-200 font-medium mt-1">{{ $suratMasuk->keterangan ?? '-' }}</p>
            </div>
        </div>

        <div class="border-t border-slate-100 dark:border-slate-700 pt-6">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-3 flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                Dokumen / Scan Surat Masuk
            </span>
            @if($suratMasuk->file_scan)
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between p-4 bg-blue-50/60 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-900/40 rounded-2xl gap-4">
                    <div class="flex items-center space-x-3">
                        <div class="p-2.5 bg-blue-100 dark:bg-blue-900/40 rounded-xl text-blue-600 dark:text-blue-400 shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-blue-900 dark:text-blue-200 break-all">{{ basename($suratMasuk->file_scan) }}</p>
                            <span class="text-xs text-blue-600 dark:text-blue-400 font-medium flex items-center gap-1 mt-0.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span> File arsip digital terlampir dan siap diunduh
                            </span>
                        </div>
                    </div>
                    <a href="{{ route('surat-masuk.file', $suratMasuk) }}" class="w-full sm:w-auto px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-md shadow-blue-500/25 transition flex items-center justify-center gap-2 shrink-0 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        Buka / Download File
                    </a>
                </div>
            @else
                <div class="p-4 bg-slate-50 dark:bg-slate-900/30 border border-slate-200 dark:border-slate-700 rounded-xl text-center">
                    <p class="text-sm text-slate-400 italic">Tidak ada file scan dokumen yang diunggah untuk arsip ini.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

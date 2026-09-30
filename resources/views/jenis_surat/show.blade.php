@extends('layouts.app')

@section('title', 'Detail Jenis Surat')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6 animate-fade-in">

    <!-- HEADER SECTION -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white dark:bg-slate-800 p-6 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm transition-all">
        <div class="flex items-center gap-4">
            <div class="p-3.5 bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 rounded-2xl shadow-inner shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <div>
                <h2 class="text-2xl font-extrabold text-slate-800 dark:text-white tracking-tight">Detail Jenis Surat</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">Informasi lengkap mengenai template jenis surat desa.</p>
            </div>
        </div>
        <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
            <a href="{{ route('jenis-surat.index') }}" class="px-4 py-2.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 text-sm font-bold transition-all shadow-sm active:scale-95 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
            <a href="{{ route('jenis-surat.edit', $jenisSurat->uuid) }}" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-sm shadow-md shadow-blue-500/20 transition-all active:scale-95 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                Edit Data
            </a>
        </div>
    </div>

    <!-- CONTENT CARD -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 sm:p-8 space-y-6">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- KODE SURAT -->
            <div class="p-5 bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-slate-100 dark:border-slate-700/60">
                <p class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Kode Surat</p>
                <p class="text-base font-extrabold text-slate-900 dark:text-white font-mono">{{ $jenisSurat->kode_surat }}</p>
            </div>

            <!-- STATUS TEMPLATE -->
            <div class="p-5 bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-slate-100 dark:border-slate-700/60">
                <p class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Status Template</p>
                <div class="mt-1">
                    @if($jenisSurat->is_active)
                        <span class="px-3 py-1 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 font-bold text-xs rounded-lg border border-emerald-200 dark:border-emerald-800/50 shadow-sm inline-flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            Aktif (Bisa digunakan)
                        </span>
                    @else
                        <span class="px-3 py-1 bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-400 font-bold text-xs rounded-lg border border-red-200 dark:border-red-800/50 shadow-sm inline-flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-red-500"></span>
                            Nonaktif (Disembunyikan)
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- NAMA SURAT -->
        <div class="p-5 bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-slate-100 dark:border-slate-700/60">
            <p class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Nama Jenis Surat</p>
            <p class="text-lg font-bold text-slate-900 dark:text-white">{{ $jenisSurat->nama_surat }}</p>
        </div>

        <!-- DESKRIPSI -->
        <div class="p-5 bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-slate-100 dark:border-slate-700/60">
            <p class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Deskripsi / Keterangan Singkat</p>
            <p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed">
                {{ $jenisSurat->deskripsi ?? 'Tidak ada deskripsi yang ditambahkan untuk jenis surat ini.' }}
            </p>
        </div>

        <!-- METADATA TIMELINE -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4 border-t border-slate-100 dark:border-slate-700 text-xs text-slate-400 dark:text-slate-500">
            <div>
                <span class="font-semibold">Dibuat pada:</span> {{ $jenisSurat->created_at ? $jenisSurat->created_at->translatedFormat('d F Y - H:i') : '-' }}
            </div>
            <div>
                <span class="font-semibold">Terakhir diperbarui:</span> {{ $jenisSurat->updated_at ? $jenisSurat->updated_at->translatedFormat('d F Y - H:i') : '-' }}
            </div>
        </div>

    </div>
</div>
@endsection

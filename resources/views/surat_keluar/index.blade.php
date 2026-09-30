@extends('layouts.app')

@section('title', 'E-Surat Keluar - Desa Ridogalih')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6 animate-fade-in">

    <!-- HEADER SECTION -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm transition-all">
        <div class="flex items-start gap-4">
            <div class="p-3 bg-blue-100 dark:bg-blue-900/40 rounded-xl text-blue-600 dark:text-blue-400 shrink-0">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <div>
                <h2 class="text-2xl font-extrabold text-slate-800 dark:text-white tracking-tight">Data Pengajuan Surat Keluar</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">Kelola, review, dan pantau status pengajuan administrasi surat desa secara digital.</p>
            </div>
        </div>
        @can('surat_keluar.create')
        <a href="{{ route('surat-keluar.create') }}" class="px-5 py-2.5 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition shadow-md shadow-blue-500/20 flex items-center gap-2 text-sm shrink-0 focus:ring-2 focus:ring-blue-500 focus:outline-none">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Buat Pengajuan Baru
        </a>
        @endcan
    </div>

    <!-- ALERT MESSAGES -->
    @if (session('success'))
    <div class="p-4 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 rounded-2xl font-medium flex items-center gap-3 shadow-sm animate-fade-in">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        {{ session('success') }}
    </div>
    @endif

    @if (session('error'))
    <div class="p-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 rounded-2xl font-medium flex items-center gap-3 shadow-sm animate-fade-in">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        {{ session('error') }}
    </div>
    @endif

    <!-- DATA TABLE -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden flex flex-col">
        <div class="overflow-x-auto relative">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wider border-b border-slate-200 dark:border-slate-700 font-semibold">
                        <th class="py-4 px-6">Tanggal</th>
                        <th class="py-4 px-6">Nomor Surat</th>
                        <th class="py-4 px-6">Jenis Surat</th>
                        <th class="py-4 px-6">Pemohon (Penduduk)</th>
                        <th class="py-4 px-6 text-center">Status</th>
                        <th class="py-4 px-6 text-center">Aksi Pilihan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50 text-sm text-slate-700 dark:text-slate-300">
                    @forelse ($surat_keluars as $surat)
                    <tr class="hover:bg-slate-50/75 dark:hover:bg-slate-800/60 transition-colors group">
                        <td class="py-4 px-6 font-medium text-slate-500 dark:text-slate-400 text-xs">
                            {{ $surat->created_at->format('d/m/Y') }}
                        </td>
                        <td class="py-4 px-6 font-bold text-slate-900 dark:text-white font-mono text-xs">
                            {{ $surat->nomor_surat ?? 'Belum ada nomor' }}
                        </td>
                        <td class="py-4 px-6">
                            <span class="font-bold text-indigo-600 dark:text-indigo-400 block">{{ $surat->jenis_surat->kode_surat }}</span>
                            <span class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 block">{{ $surat->jenis_surat->nama_surat }}</span>
                        </td>
                        <td class="py-4 px-6">
                            <span class="font-bold text-slate-900 dark:text-white block">{{ $surat->penduduk->nama_lengkap ?? 'Data tidak ditemukan' }}</span>
                            <span class="text-xs text-slate-500 dark:text-slate-400 font-mono mt-0.5 block">NIK: {{ $surat->penduduk?->nik ?? '-' }}</span>
                        </td>
                        <td class="py-4 px-6 text-center">
                            @if($surat->status == 'Menunggu')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400 shadow-sm">
                                    <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 animate-pulse"></span> Menunggu
                                </span>
                            @elseif($surat->status == 'Diproses')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400 shadow-sm">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span> Diproses
                                </span>
                            @elseif($surat->status == 'Disetujui')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400 shadow-sm">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Disetujui
                                </span>
                            @elseif($surat->status == 'Selesai')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 shadow-sm">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Selesai & Dicetak
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 shadow-sm">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Ditolak
                                </span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-center">
                            <div class="flex items-center justify-center gap-1.5 opacity-90 group-hover:opacity-100 transition-opacity">
                                <!-- TOMBOL CETAK PDF (Hanya jika disetujui/selesai dan punya akses print) -->
                                @if(in_array($surat->status, ['Disetujui', 'Selesai']))
                                    @can('surat_keluar.print')
                                    <a href="{{ route('surat-keluar.print', $surat->uuid) }}" target="_blank" class="px-3 py-2 bg-emerald-50 text-emerald-600 dark:bg-emerald-900/20 dark:text-emerald-400 hover:bg-emerald-100 dark:hover:bg-emerald-900/40 rounded-xl font-bold text-xs transition flex items-center gap-1 shadow-sm" title="Cetak PDF">
                                       <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                    </a>
                                    @endcan
                                @endif

                                <!-- TOMBOL DETAIL / REVIEW (Menggunakan UUID) -->
                                @can('surat_keluar.view')
                                <a href="{{ route('surat-keluar.show', $surat->uuid) }}" class="p-2 bg-indigo-50 text-indigo-600 dark:bg-indigo-900/20 dark:text-indigo-400 hover:bg-indigo-100 dark:hover:bg-indigo-900/40 rounded-xl transition shadow-sm" title="Detail & Proses Review">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                                @endcan

                                <!-- TOMBOL HAPUS / TARIK PENGAJUAN (Menggunakan UUID) -->
                                @can('surat_keluar.delete')
                                <form action="{{ route('surat-keluar.destroy', $surat->uuid) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus atau menarik pengajuan surat ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 bg-red-50 text-red-600 dark:bg-red-900/20 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/40 rounded-xl transition shadow-sm" title="Hapus Riwayat">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-16 text-center">
                            <div class="flex flex-col items-center justify-center text-slate-400 space-y-2">
                                <svg class="w-12 h-12 text-slate-300 dark:text-slate-600 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Belum ada data pengajuan surat keluar yang tercatat di sistem.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

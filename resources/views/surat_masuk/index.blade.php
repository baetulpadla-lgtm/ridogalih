@extends('layouts.app')

@section('title', 'Arsip Surat Masuk')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    <!-- HEADER SECTION -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm transition-all">
        <div class="flex items-start gap-4">
            <div class="p-3 bg-blue-100 dark:bg-blue-900/40 rounded-xl text-blue-600 dark:text-blue-400 shrink-0">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-slate-800 dark:text-white tracking-tight">Arsip Surat Masuk</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">Kelola dan pantau surat resmi yang masuk ke Pemerintahan Desa secara digital.</p>
            </div>
        </div>
        <a href="{{ route('surat-masuk.create') }}" class="px-5 py-2.5 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition-all duration-200 shadow-md shadow-blue-500/20 flex items-center gap-2 text-sm shrink-0 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-slate-800 focus:outline-none active:scale-95">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Surat Masuk
        </a>
    </div>

    <!-- FLASH MESSAGE (SMOOTH ALPINE.JS INTEGRATION) -->
    @if(session('success'))
        <div x-data="{ show: true }"
             x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-2"
             x-init="setTimeout(() => show = false, 4000)"
             class="p-4 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 rounded-2xl font-medium flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
            <button @click="show = false" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-300 transition-colors focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    @endif

    <!-- SEARCH FILTER SECTION -->
    <div class="bg-white dark:bg-slate-800 p-4 md:p-5 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700">
        <form action="{{ route('surat-masuk.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 items-center">
            <div class="flex-1 relative w-full">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor surat, asal instansi, atau perihal surat..." class="w-full pl-11 pr-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-xl text-sm text-slate-800 dark:text-white focus:ring-2 focus:ring-blue-500 focus:outline-none transition-all">
                <!-- Ikon Pencarian Terkunci Presisi di Tengah Vertikal -->
                <svg class="w-5 h-5 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <button type="submit" class="flex-1 sm:flex-none px-6 py-2.5 bg-slate-800 dark:bg-slate-700 hover:bg-slate-900 dark:hover:bg-slate-600 text-white text-sm font-semibold rounded-xl transition-all shadow-sm active:scale-95">
                    Cari Arsip
                </button>
                @if(request('search'))
                    <a href="{{ route('surat-masuk.index') }}" class="px-4 py-2.5 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/40 text-red-600 dark:text-red-400 text-sm font-semibold rounded-xl transition-all flex items-center justify-center border border-red-200 dark:border-red-800 active:scale-95" title="Reset Pencarian">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- DATA TABLE SECTION -->
    <div class="bg-white dark:bg-slate-800 shadow-sm rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-700 flex flex-col">
        <div class="overflow-x-auto relative">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50/80 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 uppercase text-xs tracking-wider font-bold border-b border-slate-200 dark:border-slate-700">
                        <th class="py-4 px-6">No. Surat & Asal Instansi</th>
                        <th class="py-4 px-6">Perihal Surat</th>
                        <th class="py-4 px-6">Tanggal (Surat / Diterima)</th>
                        <th class="py-4 px-6">Disposisi</th>
                        <th class="py-4 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50 text-sm text-slate-700 dark:text-slate-300">
                    @forelse($suratMasuks as $surat)
                    <tr class="hover:bg-slate-50/75 dark:hover:bg-slate-800/60 transition-colors group">
                        <td class="py-4 px-6">
                            <span class="font-bold block text-slate-900 dark:text-white">{{ $surat->nomor_surat }}</span>
                            <span class="text-xs font-semibold text-blue-600 dark:text-blue-400 mt-0.5 block">Asal: {{ $surat->asal_surat }}</span>
                        </td>
                        <td class="py-4 px-6 max-w-xs truncate text-slate-800 dark:text-slate-200" title="{{ $surat->perihal }}">
                            {{ Str::limit($surat->perihal, 45) }}
                        </td>
                        <td class="py-4 px-6 text-xs space-y-1">
                            <div class="flex items-center gap-1.5 text-slate-600 dark:text-slate-300">
                                <span class="font-medium text-slate-400">Surat:</span> {{ \Carbon\Carbon::parse($surat->tanggal_surat)->format('d/m/Y') }}
                            </div>
                            <div class="flex items-center gap-1.5 text-emerald-600 dark:text-emerald-400 font-semibold">
                                <span class="text-slate-400 font-medium">Terima:</span> {{ \Carbon\Carbon::parse($surat->tanggal_diterima)->format('d/m/Y') }}
                            </div>
                        </td>
                        <td class="py-4 px-6 text-xs">
                            @if($surat->disposisi_kepada)
                                <span class="px-3 py-1 bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 rounded-lg font-bold border border-amber-200 dark:border-amber-800/50 shadow-sm inline-block">
                                    {{ $surat->disposisi_kepada }}
                                </span>
                            @else
                                <span class="text-slate-400 italic font-medium px-2 py-1 bg-slate-50 dark:bg-slate-800 rounded-md border border-slate-100 dark:border-slate-700 inline-block">Belum didisposisi</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-center">
                            <div class="flex items-center justify-center gap-2 opacity-90 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('surat-masuk.show', $surat->uuid) }}" class="p-2 bg-teal-50 text-teal-600 dark:bg-teal-900/20 dark:text-teal-400 hover:bg-teal-100 dark:hover:bg-teal-900/40 rounded-xl transition-all shadow-sm hover:scale-105" title="Detail Arsip">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                                <a href="{{ route('surat-masuk.edit', $surat->uuid) }}" class="p-2 bg-amber-50 text-amber-600 dark:bg-amber-900/20 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-900/40 rounded-xl transition-all shadow-sm hover:scale-105" title="Edit Arsip">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>
                                <form action="{{ route('surat-masuk.destroy', $surat->uuid) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus arsip ini? Data yang dihapus tidak dapat dikembalikan.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 bg-red-50 text-red-600 dark:bg-red-900/20 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/40 rounded-xl transition-all shadow-sm hover:scale-105" title="Hapus Arsip">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-16 text-center">
                            <div class="flex flex-col items-center justify-center text-slate-400 space-y-3">
                                <div class="w-16 h-16 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mb-2 border border-slate-100 dark:border-slate-700">
                                    <svg class="w-8 h-8 text-slate-300 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                </div>
                                <p class="text-sm font-semibold text-slate-500 dark:text-slate-400">Arsip Tidak Ditemukan</p>
                                <p class="text-xs text-slate-400 dark:text-slate-500 max-w-sm text-center">Belum ada data surat masuk yang dicatat, atau hasil pencarian Anda tidak cocok dengan data mana pun.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 sm:px-6 border-t border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/30">
            <!-- Menghapus withQueryString() berlebih karena sudah ada di Controller -->
            {{ $suratMasuks->links() }}
        </div>
    </div>

</div>
@endsection

@extends('layouts.app')

@section('title', 'Data Penduduk - Desa Ridogalih')

@section('content')
<div class="space-y-6 animate-fade-in" x-data="{
    deleteModalOpen: false,
    deleteUrl: '',
    deleteNik: '',
    openDeleteModal(url, nik) {
        this.deleteUrl = url;
        this.deleteNik = nik;
        this.deleteModalOpen = true;
    }
}">

    <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-3xl border border-slate-200 dark:border-slate-700 p-6 shadow-sm flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 transition-all relative z-10">
        <div class="flex items-start gap-4">
            <div class="p-3.5 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl text-white shrink-0 shadow-lg shadow-blue-500/30">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div>
                <h2 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">Master Data Penduduk</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 leading-relaxed max-w-2xl">
                    Basis data tunggal (Single Source of Truth) warga Desa Ridogalih. Kelola biodata, cetak berkas, serta import/export data secara aman.
                </p>
            </div>
        </div>

        <div class="flex flex-wrap gap-3 w-full lg:w-auto justify-end">
            @can('penduduk.create')
            <div x-data="{ modalImportOpen: false }">
                <button @click="modalImportOpen = true" type="button" class="flex items-center px-4 py-2.5 bg-slate-100 dark:bg-slate-700/50 text-slate-700 dark:text-slate-200 text-sm font-bold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors border border-slate-200 dark:border-slate-600 shadow-sm focus:ring-2 focus:ring-slate-300">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg> Import
                </button>

                <div x-show="modalImportOpen" x-cloak class="fixed inset-0 z-[999] flex items-center justify-center bg-slate-950/60 backdrop-blur-sm p-4"
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                    <div @click.away="modalImportOpen = false" class="bg-white dark:bg-slate-800 w-full max-w-md rounded-3xl shadow-2xl overflow-hidden border border-slate-200 dark:border-slate-700 transform transition-all">
                        <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
                            <h3 class="text-lg font-extrabold text-slate-900 dark:text-white">Import Data (Excel/CSV)</h3>
                            <button @click="modalImportOpen = false" class="text-slate-400 hover:text-red-500 bg-slate-50 hover:bg-red-50 dark:bg-slate-700 dark:hover:bg-red-900/30 p-2 rounded-full transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                        </div>
                        <form action="{{ route('penduduk.import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="p-6 space-y-4 bg-slate-50/50 dark:bg-slate-900/50">
                                <a href="{{ route('penduduk.download.template') }}" class="flex items-center justify-between p-3 rounded-xl bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800/50 hover:bg-blue-100 transition group">
                                    <div class="flex items-center gap-3">
                                        <div class="p-2 bg-blue-100 dark:bg-blue-800/50 rounded-lg text-blue-600 dark:text-blue-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg></div>
                                        <div class="text-left"><p class="text-sm font-bold text-blue-700 dark:text-blue-400">Unduh Template Resmi</p><p class="text-[10px] text-blue-500">Format standar E-Office (.xlsx)</p></div>
                                    </div>
                                    <svg class="w-4 h-4 text-blue-500 transform group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </a>
                                <div class="relative border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-2xl p-6 text-center hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                                    <input type="file" name="file_import" id="file_import" accept=".xlsx, .xls, .csv" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                    <svg class="w-8 h-8 text-slate-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                    <p class="text-sm font-bold text-slate-700 dark:text-slate-300">Pilih atau Tarik file ke sini</p>
                                    <p class="text-xs text-slate-500 mt-1">Maks. 5MB (.xlsx, .xls, .csv)</p>
                                </div>
                            </div>
                            <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700 flex justify-end gap-3">
                                <button type="button" @click="modalImportOpen = false" class="px-5 py-2.5 text-sm font-bold text-slate-600 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-xl hover:bg-slate-50 transition">Batal</button>
                                <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-emerald-600 rounded-xl hover:bg-emerald-700 shadow-lg shadow-emerald-500/30 transition">Mulai Import</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endcan

            @can('penduduk.export')
            <a href="{{ route('penduduk.exports') }}" class="flex items-center px-4 py-2.5 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 text-sm font-bold rounded-xl border border-slate-200 dark:border-slate-600 shadow-sm hover:bg-slate-50 transition">
                Unduhan
            </a>
            <div x-data="{ openExport: false }" class="relative z-50">
                <button @click="openExport = !openExport" class="flex items-center px-4 py-2.5 bg-slate-100 dark:bg-slate-700/50 text-slate-700 dark:text-slate-200 text-sm font-bold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition border border-slate-200 dark:border-slate-600 shadow-sm focus:ring-2 focus:ring-slate-300">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg> Export
                    <svg class="w-4 h-4 ml-1.5 transition-transform duration-200" :class="{ 'rotate-180': openExport }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="openExport" @click.away="openExport = false" x-cloak
                     x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                     class="absolute right-0 mt-2 w-48 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-xl z-50 overflow-hidden font-medium">
                    @can('penduduk.export')
                     <a href="{{ route('penduduk.export.pdf', request()->query()) }}" target="_blank" class="flex items-center px-4 py-3 text-sm text-slate-700 dark:text-slate-300 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-900/20 dark:hover:text-red-400 transition-colors">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg> Export PDF
                    </a>
                    @endcan
                    @can('penduduk.export')
                    <a href="{{ route('penduduk.export.excel', request()->query()) }}" class="flex items-center px-4 py-3 text-sm text-slate-700 dark:text-slate-300 hover:bg-emerald-50 hover:text-emerald-600 dark:hover:bg-emerald-900/20 dark:hover:text-emerald-400 border-t border-slate-100 dark:border-slate-700 transition-colors">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg> Export Excel
                    </a>
                    @endcan
                    @can('penduduk.export')
                    <a href="{{ route('penduduk.export.csv', request()->query()) }}" class="flex items-center px-4 py-3 text-sm text-slate-700 dark:text-slate-300 hover:bg-amber-50 hover:text-amber-600 dark:hover:bg-amber-900/20 dark:hover:text-amber-400 border-t border-slate-100 dark:border-slate-700 transition-colors">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg> Export CSV
                    </a>
                    @endcan
                </div>
            </div>
            @endcan

            @can('penduduk.create')
            <a href="{{ route('penduduk.create') }}" class="flex items-center px-5 py-2.5 bg-blue-600 text-white text-sm font-extrabold rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-500/30 focus:ring-2 focus:ring-blue-500 focus:outline-none transform hover:-translate-y-0.5">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg> Tambah Data
            </a>
            @endcan
        </div>
    </div>

    <!-- KARTU STATISTIK -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @php
            $stats = [
                ['title' => 'Total Seluruh Warga', 'val' => $totalWarga ?? 0, 'color' => 'slate', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
                ['title' => 'Warga Laki-laki', 'val' => $totalLaki ?? 0, 'color' => 'blue', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                ['title' => 'Warga Perempuan', 'val' => $totalPerempuan ?? 0, 'color' => 'pink', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                ['title' => 'Status Aktif', 'val' => $totalAktif ?? 0, 'color' => 'emerald', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
            ];
        @endphp

        @foreach($stats as $stat)
        <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-md p-5 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm flex items-center justify-between hover:shadow-md transition-all duration-300 transform hover:-translate-y-1">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-1">{{ $stat['title'] }}</p>
                <h4 class="text-3xl font-black text-slate-800 dark:text-white">{{ number_format($stat['val']) }}</h4>
            </div>
            <div class="p-3.5 bg-{{ $stat['color'] }}-50 dark:bg-{{ $stat['color'] }}-900/30 text-{{ $stat['color'] }}-600 dark:text-{{ $stat['color'] }}-400 rounded-2xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat['icon'] }}"></path></svg>
            </div>
        </div>
        @endforeach
    </div>

    <!-- ALERT MESSAGES -->
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.500ms class="flex items-center justify-between bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800/50 text-emerald-700 dark:text-emerald-400 text-sm font-bold px-5 py-4 rounded-2xl shadow-sm">
        <div class="flex items-center"><svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ session('success') }}</div>
        <button @click="show = false" class="text-emerald-500 hover:text-emerald-700 transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
    </div>
    @endif

    @if(session('error'))
    <div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.500ms class="flex items-center justify-between bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800/50 text-red-700 dark:text-red-400 text-sm font-bold px-5 py-4 rounded-2xl shadow-sm">
        <div class="flex items-center"><svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>{{ session('error') }}</div>
        <button @click="show = false" class="text-red-500 hover:text-red-700 transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
    </div>
    @endif

    <!-- DATA TABLE & FILTER -->
    <div class="rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm overflow-hidden flex flex-col relative z-0">
        <div class="p-5 border-b border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50">
            <form method="GET" action="{{ route('penduduk.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-center">
                <div class="lg:col-span-4 relative">
                    <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Ketik NIK, KK, atau Nama Warga..." autocomplete="off" aria-label="Cari warga" class="w-full pl-11 pr-4 py-3 text-sm font-medium border border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-900 text-slate-800 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-sm outline-none">
                    <svg class="w-5 h-5 absolute left-3.5 top-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <div class="lg:col-span-2 relative">
                    <select name="filter" aria-label="Filter Dusun" class="w-full pl-4 pr-10 py-3 text-sm font-medium border border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 appearance-none focus:ring-2 focus:ring-blue-500 shadow-sm outline-none cursor-pointer">
                        <option value="">Semua Dusun</option>
                        @foreach(\App\Enums\Dusun::cases() as $dusun)
                            <!-- FIXED: request('filter') tanpa referensi objek paginator -->
                            <option value="{{ $dusun->value }}" {{ request('filter') == $dusun->value ? 'selected' : '' }}>
                                {{ $dusun->value }}
                            </option>
                        @endforeach
                    </select>
                    <svg class="w-5 h-5 absolute right-3.5 top-3 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg>
                </div>
                <div class="lg:col-span-2 relative">
                    <select name="status" aria-label="Filter Status" class="w-full pl-4 pr-10 py-3 text-sm font-medium border border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 appearance-none focus:ring-2 focus:ring-blue-500 shadow-sm outline-none cursor-pointer">
                        <option value="">Semua Status</option>
                            @foreach(\App\Enums\StatusKependudukan::cases() as $status)
                                <!-- FIXED: request('status') tanpa referensi objek paginator -->
                                <option value="{{ $status->value }}" {{ request('status') == $status->value ? 'selected' : '' }}>
                                    {{ $status->value }}
                                </option>
                            @endforeach
                    </select>
                    <svg class="w-5 h-5 absolute right-3.5 top-3 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg>
                </div>
                <div class="lg:col-span-2 relative">
                    <select name="limit" aria-label="Limit Baris" class="w-full pl-4 pr-10 py-3 text-sm font-medium border border-slate-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 appearance-none focus:ring-2 focus:ring-blue-500 shadow-sm outline-none cursor-pointer">
                        <option value="10" {{ request('limit') == 10 ? 'selected' : '' }}>10 Baris</option>
                        <option value="50" {{ request('limit') == 50 ? 'selected' : '' }}>50 Baris</option>
                        <option value="100" {{ request('limit') == 100 ? 'selected' : '' }}>100 Baris</option>
                    </select>
                    <svg class="w-5 h-5 absolute right-3.5 top-3 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg>
                </div>
                <div class="lg:col-span-2 flex gap-2">
                    <button type="submit" class="flex-1 py-3 bg-slate-800 dark:bg-slate-200 text-white dark:text-slate-800 text-sm font-bold rounded-xl hover:bg-slate-700 dark:hover:bg-slate-300 transition shadow-sm text-center focus:ring-2 focus:ring-slate-500">Filter</button>
                    @if(request('search') || request('filter') || request('status') || request('limit') != 10)
                    <a href="{{ route('penduduk.index') }}" class="px-3.5 py-3 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 rounded-xl hover:bg-red-100 transition flex items-center justify-center border border-red-200 dark:border-red-800/50 shadow-sm" title="Reset Filter">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="max-w-full overflow-x-auto relative">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead class="sticky top-0 z-10 bg-slate-50 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-200 dark:border-slate-700">
                    <tr class="text-slate-500 dark:text-slate-400 text-xs uppercase tracking-widest font-extrabold">
                        <th class="px-5 py-4 w-12 text-center">No</th>
                        <th class="px-5 py-4">Identitas Warga</th>
                        <th class="px-5 py-4">Tempat, Tgl Lahir</th>
                        <th class="px-5 py-4 text-center">L/P</th>
                        <th class="px-5 py-4">Domisili</th>
                        <th class="px-5 py-4 text-center">Status</th>
                        <th class="px-5 py-4 text-right">Opsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50 text-sm text-slate-700 dark:text-slate-300">
                    @forelse($penduduk as $index => $p)
                    <tr class="hover:bg-blue-50/50 dark:hover:bg-slate-700/30 transition-colors group">
                        <td class="px-5 py-4 text-center font-bold text-slate-400 dark:text-slate-500">
                            {{ $penduduk->firstItem() + $index }}
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-4">
                                <!-- FIXED: Hapus I/O Storage::exists di dalam Looping -->
                                @if(!empty($p->foto))
                                    <img src="{{ route('penduduk.photo', $p) }}" alt="Foto {{ $p->nama_lengkap }}" loading="lazy" class="w-11 h-11 rounded-full object-cover shrink-0 border-2 border-white dark:border-slate-800 shadow-md">
                                @else
                                    <div class="w-11 h-11 rounded-full bg-gradient-to-tr from-blue-500 to-indigo-500 flex items-center justify-center text-white font-black text-sm shrink-0 border-2 border-white dark:border-slate-800 shadow-md">
                                        {{ strtoupper(substr($p->nama_lengkap, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <div class="font-extrabold text-slate-900 dark:text-white">{{ $p->nama_lengkap }}</div>
                                    <div class="text-xs text-slate-500 dark:text-slate-400 font-mono mt-0.5 tracking-wide">
                                        NIK: <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $p->nik }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <div class="font-bold text-slate-700 dark:text-slate-200">{{ $p->tempat_lahir }}</div>
                            <div class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $p->tanggal_lahir ? \Carbon\Carbon::parse($p->tanggal_lahir)->translatedFormat('d M Y') : '-' }}</div>
                        </td>
                        <td class="px-5 py-4 text-center">
                            <!-- FIXED: Evaluasi Type-Casting Enum dengan Null-Safe ?->value -->
                            @php
                                $jkVal = $p->jenis_kelamin?->value ?? $p->jenis_kelamin;
                                $isLaki = $jkVal === \App\Enums\JenisKelamin::LAKI_LAKI->value;
                            @endphp
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full font-bold text-xs {{ $isLaki ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'bg-pink-100 text-pink-700 dark:bg-pink-900/30 dark:text-pink-400' }}">
                                {{ $isLaki ? 'L' : 'P' }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <!-- FIXED: Evaluasi Type-Casting Enum untuk Dusun & RT/RW -->
                            <div class="font-bold text-slate-800 dark:text-slate-200">Dusun {{ $p->dusun?->value ?? $p->dusun ?? '-' }}</div>
                            <div class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">RT {{ str_pad($p->rt?->value ?? $p->rt ?? 0, 3, '0', STR_PAD_LEFT) }} / RW {{ str_pad($p->rw?->value ?? $p->rw ?? 0, 3, '0', STR_PAD_LEFT) }}</div>
                        </td>
                        <td class="px-5 py-4 text-center">
                            <!-- FIXED: Pencocokan Zero-Hardcode dengan Enum Class -->
                            @php
                                $statusVal = $p->status_kependudukan?->value ?? \App\Enums\StatusKependudukan::AKTIF->value;

                                $statusColors = match($statusVal) {
                                    \App\Enums\StatusKependudukan::AKTIF->value => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800/50',
                                    \App\Enums\StatusKependudukan::MENINGGAL->value => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 border-slate-200 dark:border-slate-700',
                                    \App\Enums\StatusKependudukan::PINDAH->value => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 border-amber-200 dark:border-amber-800/50',
                                    \App\Enums\StatusKependudukan::HILANG->value => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 border-red-200 dark:border-red-800/50',
                                    default => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 border-slate-200 dark:border-slate-700'
                                };

                                $dotColor = match($statusVal) {
                                    \App\Enums\StatusKependudukan::AKTIF->value => 'bg-emerald-500 animate-pulse',
                                    \App\Enums\StatusKependudukan::MENINGGAL->value => 'bg-slate-500',
                                    \App\Enums\StatusKependudukan::PINDAH->value => 'bg-amber-500',
                                    \App\Enums\StatusKependudukan::HILANG->value => 'bg-red-500 animate-pulse',
                                    default => 'bg-slate-400'
                                };
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-[10px] font-black uppercase tracking-widest rounded-full shadow-sm border {{ $statusColors }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $dotColor }}"></span>
                                {{ $statusVal }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-80 group-hover:opacity-100 transition-opacity">
                                @can('penduduk.view')
                                <a href="{{ route('penduduk.show', $p->uuid) }}" title="Lihat Detail" class="p-2 text-blue-600 bg-blue-50 dark:bg-blue-900/20 dark:text-blue-400 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/40 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                                @endcan

                                @can('penduduk.view')
                                <a href="{{ route('penduduk.print', $p->uuid) }}" target="_blank" title="Cetak Biodata" class="p-2 text-purple-600 bg-purple-50 dark:bg-purple-900/20 dark:text-purple-400 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-900/40 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                </a>
                                @endcan

                                @can('penduduk.update')
                                <a href="{{ route('penduduk.edit', $p->uuid) }}" title="Edit Data" class="p-2 text-amber-600 bg-amber-50 dark:bg-amber-900/20 dark:text-amber-400 rounded-lg hover:bg-amber-100 dark:hover:bg-amber-900/40 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                @endcan

                                @can('penduduk.delete')
                                <button @click="openDeleteModal('{{ route('penduduk.destroy', $p->uuid) }}', '{{ $p->nik }}')" title="Hapus Data" class="p-2 text-red-600 bg-red-50 dark:bg-red-900/20 dark:text-red-400 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/40 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-20 text-center">
                            <div class="flex flex-col items-center justify-center text-slate-400 space-y-4">
                                <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mb-2">
                                    <svg class="w-8 h-8 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                </div>
                                <h3 class="text-base font-bold text-slate-700 dark:text-slate-300">Data Tidak Ditemukan</h3>
                                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Belum ada data penduduk atau hasil filter pencarian tidak cocok.</p>
                                @if(request('search') || request('filter') || request('status'))
                                <a href="{{ route('penduduk.index') }}" class="mt-2 text-blue-600 font-bold hover:underline">Reset Pencarian</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800">
            {{ $penduduk->appends(request()->query())->links() }}
        </div>
    </div>

    <!-- MODAL HAPUS (Tidak Ada Perubahan - Sudah Aman) -->
    <div x-show="deleteModalOpen" x-cloak class="fixed inset-0 z-[999] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4"
         x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div @click.away="deleteModalOpen = false" class="bg-white dark:bg-slate-800 w-full max-w-md rounded-3xl shadow-2xl overflow-hidden transform transition-all border border-slate-200 dark:border-slate-700"
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100">
            <div class="p-6 sm:p-8 text-center">
                <div class="w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-5">
                    <svg class="w-8 h-8 text-red-600 dark:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <h3 class="text-xl font-extrabold text-slate-900 dark:text-white mb-2">Hapus Data Permanen?</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">Anda yakin ingin menghapus data penduduk dengan NIK <strong class="text-slate-800 dark:text-slate-200" x-text="deleteNik"></strong>? Tindakan ini permanen dan tidak dapat dibatalkan.</p>
                <div class="flex gap-3 justify-center">
                    <button @click="deleteModalOpen = false" type="button" class="px-5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 font-bold hover:bg-slate-50 dark:hover:bg-slate-700 transition w-full">Batal</button>
                    <form :action="deleteUrl" method="POST" class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white font-bold shadow-lg shadow-red-500/30 transition focus:ring-2 focus:ring-red-500 focus:outline-none">Ya, Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

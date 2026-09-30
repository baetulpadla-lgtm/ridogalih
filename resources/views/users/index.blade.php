@extends('layouts.app')

@section('title', 'Manajemen Pengguna - Desa Ridogalih')

@section('content')
<div class="w-full xl:max-w-[96%] mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6 animate-fade-in" x-data="{
    deleteModalOpen: false,
    deleteUrl: '',
    deleteName: '',
    openDeleteModal(url, name) {
        this.deleteUrl = url;
        this.deleteName = name;
        this.deleteModalOpen = true;
    }
}">

    <!-- HEADER SECTION -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm relative z-20">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800 dark:text-white tracking-tight">Manajemen Akun Pengguna</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Kelola akses login, Group lembaga, dan Role jabatan sistem E-Office Desa Ridogalih.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <!-- EXPORT DROPDOWN (ALPINE.JS) -->
            @can('users.export')
            <div x-data="{ openExport: false }" class="relative z-50">
                <button @click="openExport = !openExport" type="button" class="px-4 py-2.5 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 font-semibold rounded-xl border border-emerald-200 dark:border-emerald-800 hover:bg-emerald-100 dark:hover:bg-emerald-900/50 transition shadow-sm flex items-center gap-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Export Data
                    <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': openExport }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="openExport" @click.away="openExport = false" x-cloak
                     x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                     class="absolute right-0 mt-2 w-48 bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden z-[100]">
                    @can('users.export')
                     <a href="{{ route('users.export', ['format' => 'csv']) }}" class="block px-4 py-2.5 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 font-medium transition-colors">📄 Export CSV</a>
                    @endcan
                    @can('users.export')
                     <a href="{{ route('users.export', ['format' => 'excel']) }}" class="block px-4 py-2.5 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 font-medium border-t border-slate-100 dark:border-slate-700 transition-colors">📊 Export Excel (.xls)</a>
                    @endcan
                    @can('users.export')
                     <a href="{{ route('users.export', ['format' => 'pdf']) }}" target="_blank" class="block px-4 py-2.5 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 font-medium border-t border-slate-100 dark:border-slate-700 transition-colors">📕 Export PDF</a>
                    @endcan
                </div>
            </div>
            @endcan

            <!-- ADD BUTTON -->
            @can('users.create')
            <a href="{{ route('users.create') }}" class="px-5 py-2.5 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition shadow-md shadow-blue-500/20 flex items-center gap-2 text-sm focus:ring-2 focus:ring-blue-500/50 focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Daftarkan Akun
            </a>
            @endcan
        </div>
    </div>

    <!-- ALERT MESSAGES -->
    <div class="flex flex-col gap-3">
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" class="p-4 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 rounded-xl font-medium flex items-center justify-between shadow-sm animate-fade-in">
                <div class="flex items-center gap-3"><svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>{{ session('success') }}</div>
                <button @click="show = false" class="text-emerald-500 hover:text-emerald-700 transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
        @endif

        @if(session('error'))
            <div x-data="{ show: true }" x-show="show" class="p-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 rounded-xl font-medium flex items-center justify-between shadow-sm animate-fade-in">
                <div class="flex items-center gap-3"><svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>{{ session('error') }}</div>
                <button @click="show = false" class="text-red-500 hover:text-red-700 transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
        @endif
    </div>

    <!-- STATISTIC CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 relative z-10">
        <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm flex items-center gap-4 hover:-translate-y-1 transition-transform duration-300">
            <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold uppercase tracking-wider">Total Akun Terdaftar</p>
                <p class="text-xl font-extrabold text-slate-800 dark:text-white mt-0.5">{{ number_format($totalUsers ?? $users->total() ?? 0) }}</p>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm flex items-center gap-4 hover:-translate-y-1 transition-transform duration-300">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold uppercase tracking-wider">Status Aktif</p>
                <p class="text-xl font-extrabold text-slate-800 dark:text-white mt-0.5">{{ number_format($totalAktif ?? 0) }}</p>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm flex items-center gap-4 hover:-translate-y-1 transition-transform duration-300">
            <div class="w-12 h-12 rounded-xl bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <div>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold uppercase tracking-wider">Nonaktif / Suspend</p>
                <p class="text-xl font-extrabold text-slate-800 dark:text-white mt-0.5">{{ number_format($totalSuspend ?? 0) }}</p>
            </div>
        </div>
    </div>

    <!-- FILTER & SEARCH FORM -->
    <div class="bg-white dark:bg-slate-800 p-4 sm:p-5 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm relative z-0">
        <form action="{{ route('users.index') }}" method="GET" class="flex flex-col md:flex-row gap-3">
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" id="search" name="search" value="{{ request('search') }}" autocomplete="off" aria-label="Cari pengguna" placeholder="Cari berdasarkan nama, NIK, atau email..." class="block w-full pl-10 pr-3 py-2.5 border border-slate-300 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white text-sm transition placeholder-slate-400">
            </div>
            <div class="w-full md:w-44">
                <select name="status" aria-label="Filter status" class="block w-full px-3 py-2.5 border border-slate-300 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white text-sm transition cursor-pointer">
                    <option value="">Semua Status</option>
                    <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="Nonaktif" {{ request('status') == 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    <option value="Suspend" {{ request('status') == 'Suspend' ? 'selected' : '' }}>Suspend</option>
                </select>
            </div>
            <div class="w-full md:w-48">
                <select name="sort" aria-label="Urutkan data" class="block w-full px-3 py-2.5 border border-slate-300 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white text-sm transition cursor-pointer">
                    <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru Didaftarkan</option>
                    <option value="terlama" {{ request('sort') == 'terlama' ? 'selected' : '' }}>Terlama Didaftarkan</option>
                    <option value="nama_asc" {{ request('sort') == 'nama_asc' ? 'selected' : '' }}>Nama (A - Z)</option>
                    <option value="nama_desc" {{ request('sort') == 'nama_desc' ? 'selected' : '' }}>Nama (Z - A)</option>
                </select>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 md:flex-none px-5 py-2.5 bg-slate-800 dark:bg-slate-200 text-white dark:text-slate-800 font-semibold rounded-xl hover:bg-slate-700 dark:hover:bg-slate-300 transition text-sm shadow-sm focus:ring-2 focus:ring-slate-500 focus:outline-none">Terapkan</button>
                @if(request()->anyFilled(['search', 'status', 'sort']))
                    <a href="{{ route('users.index') }}" class="px-4 py-2.5 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 font-semibold rounded-xl hover:bg-red-100 dark:hover:bg-red-900/40 transition text-sm flex items-center justify-center border border-red-200 dark:border-red-800">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <!-- DATA TABLE -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden relative z-0">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wider border-b border-slate-200 dark:border-slate-700">
                        <th class="py-4 px-6 font-semibold">Profil & NIK</th>
                        <th class="py-4 px-6 font-semibold">Group & Role (Jabatan)</th>
                        <th class="py-4 px-6 font-semibold">Status Akun</th>
                        <th class="py-4 px-6 font-semibold text-center">Aksi Pilihan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50 text-sm text-slate-700 dark:text-slate-300">
                    @forelse($users as $user)
                    <tr class="hover:bg-slate-50/75 dark:hover:bg-slate-800/60 transition-colors group">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                @if($user->penduduk && !empty($user->penduduk->foto) && \Illuminate\Support\Facades\Storage::disk('private')->exists($user->penduduk->foto))
                                    <img src="{{ route('penduduk.photo', $user->penduduk) }}" alt="Foto {{ $user->name }}" loading="lazy" class="w-10 h-10 rounded-full object-cover shrink-0 border border-slate-200 dark:border-slate-600 shadow-sm group-hover:shadow-md transition-shadow">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-white font-bold shrink-0 border border-blue-500 dark:border-indigo-500 shadow-sm group-hover:shadow-md transition-shadow">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <p class="font-bold text-slate-900 dark:text-white">{{ $user->name }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">NIK: <span class="font-mono font-medium">{{ $user->penduduk?->nik ?? $user->nik ?? 'Tidak diketahui' }}</span> | Email: {{ $user->email ?? '-' }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="py-4 px-6">
                            <span class="inline-block px-2.5 py-0.5 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 font-bold text-xs rounded-lg border border-indigo-200 dark:border-indigo-800 mb-1">
                                {{ $user->group?->name ?? 'Tanpa Group' }}
                            </span>
                            <div class="text-xs font-semibold text-slate-600 dark:text-slate-400 flex items-center gap-1.5 mt-0.5">
                                <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                Jabatan: <span class="text-slate-800 dark:text-slate-200 font-bold">{{ $user->role?->name ?? 'Belum ada Role' }}</span>
                            </div>
                        </td>

                        <td class="py-4 px-6">
                            @if($user->status_akun === 'Aktif')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 text-xs font-bold rounded-full"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Aktif</span>
                            @elseif($user->status_akun === 'Nonaktif')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-bold rounded-full"><span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Nonaktif</span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 text-xs font-bold rounded-full"><span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Suspend</span>
                            @endif
                        </td>

                        <td class="py-4 px-6 text-center">
                            <div class="flex items-center justify-center gap-1.5 opacity-80 group-hover:opacity-100 transition-opacity">
                                @can('users.view')
                                <a href="{{ route('users.show', $user->uuid) }}" class="p-2 text-blue-600 bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/40 rounded-xl transition-colors shadow-sm" title="Lihat Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                                @endcan
                                @can('users.update')
                                <a href="{{ route('users.edit', $user->uuid) }}" class="p-2 text-amber-600 bg-amber-50 dark:bg-amber-900/20 hover:bg-amber-100 dark:hover:bg-amber-900/40 rounded-xl transition-colors shadow-sm" title="Edit Akun">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>
                                @endcan
                                @can('users.view')
                                <a href="{{ route('users.print', $user->uuid) }}" target="_blank" class="p-2 text-slate-600 bg-slate-100 dark:bg-slate-700/50 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-xl transition-colors shadow-sm" title="Cetak ID Card">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                </a>
                                @endcan

                                <!-- PROTEKSI: Mencegah tombol hapus muncul di akun yang sedang login -->
                                @if($user->id !== auth()->id())
                                    @can('users.delete')
                                    <button @click="openDeleteModal('{{ route('users.destroy', $user->uuid) }}', '{{ addslashes($user->name) }}')" type="button" class="p-2 text-red-600 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/40 rounded-xl transition-colors shadow-sm" title="Hapus Akun">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                    @endcan
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-16 text-center">
                            <div class="max-w-xs mx-auto space-y-3">
                                <svg class="w-12 h-12 text-slate-300 dark:text-slate-600 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                <p class="text-slate-500 dark:text-slate-400 font-medium">Belum ada akun pengguna atau data pencarian tidak ditemukan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/30">
            {{ $users->appends(request()->query())->links() }}
        </div>
    </div>

    <!-- MODAL KONFIRMASI HAPUS (ALPINE.JS) -->
    <div x-show="deleteModalOpen" x-cloak class="fixed inset-0 z-[999] overflow-y-auto bg-slate-950/60 backdrop-blur-sm px-4 py-6 flex items-center justify-center"
         x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div @click.away="deleteModalOpen = false" class="bg-white dark:bg-slate-800 w-full max-w-md rounded-3xl shadow-2xl overflow-hidden border border-slate-200 dark:border-slate-700 transform transition-all my-auto"
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="p-6 sm:p-8 text-center">
                <div class="w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mx-auto mb-5">
                    <svg class="w-8 h-8 text-red-600 dark:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <h3 class="text-xl font-extrabold text-slate-900 dark:text-white mb-2">Hapus Akun Permanen?</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">Anda yakin ingin menghapus akses login untuk akun <strong class="text-slate-800 dark:text-slate-200" x-text="deleteName"></strong>? Data yang dihapus tidak dapat dipulihkan.</p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <button @click="deleteModalOpen = false" type="button" class="px-5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 font-bold hover:bg-slate-50 dark:hover:bg-slate-700 transition w-full">Batal</button>
                    <form :action="deleteUrl" method="POST" class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white font-bold shadow-lg shadow-red-500/30 transition">Ya, Hapus Akun</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

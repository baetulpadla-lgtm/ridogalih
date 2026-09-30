@extends('layouts.app')

@section('title', 'Manajemen Hak Akses & Role')

@section('content')
<div class="w-full xl:max-w-[96%] mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8" x-data="{ searchQuery: '' }">

    <!-- HEADER SECTION (Dashboard Aligned) -->
    <div class="relative p-6 sm:p-8 rounded-2xl bg-gradient-to-br from-blue-600 via-indigo-600 to-blue-800 text-white shadow-lg overflow-hidden flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-48 h-48 bg-white opacity-10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 space-y-3 w-full">
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 bg-white/20 backdrop-blur-md rounded-lg text-xs font-bold uppercase tracking-wider shadow-sm">
                    Keamanan Sistem
                </span>
            </div>
            <div class="pt-2">
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight drop-shadow-md">Pengaturan Privilege (Hak Akses)</h1>
                <p class="text-white/80 mt-1 text-sm sm:text-base max-w-xl font-medium">Kelola permission server-side berdasarkan struktur lembaga dan role jabatan. Menu hanya mengatur navigasi.</p>
            </div>
        </div>

        <div class="relative z-10 w-full md:w-72 mt-4 md:mt-0">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input x-model="searchQuery" type="text" autocomplete="off" placeholder="Cari nama jabatan..." class="w-full pl-10 pr-4 py-3 bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl text-sm font-medium text-white placeholder-indigo-200 focus:outline-none focus:ring-2 focus:ring-white/50 focus:bg-white/20 transition-all shadow-inner">
            </div>
        </div>
    </div>

    <!-- FLASH MESSAGE -->
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2" class="p-4 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800/50 text-emerald-700 dark:text-emerald-400 rounded-2xl font-semibold flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-3">
                <div class="p-1.5 bg-emerald-100 dark:bg-emerald-800/50 rounded-lg shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                </div>
                {{ session('success') }}
            </div>
            <button @click="show = false" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-800 transition-colors focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    @endif

    <!-- LIST GROUP & ROLES -->
    <div class="space-y-6 pb-10">
        @forelse($groups as $group)
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md"
             x-data="{
                 groupName: '{{ addslashes(strtolower($group->name)) }}',
                 rolesCount: {{ $group->roles->count() }}
             }"
             x-show="searchQuery === '' || groupName.includes(searchQuery.toLowerCase()) || Array.from($el.querySelectorAll('.role-item')).some(el => el.innerText.toLowerCase().includes(searchQuery.toLowerCase()))">

            <!-- GROUP HEADER -->
            <div class="px-6 py-5 bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <div class="flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <h3 class="text-base sm:text-lg font-black text-slate-800 dark:text-white uppercase tracking-wide">
                        {{ $group->name }}
                    </h3>
                </div>
                <span class="px-3.5 py-1.5 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 font-bold text-xs rounded-lg border border-blue-200 dark:border-blue-800 shadow-sm">
                    {{ $group->roles->count() }} Jabatan Terdaftar
                </span>
            </div>

            <!-- ROLE LIST -->
            <div class="divide-y divide-slate-100 dark:divide-slate-700/50">
                @forelse($group->roles as $role)
                <div class="role-item px-6 py-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5 hover:bg-slate-50/75 dark:hover:bg-slate-800/60 transition-colors group"
                     x-show="searchQuery === '' || '{{ addslashes(strtolower($role->name)) }}'.includes(searchQuery.toLowerCase()) || groupName.includes(searchQuery.toLowerCase())">

                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shadow-sm shrink-0 border border-indigo-100 dark:border-indigo-800/50 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-extrabold text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors text-lg tracking-tight">
                                {{ $role->name }}
                            </p>
                            <div class="flex flex-wrap items-center gap-2 mt-1.5">
                                <span class="px-2 py-0.5 bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-300 text-[10px] font-bold uppercase rounded-md tracking-wider border border-slate-200 dark:border-slate-600 shadow-sm">
                                    {{ $group->name }}
                                </span>
                                <span class="text-slate-300 dark:text-slate-600">&bull;</span>
                                <span class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-600 dark:text-emerald-400">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ $role->permissions->count() }} Permissions
                                </span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <a href="{{ route('admin.role-menus.edit', $role->uuid) }}" class="px-5 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 text-slate-700 dark:text-slate-200 font-bold text-xs rounded-xl shadow-sm hover:bg-blue-600 hover:text-white hover:border-blue-600 dark:hover:bg-blue-600 dark:hover:border-blue-600 transition-all duration-300 flex items-center gap-2 focus:ring-2 focus:ring-blue-500 focus:outline-none active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Atur Privilege
                        </a>
                    </div>
                </div>
                @empty
                <div class="px-6 py-8 text-center text-slate-400 text-sm font-medium italic bg-slate-50/50 dark:bg-slate-900/20">
                    Belum ada role / jabatan yang terdaftar di lembaga ini.
                </div>
                @endforelse
            </div>
        </div>
        @empty
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-16 text-center space-y-4 shadow-sm">
            <div class="w-20 h-20 bg-slate-50 dark:bg-slate-900 rounded-full flex items-center justify-center mx-auto mb-2 border border-slate-100 dark:border-slate-700">
                <svg class="w-10 h-10 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-700 dark:text-slate-300">Data Kosong</h3>
            <p class="text-slate-500 dark:text-slate-400 font-medium text-sm">Belum ada data Group Lembaga atau Role yang tersedia di sistem.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection

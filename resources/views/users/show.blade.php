@extends('layouts.app')

@section('title', 'Detail Akun Pengguna - ' . e($user->name))

@section('content')
<div class="w-full max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6 animate-fade-in">

    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700/80 gap-4 transition-all">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800 dark:text-white tracking-tight">Detail Akun Pengguna</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Informasi lengkap profil, hak akses lembaga, dan jabatan sistem E-Office.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('users.print', $user->id) }}" target="_blank" rel="noopener noreferrer" class="px-4 py-2.5 bg-slate-100 dark:bg-slate-700/50 text-slate-700 dark:text-slate-300 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition text-sm font-semibold flex items-center gap-2 shadow-sm focus:ring-2 focus:ring-slate-400 focus:outline-none">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak ID Card
            </a>
            <a href="{{ route('users.index') }}" class="px-4 py-2.5 bg-slate-100 dark:bg-slate-700/50 text-slate-700 dark:text-slate-300 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition text-sm font-semibold flex items-center gap-2 shadow-sm focus:ring-2 focus:ring-slate-400 focus:outline-none">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700/80 overflow-hidden">

        <div class="h-40 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 relative">
            <div class="absolute inset-0 bg-black/10"></div>

            <div class="absolute -bottom-10 left-8 border-4 border-white dark:border-slate-800 rounded-2xl w-24 h-24 shadow-lg bg-white dark:bg-slate-800 z-10 overflow-hidden group cursor-pointer">
                @if($user->penduduk && $user->penduduk->foto && Storage::disk('private')->exists($user->penduduk->foto))
                    <img src="{{ route('penduduk.photo', $user->penduduk) }}"
                         alt="Foto Profil {{ e($user->name) }}"
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                @else
                    <div class="w-full h-full bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-4xl font-black text-white group-hover:scale-105 transition-transform duration-500">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
            </div>
        </div>

        <div class="pt-14 pb-8 px-6 md:px-8 space-y-8">

            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-100 dark:border-slate-700/60 pb-6">
                <div>
                    <h2 class="text-2xl font-extrabold text-slate-800 dark:text-white tracking-tight">{{ e($user->name) }}</h2>
                    <p class="text-sm font-mono text-slate-500 dark:text-slate-400 mt-0.5">
                        NIK: <span class="font-mono font-medium">{{ e($user->penduduk->nik ?? $user->nik ?? 'Tidak diketahui') }}</span>
                    </p>
                </div>
                <div>
                    @if($user->status_akun === 'Aktif')
                        <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 text-xs font-bold rounded-full shadow-sm">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Akun Aktif
                        </span>
                    @elseif($user->status_akun === 'Nonaktif')
                        <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-bold rounded-full shadow-sm">
                            <span class="w-2 h-2 rounded-full bg-slate-400"></span> Akun Nonaktif
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 text-xs font-bold rounded-full shadow-sm">
                            <span class="w-2 h-2 rounded-full bg-red-500"></span> Akun Suspend
                        </span>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/50 border border-slate-200/60 dark:border-slate-700/50 flex items-start gap-3.5 hover:border-blue-200 dark:hover:border-blue-800/50 transition-colors group">
                    <div class="p-2.5 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Alamat Email</p>
                        <p class="font-semibold text-slate-800 dark:text-white mt-0.5">{{ e($user->email ?? 'Belum disetel') }}</p>
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/50 border border-slate-200/60 dark:border-slate-700/50 flex items-start gap-3.5 hover:border-purple-200 dark:hover:border-purple-800/50 transition-colors group">
                    <div class="p-2.5 rounded-xl bg-purple-50 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Terdaftar Sejak</p>
                        <p class="font-semibold text-slate-800 dark:text-white mt-0.5">{{ $user->created_at?->format('d M Y, H:i') ?? '-' }}</p>
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-blue-50/50 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-900/30 flex items-start gap-3.5">
                    <div class="p-2.5 rounded-xl bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs text-blue-500 dark:text-blue-400 font-bold uppercase tracking-wider">Group Lembaga / Unit</p>
                        <p class="font-bold text-blue-900 dark:text-blue-200 mt-0.5">{{ e($user->group?->name ?? 'Tidak ada Group') }}</p>
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-indigo-50/50 dark:bg-indigo-900/10 border border-indigo-100 dark:border-indigo-900/30 flex items-start gap-3.5">
                    <div class="p-2.5 rounded-xl bg-indigo-100 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs text-indigo-500 dark:text-indigo-400 font-bold uppercase tracking-wider">Role / Jabatan Sistem</p>
                        <p class="font-bold text-indigo-900 dark:text-indigo-200 mt-0.5">{{ e($user->jabatan?->name ?? 'Belum ada Role') }}</p>
                    </div>
                </div>
            </div>

            @if($user->penduduk)
            <div class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-900/50 border border-slate-200/60 dark:border-slate-700/60 space-y-3">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h5m2-13a2 2 0 012-2h5a2 2 0 012 2v9a2 2 0 01-2 2h-5m-2-13v13"></path></svg>
                    Informasi Kependudukan Terkait (Master Penduduk)
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                    <div class="hover:bg-slate-100 dark:hover:bg-slate-800 p-2 rounded-lg transition-colors -mx-2">
                        <span class="text-slate-400 text-xs block font-medium">Jenis Kelamin</span>
                        <span class="font-semibold text-slate-700 dark:text-slate-300">{{ e($user->penduduk->jenis_kelamin ?? '-') }}</span>
                    </div>
                    <div class="hover:bg-slate-100 dark:hover:bg-slate-800 p-2 rounded-lg transition-colors -mx-2">
                        <span class="text-slate-400 text-xs block font-medium">Dusun / Alamat</span>
                        <span class="font-semibold text-slate-700 dark:text-slate-300">{{ e($user->penduduk->dusun ?? '-') }}</span>
                    </div>
                    <div class="hover:bg-slate-100 dark:hover:bg-slate-800 p-2 rounded-lg transition-colors -mx-2">
                        <span class="text-slate-400 text-xs block font-medium">No. Telepon / WhatsApp</span>
                        <span class="font-semibold text-slate-700 dark:text-slate-300">{{ e($user->penduduk->no_hp ?? '-') }}</span>
                    </div>
                </div>
            </div>
            @endif

            <div class="pt-4 border-t border-slate-100 dark:border-slate-700/60 flex flex-col sm:flex-row justify-end gap-3">
                <a href="{{ route('users.index') }}" class="px-6 py-3 bg-slate-100 dark:bg-slate-700/50 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold rounded-xl text-center transition focus:ring-2 focus:ring-slate-400 focus:outline-none">
                    Kembali ke Daftar
                </a>
                <a href="{{ route('users.edit', $user->id) }}" class="px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-xl text-center transition shadow-md shadow-amber-500/20 flex items-center justify-center gap-2 focus:ring-2 focus:ring-amber-500 focus:outline-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    Edit Data Akun
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

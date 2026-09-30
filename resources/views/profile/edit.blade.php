@extends('layouts.app')

@section('title', 'Pengaturan Profil')

@section('content')
<div class="w-full xl:max-w-[96%] mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    <!-- Header Glassmorphism -->
    <div class="relative p-6 sm:p-8 rounded-3xl bg-gradient-to-br from-indigo-600 via-blue-600 to-indigo-800 text-white shadow-xl overflow-hidden flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex items-center gap-5">
            <div class="w-20 h-20 rounded-full bg-white/20 p-1 backdrop-blur-md shadow-inner shrink-0">
                @php
                @endphp
                @if($user->penduduk?->foto)
                    <img src="{{ route('profile.photo') }}" class="w-full h-full rounded-full object-cover shadow-sm" alt="Profile">
                @else
                    <div class="w-full h-full rounded-full flex items-center justify-center text-2xl font-black text-white" aria-label="Inisial {{ $user->name }}">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                @endif
            </div>
            <div>
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight">{{ $user->name }}</h1>
                <p class="text-white/80 font-medium text-sm mt-1 flex items-center gap-2">
                    {{ $user->email }}
                    <span class="text-white/50">&bull;</span>
                    <span class="px-2 py-0.5 bg-white/20 rounded font-bold text-xs uppercase tracking-wider">{{ $user->role->name ?? 'User' }}</span>
                </p>
                @if($user->penduduk)
                    <p class="text-emerald-300 font-bold text-xs mt-2"><i class="fas fa-check-circle"></i> Terhubung dengan Data Kependudukan (ID: {{ $user->penduduk_id }})</p>
                @else
                    <p class="text-amber-300 font-bold text-xs mt-2"><i class="fas fa-exclamation-triangle"></i> Akun Sistem Mandiri (Tidak Terhubung ke Penduduk)</p>
                @endif
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl font-bold flex items-center gap-3 shadow-sm animate-fade-in">
            <i class="fas fa-check-circle text-xl shrink-0"></i> {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informasi Dasar -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 p-6 sm:p-8 shadow-sm">
            <h2 class="text-lg font-black text-slate-800 dark:text-white mb-1 flex items-center gap-2">
                <i class="fas fa-user-edit text-blue-500"></i> Informasi Personal
            </h2>
            <p class="text-xs text-slate-500 mb-5">Perubahan nama & foto di sini akan otomatis tersinkronisasi dengan Data Kependudukan.</p>

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 transition">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Pas Foto Penduduk (Opsional)</label>
                    <input type="file" name="foto" accept="image/jpeg,image/png,image/webp" class="w-full px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition cursor-pointer">
                    <p class="text-[10px] text-slate-400 mt-1.5">Format didukung: JPG, PNG, WEBP. Maksimal ukuran: 2MB.</p>
                </div>
                <div class="pt-4 flex justify-end">
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold shadow-md shadow-blue-500/30 active:scale-95 transition flex items-center gap-2">
                        <i class="fas fa-sync-alt"></i> Simpan & Sinkronisasi
                    </button>
                </div>
            </form>
        </div>

        <!-- Keamanan & Password -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 p-6 sm:p-8 shadow-sm">
            <h2 class="text-lg font-black text-slate-800 dark:text-white mb-5 flex items-center gap-2">
                <i class="fas fa-lock text-emerald-500"></i> Ubah Kata Sandi
            </h2>
            <form action="{{ route('profile.update-password') }}" method="POST" class="space-y-4">
                @csrf
                @method('PATCH')

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Sandi Saat Ini</label>
                    <input type="password" name="current_password" required class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Sandi Baru</label>
                    <input type="password" name="password" required class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Konfirmasi Sandi Baru</label>
                    <input type="password" name="password_confirmation" required class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 transition">
                </div>
                <div class="pt-2">
                    <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold shadow-md shadow-emerald-500/30 active:scale-95 transition flex items-center justify-center gap-2">
                        <i class="fas fa-key"></i> Perbarui Kata Sandi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Profil Pemerintahan Desa - Desa Ridogalih')

@section('content')
<div class="w-full xl:max-w-[96%] mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8 animate-fade-in">

    <!-- HEADER AKSI -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white dark:bg-slate-800 p-6 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold shadow-inner">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-slate-800 dark:text-white tracking-tight">Profil Pemerintahan Desa</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Informasi resmi identitas wilayah dan aparatur Desa Ridogalih.</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            @can('security.settings.update')
            <a href="{{ route('admin.profil-desa.print') }}" target="_blank" class="px-5 py-2.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition shadow-md shadow-blue-500/20 flex items-center gap-2 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak Poster
            </a>
            @endcan
        </div>
    </div>

    <!-- KONTROL UTAMA -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- KARTU LOGO & KADES -->
        <div class="bg-white dark:bg-slate-800 p-6 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm text-center space-y-6 flex flex-col items-center justify-center">
            <div class="relative group">
                @if($profil->logo_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($profil->logo_path))
                    <img src="{{ asset('storage/' . $profil->logo_path) }}" alt="Logo Desa" class="w-36 h-36 object-contain mx-auto rounded-2xl p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-md">
                @else
                    <div class="w-36 h-36 rounded-2xl bg-blue-600 text-white font-black text-4xl flex items-center justify-center mx-auto shadow-md">
                        {{ strtoupper(substr($profil->nama_desa, 0, 2)) }}
                    </div>
                @endif
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-800 dark:text-white">{{ $profil->nama_desa }}</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Kec. {{ $profil->kecamatan }}, Kab. {{ $profil->kabupaten }}</p>
            </div>
            <div class="w-full pt-4 border-t border-slate-100 dark:border-slate-700 text-left space-y-2">
                <div class="flex justify-between text-xs"><span class="text-slate-400">Kepala Desa:</span><span class="font-bold text-slate-700 dark:text-slate-300">{{ $profil->nama_kepala_desa }}</span></div>
                <div class="flex justify-between text-xs"><span class="text-slate-400">NIP Kades:</span><span class="font-mono text-slate-700 dark:text-slate-300">{{ $profil->nip_kepala_desa ?? '-' }}</span></div>
            </div>
        </div>

        <!-- DETAIL INFORMASI -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-800 p-8 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm space-y-6">
            <h3 class="text-lg font-extrabold text-slate-800 dark:text-white border-b border-slate-100 dark:border-slate-700 pb-4">Detail Wilayah & Kontak Pemerintahan</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-1">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Kecamatan</span>
                    <p class="text-base font-semibold text-slate-800 dark:text-white">{{ $profil->kecamatan }}</p>
                </div>
                <div class="space-y-1">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Kabupaten</span>
                    <p class="text-base font-semibold text-slate-800 dark:text-white">{{ $profil->kabupaten }}</p>
                </div>
                <div class="space-y-1">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Nomor Telepon</span>
                    <p class="text-base font-semibold text-slate-800 dark:text-white">{{ $profil->telepon ?? 'Belum diatur' }}</p>
                </div>
                <div class="space-y-1">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Email Resmi</span>
                    <p class="text-base font-semibold text-slate-800 dark:text-white">{{ $profil->email ?? 'Belum diatur' }}</p>
                </div>
                <div class="space-y-1">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Kode Pos</span>
                    <p class="text-base font-semibold text-slate-800 dark:text-white">{{ $profil->kode_pos ?? '-' }}</p>
                </div>
                <div class="space-y-1 md:col-span-2">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Alamat Kantor Desa</span>
                    <p class="text-base font-semibold text-slate-800 dark:text-white leading-relaxed">{{ $profil->alamat }}</p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

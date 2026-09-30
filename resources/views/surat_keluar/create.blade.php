@extends('layouts.app')

@section('title', 'Buat Pengajuan Surat')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6 animate-fade-in">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm transition-all">
        <div class="flex items-start gap-4">
            <div class="p-3 bg-blue-100 dark:bg-blue-900/40 rounded-xl text-blue-600 dark:text-blue-400 shrink-0">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            </div>
            <div>
                <h2 class="text-2xl font-extrabold text-slate-800 dark:text-white tracking-tight">Buat Pengajuan Surat</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">Isi formulir di bawah ini dengan lengkap untuk mengajukan pembuatan administrasi surat desa.</p>
            </div>
        </div>
        <a href="{{ route('surat-keluar.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-600 dark:text-slate-300 font-semibold rounded-xl transition text-sm flex items-center gap-2 shrink-0 focus:ring-2 focus:ring-slate-400 focus:outline-none">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
    </div>

    @if ($errors->any())
    <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-2xl p-5 shadow-sm animate-fade-in">
        <div class="flex items-center gap-2 mb-2 text-red-700 dark:text-red-400 font-bold text-sm">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Terdapat kesalahan pada input formulir:
        </div>
        <ul class="text-xs text-red-600 dark:text-red-400 list-disc list-inside ml-5 space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 md:p-8">
        <form action="{{ route('surat-keluar.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="group">
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Pilih Jenis Surat <span class="text-red-500">*</span>
                </label>
                <select name="jenis_surat_id" required class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-xl text-sm text-slate-800 dark:text-white focus:ring-2 focus:ring-blue-500 focus:outline-none transition cursor-pointer">
                    <option value="">-- Pilih Format Surat --</option>
                    @foreach($jenis_surats as $js)
                        <option value="{{ $js->id }}" {{ old('jenis_surat_id') == $js->id ? 'selected' : '' }}>
                            {{ $js->kode_surat }} - {{ $js->nama_surat }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="group">
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Pilih Penduduk (Pemohon) <span class="text-red-500">*</span>
                </label>
                <select name="penduduk_id" required class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-xl text-sm text-slate-800 dark:text-white focus:ring-2 focus:ring-blue-500 focus:outline-none transition cursor-pointer">
                    <option value="">-- Pilih Warga Desa --</option>
                    @foreach($penduduks as $p)
                        <option value="{{ $p->id }}" {{ old('penduduk_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->nik }} - {{ $p->nama_lengkap }} (Dusun {{ $p->dusun ?? '-' }})
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-slate-400 mt-2 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 text-blue-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Sistem terintegrasi otomatis dengan Master Data Kependudukan NIK.
                </p>
            </div>

            <div class="group">
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                    Keperluan <span class="text-red-500">*</span>
                </label>
                <textarea name="keperluan" rows="3" required placeholder="Contoh: Untuk persyaratan pembuatan SKCK ke Polsek Cibarusah..." class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-xl text-sm text-slate-800 dark:text-white focus:ring-2 focus:ring-blue-500 focus:outline-none transition">{{ old('keperluan') }}</textarea>
            </div>

            <div class="flex justify-end pt-6 border-t border-slate-200 dark:border-slate-700">
                <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-extrabold rounded-xl shadow-md shadow-blue-500/25 transition flex items-center justify-center gap-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    Ajukan Surat Sekarang
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

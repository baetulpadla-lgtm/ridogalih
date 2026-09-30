@extends('layouts.app')

@section('title', 'Edit Jenis Surat')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6 animate-fade-in">

    <!-- HEADER SECTION -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white dark:bg-slate-800 p-6 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm transition-all">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-800 dark:text-white tracking-tight">Edit Jenis Surat</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">Perbarui informasi dan status template surat resmi desa.</p>
        </div>
        <a href="{{ route('jenis-surat.index') }}" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 text-sm font-bold transition-all shadow-sm active:scale-95 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
    </div>

    <!-- FORM CARD -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 sm:p-8">
        <!-- [CRITICAL FIXED]: Menggunakan $jenisSurat->uuid, selaras dengan Controller -->
        <form action="{{ route('jenis-surat.update', $jenisSurat->uuid) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- KODE SURAT -->
                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-2">Kode Surat <span class="text-red-500">*</span></label>
                    <input type="text" name="kode_surat" value="{{ old('kode_surat', $jenisSurat->kode_surat) }}" required
                           class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-xl text-sm font-medium text-slate-800 dark:text-white focus:ring-2 focus:ring-blue-500 focus:outline-none transition @error('kode_surat') border-red-500 ring-1 ring-red-500 @enderror"
                           placeholder="Contoh: SKD, SKTM">
                    @error('kode_surat')
                        <p class="text-red-500 text-xs font-semibold mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <!-- NAMA SURAT -->
                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-2">Nama Surat <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_surat" value="{{ old('nama_surat', $jenisSurat->nama_surat) }}" required
                           class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-xl text-sm font-medium text-slate-800 dark:text-white focus:ring-2 focus:ring-blue-500 focus:outline-none transition @error('nama_surat') border-red-500 ring-1 ring-red-500 @enderror"
                           placeholder="Contoh: Surat Keterangan Domisili">
                    @error('nama_surat')
                        <p class="text-red-500 text-xs font-semibold mt-1.5">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- DESKRIPSI -->
            <div class="mb-6">
                <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-2">Deskripsi / Keterangan Singkat</label>
                <textarea name="deskripsi" rows="3"
                          class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-xl text-sm font-medium text-slate-800 dark:text-white focus:ring-2 focus:ring-blue-500 focus:outline-none transition"
                          placeholder="Tuliskan keterangan fungsi surat ini...">{{ old('deskripsi', $jenisSurat->deskripsi) }}</textarea>
                @error('deskripsi')
                    <p class="text-red-500 text-xs font-semibold mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- STATUS TEMPLATE -->
            <div class="mb-8">
                <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-2">Status Template <span class="text-red-500">*</span></label>
                <select name="is_active" required class="w-full md:w-1/2 px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-xl text-sm font-medium text-slate-800 dark:text-white focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
                    <option value="1" {{ old('is_active', $jenisSurat->is_active) == '1' ? 'selected' : '' }}>Aktif (Bisa digunakan)</option>
                    <option value="0" {{ old('is_active', $jenisSurat->is_active) == '0' ? 'selected' : '' }}>Nonaktif (Disembunyikan)</option>
                </select>
            </div>

            <!-- SUBMIT BUTTON -->
            <div class="flex justify-end pt-5 border-t border-slate-100 dark:border-slate-700">
                <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-sm shadow-md shadow-blue-500/20 transition-all active:scale-95 focus:ring-2 focus:ring-blue-500 focus:outline-none flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Perbarui Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

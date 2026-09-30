@extends('layouts.app')

@section('title', 'Edit Arsip Surat Masuk')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6 animate-fade-in">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm transition-all">
        <div class="flex items-start gap-4">
            <div class="p-3 bg-amber-100 dark:bg-amber-900/40 rounded-xl text-amber-600 dark:text-amber-400 shrink-0">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-slate-800 dark:text-white tracking-tight">Edit Arsip Surat Masuk</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">Perbarui informasi, ubah catatan disposisi, atau ganti file scan dokumen arsip surat masuk.</p>
            </div>
        </div>
        <a href="{{ route('surat-masuk.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-600 dark:text-slate-300 font-semibold rounded-xl transition text-sm flex items-center gap-2 shrink-0 focus:ring-2 focus:ring-slate-400 focus:outline-none">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar
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

    <div class="bg-white dark:bg-slate-800 shadow-sm rounded-2xl border border-slate-200 dark:border-slate-700 p-6 md:p-8">
        <form action="{{ route('surat-masuk.update', $suratMasuk->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="group">
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        Nomor Surat <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nomor_surat" value="{{ old('nomor_surat', $suratMasuk->nomor_surat) }}" required placeholder="Contoh: 005/123/Des.Rdg" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-xl text-sm text-slate-800 dark:text-white focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
                </div>

                <div class="group">
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        Asal Surat / Instansi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="asal_surat" value="{{ old('asal_surat', $suratMasuk->asal_surat) }}" required placeholder="Contoh: Kecamatan Cibarusah" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-xl text-sm text-slate-800 dark:text-white focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
                </div>
            </div>

            <div class="group">
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                    Perihal Surat <span class="text-red-500">*</span>
                </label>
                <textarea name="perihal" rows="3" required placeholder="Tuliskan perihal atau maksud surat masuk secara ringkas..." class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-xl text-sm text-slate-800 dark:text-white focus:ring-2 focus:ring-blue-500 focus:outline-none transition">{{ old('perihal', $suratMasuk->perihal) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="group">
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Tanggal Tertera pada Surat <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal_surat" value="{{ old('tanggal_surat', $suratMasuk->tanggal_surat) }}" required class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-xl text-sm text-slate-800 dark:text-white focus:ring-2 focus:ring-blue-500 focus:outline-none transition cursor-pointer">
                </div>
                <div class="group">
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        Tanggal Diterima di Desa <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal_diterima" value="{{ old('tanggal_diterima', $suratMasuk->tanggal_diterima) }}" required class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-xl text-sm text-slate-800 dark:text-white focus:ring-2 focus:ring-blue-500 focus:outline-none transition cursor-pointer">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="group">
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Disposisi Kepada
                    </label>
                    <input type="text" name="disposisi_kepada" value="{{ old('disposisi_kepada', $suratMasuk->disposisi_kepada) }}" placeholder="Contoh: Sekretaris Desa / Kasi Pemerintahan" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-xl text-sm text-slate-800 dark:text-white focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
                </div>
                <div class="group">
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                        Ganti File Scan (Opsional)
                    </label>
                    <input type="file" name="file_scan" accept=".pdf,.jpg,.jpeg,.png" class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 dark:file:bg-blue-900/40 dark:file:text-blue-300 border border-slate-300 dark:border-slate-600 rounded-xl cursor-pointer bg-slate-50 dark:bg-slate-900">
                    @if($suratMasuk->file_scan)
                        <div class="mt-2 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span class="text-xs text-emerald-600 dark:text-emerald-400 font-medium">File aktif saat ini: <strong>{{ basename($suratMasuk->file_scan) }}</strong></span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="group">
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Keterangan Tambahan (Opsional)
                </label>
                <textarea name="keterangan" rows="2" placeholder="Catatan tambahan mengenai surat masuk..." class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-xl text-sm text-slate-800 dark:text-white focus:ring-2 focus:ring-blue-500 focus:outline-none transition">{{ old('keterangan', $suratMasuk->keterangan) }}</textarea>
            </div>

            <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t border-slate-200 dark:border-slate-700">
                <a href="{{ route('surat-masuk.index') }}" class="px-6 py-3 bg-slate-100 dark:bg-slate-700/50 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold text-center rounded-xl transition focus:ring-2 focus:ring-slate-400 focus:outline-none">Batal</a>
                <button type="submit" class="px-8 py-3 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-xl shadow-md shadow-amber-500/20 transition flex items-center justify-center gap-2 focus:ring-2 focus:ring-amber-500 focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Perbarui Arsip Surat
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

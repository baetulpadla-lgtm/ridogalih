@extends('layouts.app')

@section('title', 'Detail & Proses Pengajuan Surat - Desa Ridogalih')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6 animate-fade-in">

    <!-- HEADER SECTION -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm transition-all">
        <div class="flex items-start gap-4">
            <div class="p-3 bg-indigo-100 dark:bg-indigo-900/40 rounded-xl text-indigo-600 dark:text-indigo-400 shrink-0">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <div>
                <h2 class="text-2xl font-extrabold text-slate-800 dark:text-white tracking-tight">Detail & Proses Pengajuan Surat</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">Review data pemohon secara cermat dan berikan status persetujuan atau disposisi surat keluar.</p>
            </div>
        </div>
        <a href="{{ route('surat-keluar.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-600 dark:text-slate-300 font-semibold rounded-xl transition text-sm flex items-center gap-2 shrink-0 focus:ring-2 focus:ring-slate-400 focus:outline-none">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar
        </a>
    </div>

    <!-- ALERT SUCCESS -->
    @if (session('success'))
    <div class="p-4 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 rounded-2xl font-medium flex items-center gap-3 shadow-sm animate-fade-in">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        {{ session('success') }}
    </div>
    @endif

    <!-- ALERT ERROR VALIDASI -->
    @if ($errors->any())
    <div class="p-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 rounded-2xl font-medium shadow-sm animate-fade-in">
        <div class="flex items-center gap-3 mb-1">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <span>Mohon periksa kembali form pengisian Anda:</span>
        </div>
        <ul class="list-disc list-inside text-xs space-y-1 pl-8">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- KOLOM INFORMASI (KIRI) -->
        <div class="lg:col-span-2 space-y-6">

            <!-- INFORMASI SURAT -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 md:p-8 space-y-4">
                <h3 class="text-base font-extrabold text-slate-800 dark:text-white border-b border-slate-100 dark:border-slate-700 pb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Informasi Pengajuan Surat
                </h3>

                <table class="w-full text-sm text-slate-700 dark:text-slate-300">
                    <tr class="border-b border-slate-100 dark:border-slate-700/60">
                        <td class="py-3 font-semibold w-1/3 text-slate-400 uppercase text-xs">Jenis Surat</td>
                        <td class="py-3 font-bold text-slate-900 dark:text-white">: <span class="text-indigo-600 dark:text-indigo-400">{{ $suratKeluar->jenis_surat->kode_surat }}</span> - {{ $suratKeluar->jenis_surat->nama_surat }}</td>
                    </tr>
                    <tr class="border-b border-slate-100 dark:border-slate-700/60">
                        <td class="py-3 font-semibold text-slate-400 uppercase text-xs">Nomor Surat Resmi</td>
                        <td class="py-3 font-mono font-bold text-slate-900 dark:text-white">: {{ $suratKeluar->nomor_surat ?? 'Belum Digenerate (Menunggu Disetujui)' }}</td>
                    </tr>
                    <tr class="border-b border-slate-100 dark:border-slate-700/60">
                        <td class="py-3 font-semibold text-slate-400 uppercase text-xs">Tanggal Pengajuan</td>
                        <td class="py-3 font-medium">: {{ $suratKeluar->created_at->format('d F Y, H:i') }} WIB</td>
                    </tr>
                    <tr class="border-b border-slate-100 dark:border-slate-700/60">
                        <td class="py-3 font-semibold text-slate-400 uppercase text-xs">Diajukan Oleh (Akun)</td>
                        <td class="py-3 font-medium">: {{ $suratKeluar->user->name ?? 'Sistem Desa' }}</td>
                    </tr>
                    <tr>
                        <td class="py-3 font-semibold text-slate-400 uppercase text-xs align-top">Keperluan</td>
                        <td class="py-3 font-medium leading-relaxed">: <span class="bg-slate-50 dark:bg-slate-900/50 p-3 rounded-xl block mt-1 border border-slate-200 dark:border-slate-700">{{ $suratKeluar->keperluan }}</span></td>
                    </tr>
                </table>
            </div>

            <!-- INFORMASI PEMOHON -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 md:p-8 space-y-4">
                <h3 class="text-base font-extrabold text-slate-800 dark:text-white border-b border-slate-100 dark:border-slate-700 pb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Data Pemohon (Penduduk)
                </h3>

                <table class="w-full text-sm text-slate-700 dark:text-slate-300">
                    <tr class="border-b border-slate-100 dark:border-slate-700/60">
                        <td class="py-3 font-semibold w-1/3 text-slate-400 uppercase text-xs">NIK Penduduk</td>
                        <td class="py-3 font-bold text-slate-900 dark:text-white font-mono">: <span class="text-blue-600 dark:text-blue-400">{{ $suratKeluar->penduduk->nik ?? '-' }}</span></td>
                    </tr>
                    <tr class="border-b border-slate-100 dark:border-slate-700/60">
                        <td class="py-3 font-semibold text-slate-400 uppercase text-xs">Nama Lengkap</td>
                        <td class="py-3 font-bold text-slate-900 dark:text-white">: {{ $suratKeluar->penduduk->nama_lengkap ?? 'Data Kependudukan Tidak Ditemukan' }}</td>
                    </tr>
                    <tr>
                        <td class="py-3 font-semibold text-slate-400 uppercase text-xs">Tempat, Tgl Lahir</td>
                        <td class="py-3 font-medium">: {{ $suratKeluar->penduduk->tempat_lahir ?? '-' }}, {{ optional($suratKeluar->penduduk)->tanggal_lahir ? \Carbon\Carbon::parse($suratKeluar->penduduk->tanggal_lahir)->format('d-m-Y') : '-' }}</td>
                    </tr>
                </table>
            </div>

        </div>

        <!-- KOLOM TINDAKAN & DISPOSISI (KANAN) -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 md:p-8 flex flex-col justify-between">
            <div>
                <h3 class="text-base font-extrabold text-slate-800 dark:text-white border-b border-slate-100 dark:border-slate-700 pb-3 mb-5 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    Tindakan & Disposisi
                </h3>

                <!-- [UUID FIXED]: Menggunakan $suratKeluar->uuid sebagai parameter rute -->
                <form action="{{ route('surat-keluar.update', $suratKeluar->uuid) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1.5">Status Pengajuan</label>
                        <select name="status" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-xl text-sm font-bold transition cursor-pointer
                            {{ $suratKeluar->status == 'Menunggu' ? 'text-yellow-600 dark:text-yellow-400' : '' }}
                            {{ $suratKeluar->status == 'Disetujui' || $suratKeluar->status == 'Selesai' ? 'text-emerald-600 dark:text-emerald-400' : '' }}
                            {{ $suratKeluar->status == 'Ditolak' ? 'text-red-600 dark:text-red-400' : '' }}">
                            <option value="Menunggu" {{ $suratKeluar->status == 'Menunggu' ? 'selected' : '' }}>⏳ Menunggu</option>
                            <option value="Diproses" {{ $suratKeluar->status == 'Diproses' ? 'selected' : '' }}>🔄 Sedang Diproses</option>
                            <option value="Disetujui" {{ $suratKeluar->status == 'Disetujui' ? 'selected' : '' }}>✅ Disetujui</option>
                            <option value="Selesai" {{ $suratKeluar->status == 'Selesai' ? 'selected' : '' }}>🎉 Selesai & Dicetak</option>
                            <option value="Ditolak" {{ $suratKeluar->status == 'Ditolak' ? 'selected' : '' }}>❌ Ditolak</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1.5">Nomor Surat Resmi</label>
                        <input type="text" name="nomor_surat" value="{{ old('nomor_surat', $suratKeluar->nomor_surat) }}" placeholder="Cth: 001 / SKTM / IV / 2026" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-xl text-sm font-mono text-slate-800 dark:text-white focus:ring-2 focus:ring-blue-500 transition">
                        <p class="text-[11px] text-slate-400 mt-1">Biarkan kosong jika sistem merangkai nomor surat otomatis.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1.5">Keterangan / Catatan Penolakan</label>
                        <textarea name="keterangan_status" rows="3" placeholder="Opsional. Isi jika ada revisi berkas atau alasan penolakan..." class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-xl text-sm text-slate-800 dark:text-white focus:ring-2 focus:ring-blue-500 transition">{{ old('keterangan_status', $suratKeluar->keterangan_status) }}</textarea>
                    </div>

                    <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-extrabold rounded-xl shadow-md shadow-blue-500/25 transition text-sm flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Simpan Perubahan
                    </button>
                </form>
            </div>

            <!-- TOMBOL CETAK PDF (MUNCUL JIKA DISETUJUI/SELESAI) -->
            @if(in_array($suratKeluar->status, ['Disetujui', 'Selesai']))
                <div class="mt-6 pt-5 border-t border-slate-200 dark:border-slate-700 space-y-3">
                    <!-- [UUID FIXED]: Menggunakan $suratKeluar->uuid -->
                    <a href="{{ route('surat-keluar.print', $suratKeluar->uuid) }}" target="_blank" class="flex items-center justify-center w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold rounded-xl shadow-md shadow-emerald-500/25 transition text-sm gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        Cetak Surat (PDF)
                    </a>
                </div>
            @endif

        </div>

    </div>
</div>
@endsection

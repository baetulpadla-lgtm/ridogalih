@extends('layouts.app')

@section('title', 'Detail Biodata Penduduk - ' . ($penduduk->nama_lengkap ?? ''))

@section('content')
<div class="w-full max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 animate-fade-in">

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white/80 dark:bg-slate-800/80 backdrop-blur-md p-6 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700">
        <div class="flex items-start sm:items-center gap-4">
            <div class="p-3.5 bg-blue-600 text-white rounded-2xl flex-shrink-0 shadow-lg shadow-blue-500/30">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
            </div>
            <div>
                <h2 class="text-xl sm:text-2xl font-extrabold text-slate-800 dark:text-white tracking-tight">Detail Biodata Penduduk</h2>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">Informasi resmi kependudukan terdaftar dalam sistem database Desa {{ $profilDesa->nama_desa ?? config('app.desa_default', 'Ridogalih') }}.</p>
            </div>
        </div>
        <div class="flex items-center gap-3 flex-wrap">
            <a href="{{ route('penduduk.print', $penduduk->uuid) }}" target="_blank" class="px-5 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-sm font-bold flex items-center shadow-lg shadow-purple-500/30 transition-all transform hover:-translate-y-0.5">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak Biodata
            </a>
            <a href="{{ route('penduduk.index') }}" class="px-5 py-2.5 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-all text-sm font-bold flex items-center shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 flex flex-col justify-between">
            <div class="flex items-start gap-5">
                <div class="w-24 h-32 flex-shrink-0 rounded-2xl border-2 border-slate-200 dark:border-slate-700 overflow-hidden bg-slate-50 dark:bg-slate-900 flex items-center justify-center shadow-inner">
                    @if(!empty($penduduk->foto))
                        <img src="{{ route('penduduk.photo', $penduduk) }}" alt="Foto {{ $penduduk->nama_lengkap }}" class="w-full h-full object-cover">
                    @else
                        <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    @endif
                </div>
                <div>
                    <span class="text-xs font-black uppercase tracking-widest text-slate-400 block mb-1">Nama Lengkap</span>
                    <h3 class="text-xl font-extrabold text-slate-800 dark:text-white leading-tight">{{ $penduduk->nama_lengkap }}</h3>
                    <span class="inline-block mt-2 px-3 py-1 bg-slate-100 dark:bg-slate-700/50 text-slate-600 dark:text-slate-300 text-xs font-mono font-bold rounded-lg">ID: #{{ $penduduk->id }}</span>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-700/60 flex justify-between items-center">
                <span class="text-xs font-bold text-slate-500 uppercase">Status Kependudukan</span>
                @php
                    $statusVal = $penduduk->status_kependudukan?->value ?? \App\Enums\StatusKependudukan::AKTIF->value;

                    $statusBadge = match($statusVal) {
                        \App\Enums\StatusKependudukan::AKTIF->value => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/50 dark:text-emerald-300 border-emerald-300',
                        \App\Enums\StatusKependudukan::MENINGGAL->value => 'bg-slate-200 text-slate-900 dark:bg-slate-900 dark:text-slate-100 border-slate-400',
                        \App\Enums\StatusKependudukan::PINDAH->value => 'bg-amber-100 text-amber-800 dark:bg-amber-950/50 dark:text-amber-300 border-amber-300',
                        \App\Enums\StatusKependudukan::HILANG->value => 'bg-red-100 text-red-800 dark:bg-red-950/50 dark:text-red-300 border-red-300',
                        default => 'bg-slate-100 text-slate-700 border-slate-300'
                    };
                    $dotColor = match($statusVal) {
                        \App\Enums\StatusKependudukan::AKTIF->value => 'bg-emerald-500 animate-pulse',
                        \App\Enums\StatusKependudukan::MENINGGAL->value => 'bg-slate-800 dark:bg-slate-300',
                        \App\Enums\StatusKependudukan::PINDAH->value => 'bg-amber-500',
                        \App\Enums\StatusKependudukan::HILANG->value => 'bg-red-500 animate-pulse',
                        default => 'bg-slate-400'
                    };
                @endphp
                <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-black uppercase tracking-wider rounded-full border shadow-sm {{ $statusBadge }}">
                    <span class="w-2 h-2 rounded-full {{ $dotColor }}"></span>
                    {{ $statusVal }}
                </span>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 flex flex-col justify-center">
            <span class="text-xs font-black uppercase tracking-widest text-slate-400 block">Nomor Induk Kependudukan (NIK)</span>
            <div class="text-2xl font-mono font-black text-blue-600 dark:text-blue-400 mt-1 tracking-wider">{{ $penduduk->nik }}</div>

            <div class="w-full h-px bg-slate-100 dark:bg-slate-700/60 my-5"></div>

            <span class="text-xs font-black uppercase tracking-widest text-slate-400 block">Nomor Kartu Keluarga (KK)</span>
            <div class="text-lg font-mono font-bold text-slate-700 dark:text-slate-200 mt-1 tracking-wider">{{ $penduduk->no_kk }}</div>
        </div>

        <div class="bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 flex flex-col justify-between">
            <div>
                <span class="text-xs font-black uppercase tracking-widest text-slate-400 block">Tempat, Tanggal Lahir</span>
                <div class="text-base font-bold text-slate-800 dark:text-white mt-1">
                    {{ $penduduk->tempat_lahir }}, {{ $penduduk->tanggal_lahir ? \Carbon\Carbon::parse($penduduk->tanggal_lahir)->translatedFormat('d F Y') : '-' }}
                </div>
            </div>

            <div class="flex items-center justify-between mt-4 pt-4 border-t border-slate-100 dark:border-slate-700/60">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block">Validasi QR TTE</span>
                    <span class="text-xs font-bold text-slate-500">Sistem Resmi Desa</span>
                </div>
                <div class="bg-white p-2 rounded-xl border border-slate-200 shadow-sm">
                    @php
                        // Data statis untuk operasional QR Code aman di dalam block PHP
                        $namaDesaQr = $profilDesa->nama_desa ?? config('app.desa_default', 'Ridogalih');
                        $qrData = 'TTE PEMDES ' . strtoupper($namaDesaQr) . ' - NIK: ' . $penduduk->nik . ' - Nama: ' . $penduduk->nama_lengkap;
                    @endphp
                    {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(55)->margin(0)->generate($qrData) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50">
            <h3 class="font-extrabold text-slate-800 dark:text-white flex items-center text-base">
                <div class="w-2.5 h-5 bg-emerald-500 rounded-full mr-3 shadow-sm shadow-emerald-500/50"></div> Rincian Data Sosial & Keluarga
            </h3>
        </div>
        <div class="p-6 sm:p-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 text-sm">
            <div class="space-y-1">
                <span class="text-slate-400 block text-xs font-black uppercase tracking-wider">Pendidikan Terakhir</span>
                <span class="font-extrabold text-slate-800 dark:text-white text-base">{{ $penduduk->pendidikan?->value ?? $penduduk->pendidikan ?? '-' }}</span>
            </div>
            <div class="space-y-1">
                <span class="text-slate-400 block text-xs font-black uppercase tracking-wider">Jenis Pekerjaan</span>
                <span class="font-extrabold text-slate-800 dark:text-white text-base">{{ $penduduk->pekerjaan?->value ?? $penduduk->pekerjaan ?? '-' }}</span>
            </div>
            <div class="space-y-1">
                <span class="text-slate-400 block text-xs font-black uppercase tracking-wider">Status Perkawinan</span>
                <span class="font-extrabold text-slate-800 dark:text-white text-base">{{ $penduduk->status_perkawinan?->value ?? $penduduk->status_perkawinan ?? '-' }}</span>
            </div>
            <div class="space-y-1">
                <span class="text-slate-400 block text-xs font-black uppercase tracking-wider">Hubungan Keluarga (SHDK)</span>
                <span class="font-extrabold text-slate-800 dark:text-white text-base">{{ $penduduk->status_hubungan_keluarga?->value ?? $penduduk->status_hubungan_keluarga ?? '-' }}</span>
            </div>
            <div class="space-y-1">
                <span class="text-slate-400 block text-xs font-black uppercase tracking-wider">Golongan Darah / Kewarganegaraan</span>
                <span class="font-extrabold text-slate-800 dark:text-white text-base">{{ $penduduk->golongan_darah?->value ?? $penduduk->golongan_darah ?? 'Tidak Tahu' }} &bull; {{ $penduduk->kewarganegaraan ?? 'WNI' }}</span>
            </div>
            <div class="space-y-1">
                <span class="text-slate-400 block text-xs font-black uppercase tracking-wider">Nama Orang Tua (Ayah / Ibu)</span>
                <span class="font-extrabold text-slate-800 dark:text-white text-base">{{ $penduduk->nama_ayah ?? '-' }} / {{ $penduduk->nama_ibu ?? '-' }}</span>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50">
            <h3 class="font-extrabold text-slate-800 dark:text-white flex items-center text-base">
                <div class="w-2.5 h-5 bg-purple-600 rounded-full mr-3 shadow-sm shadow-purple-500/50"></div> Informasi Domisili Wilayah
            </h3>
        </div>
        <div class="p-6 sm:p-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 text-sm">
            <div class="lg:col-span-2 space-y-1">
                <span class="text-slate-400 block text-xs font-black uppercase tracking-wider">Alamat Lengkap / Jalan / Kampung</span>
                <span class="font-extrabold text-slate-800 dark:text-white text-base">{{ $penduduk->alamat_lengkap ?? '-' }}</span>
            </div>
            <div class="space-y-1">
                <span class="text-slate-400 block text-xs font-black uppercase tracking-wider">RT / RW / Dusun</span>
                <span class="font-extrabold text-slate-800 dark:text-white text-base">RT {{ str_pad($penduduk->rt?->value ?? $penduduk->rt ?? 0, 3, '0', STR_PAD_LEFT) }} / RW {{ str_pad($penduduk->rw?->value ?? $penduduk->rw ?? 0, 3, '0', STR_PAD_LEFT) }} &bull; Dusun {{ $penduduk->dusun?->value ?? $penduduk->dusun ?? '-' }}</span>
            </div>
            <div class="space-y-1">
                <span class="text-slate-400 block text-xs font-black uppercase tracking-wider">Desa / Kecamatan</span>
                <span class="font-extrabold text-slate-800 dark:text-white text-base">Desa {{ $penduduk->desa ?? $profilDesa->nama_desa ?? config('app.desa_default', 'Ridogalih') }}, Kec. {{ $penduduk->kecamatan ?? $profilDesa->kecamatan ?? config('app.kecamatan_default', 'Cibarusah') }}</span>
            </div>
            <div class="space-y-1">
                <span class="text-slate-400 block text-xs font-black uppercase tracking-wider">Kabupaten / Provinsi</span>
                <span class="font-extrabold text-slate-800 dark:text-white text-base">{{ $penduduk->kabupaten ?? $profilDesa->kabupaten ?? config('app.kabupaten_default', 'Bekasi') }}, {{ $penduduk->provinsi ?? $profilDesa->provinsi ?? config('app.provinsi_default', 'Jawa Barat') }}</span>
            </div>
            <div class="space-y-1">
                <span class="text-slate-400 block text-xs font-black uppercase tracking-wider">Kode Pos</span>
                <span class="font-extrabold text-slate-800 dark:text-white text-base font-mono tracking-widest">{{ $penduduk->kode_pos ?? '-' }}</span>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-r from-slate-900 to-slate-800 text-white rounded-3xl shadow-lg p-6 sm:p-8 flex flex-col md:flex-row items-center justify-between gap-6 border border-slate-700">
        <div class="flex items-center gap-4">
            <div class="p-3.5 bg-blue-500/20 text-blue-400 rounded-2xl border border-blue-500/30">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <h4 class="font-extrabold text-base tracking-wide">Jejak Histori & Audit Sistem Kependudukan</h4>
                <p class="text-xs text-slate-400 mt-0.5">Catatan waktu persis kapan data warga dimasukkan dan terakhir kali diperbarui ke dalam database.</p>
            </div>
        </div>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 w-full md:w-auto">
            <div class="bg-slate-800/80 backdrop-blur-md px-4 py-3 rounded-2xl border border-slate-700/80">
                <span class="block text-[10px] font-black uppercase tracking-widest text-emerald-400 mb-0.5 flex items-center">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    Data Ditambahkan
                </span>
                <span class="text-xs font-bold text-slate-200">
                    {{ $penduduk->created_at ? \Carbon\Carbon::parse($penduduk->created_at)->setTimezone('Asia/Jakarta')->translatedFormat('l, d F Y - H:i:s') : '-' }} WIB
                </span>
            </div>

            <div class="bg-slate-800/80 backdrop-blur-md px-4 py-3 rounded-2xl border border-slate-700/80">
                <span class="block text-[10px] font-black uppercase tracking-widest text-amber-400 mb-0.5 flex items-center">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Terakhir Diperbarui
                </span>
                <span class="text-xs font-bold text-slate-200">
                    {{ $penduduk->updated_at ? \Carbon\Carbon::parse($penduduk->updated_at)->setTimezone('Asia/Jakarta')->translatedFormat('l, d F Y - H:i:s') : '-' }} WIB
                </span>
            </div>
        </div>
    </div>
</div>
@endsection

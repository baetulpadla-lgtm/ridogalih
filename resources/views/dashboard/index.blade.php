@extends('layouts.app')

@section('title', 'Dashboard Monitoring')

@section('content')
<div class="w-full xl:max-w-[96%] mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <div class="relative p-6 sm:p-8 rounded-2xl {{ $isWarga ? 'bg-gradient-to-br from-emerald-600 via-teal-600 to-emerald-800' : 'bg-gradient-to-br from-blue-600 via-indigo-600 to-blue-800' }} text-white shadow-lg overflow-hidden flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-48 h-48 bg-white opacity-10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 space-y-3 w-full">
            <div class="flex flex-wrap items-center justify-between w-full gap-2">
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 bg-white/20 backdrop-blur-md rounded-lg text-xs font-bold uppercase tracking-wider shadow-sm">
                        {{ $scopeBadge ?? 'Sistem Utama' }}
                    </span>
                    <span class="inline-flex items-center gap-1.5 bg-white/10 backdrop-blur-sm border border-white/20 px-3 py-1 rounded-lg text-xs font-medium">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                    </span>
                </div>
            </div>

            <div class="pt-2">
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight drop-shadow-md">Selamat Datang, {{ $user->name ?? 'Pengguna' }}!</h1>
                <p class="text-white/80 mt-1 text-sm sm:text-base max-w-xl font-medium">{{ $scopeTitle ?? 'Sistem Informasi Pemerintahan Desa Ridogalih' }}</p>
            </div>

            @if($isWarga)
                <div class="pt-4 flex gap-3">
                    <a href="{{ route('surat-keluar.create') }}" class="px-5 py-2.5 bg-white text-emerald-700 hover:bg-emerald-50 rounded-xl text-sm font-bold shadow-lg transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg> Buat Pengajuan Surat
                    </a>
                </div>
            @endif
        </div>

        <div class="relative z-10 hidden md:block">
            <img src="{{ asset('assets/icon.png') }}" class="w-28 h-28 object-contain filter drop-shadow-xl" alt="Logo Desa" onerror="this.style.display='none'">
        </div>
    </div>


    @if($isWarga)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-500 dark:text-slate-400 mb-1 uppercase tracking-wider">Total Pengajuan</p>
                    <h4 class="text-3xl font-extrabold text-slate-800 dark:text-white">{{ $wargaTotalSurat }}</h4>
                </div>
                <div class="w-12 h-12 bg-blue-50 dark:bg-blue-900/30 rounded-full flex items-center justify-center text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-500 dark:text-slate-400 mb-1 uppercase tracking-wider">Menunggu</p>
                    <h4 class="text-3xl font-extrabold text-amber-600 dark:text-amber-400">{{ $wargaMenunggu }}</h4>
                </div>
                <div class="w-12 h-12 bg-amber-50 dark:bg-amber-900/30 rounded-full flex items-center justify-center text-amber-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-500 dark:text-slate-400 mb-1 uppercase tracking-wider">Telah Selesai</p>
                    <h4 class="text-3xl font-extrabold text-emerald-600 dark:text-emerald-400">{{ $wargaSelesai }}</h4>
                </div>
                <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-900/30 rounded-full flex items-center justify-center text-emerald-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-500 dark:text-slate-400 mb-1 uppercase tracking-wider">Ditolak</p>
                    <h4 class="text-3xl font-extrabold text-red-600 dark:text-red-400">{{ $wargaDitolak }}</h4>
                </div>
                <div class="w-12 h-12 bg-red-50 dark:bg-red-900/30 rounded-full flex items-center justify-center text-red-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden mt-6">
            <div class="flex justify-between items-center p-6 border-b border-slate-200 dark:border-slate-700">
                <h2 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2">Riwayat Pengajuan Terakhir</h2>
                <a href="{{ route('surat-keluar.index') }}" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700">Lihat Semua &rarr;</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 uppercase tracking-wider text-xs border-b border-slate-200 dark:border-slate-700">
                            <th class="py-4 px-6">Tanggal</th>
                            <th class="py-4 px-6">Jenis Surat</th>
                            <th class="py-4 px-6">Keperluan</th>
                            <th class="py-4 px-6 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                        @forelse($riwayatSuratWarga as $rs)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                            <td class="py-4 px-6">{{ $rs->created_at->format('d/m/Y') }}</td>
                            <td class="py-4 px-6 font-bold">{{ $rs->jenis_surat->nama_surat }}</td>
                            <td class="py-4 px-6 truncate max-w-[200px]">{{ $rs->keperluan }}</td>
                            <td class="py-4 px-6 text-center">
                                @if($rs->status == 'Menunggu') <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-bold">Menunggu</span>
                                @elseif(in_array($rs->status, ['Disetujui', 'Selesai'])) <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-bold">Selesai</span>
                                @else <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold">{{ $rs->status }}</span> @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="py-8 text-center text-slate-400">Belum ada riwayat pengajuan surat.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    @else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <a href="{{ route('penduduk.index') }}" class="group bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-slate-500 dark:text-slate-400 mb-1 uppercase tracking-wider">Total Penduduk</p>
                        <h4 class="text-3xl font-extrabold text-slate-800 dark:text-white">{{ number_format($totalPenduduk) }}</h4>
                    </div>
                    <div class="w-14 h-14 bg-emerald-50 dark:bg-emerald-900/30 rounded-2xl flex items-center justify-center text-emerald-600 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                </div>
            </a>

            <a href="{{ route('surat-masuk.index') }}" class="group bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-slate-500 dark:text-slate-400 mb-1 uppercase tracking-wider">Surat Masuk</p>
                        <h4 class="text-3xl font-extrabold text-slate-800 dark:text-white">{{ number_format($totalSuratMasuk) }}</h4>
                    </div>
                    <div class="w-14 h-14 bg-blue-50 dark:bg-blue-900/30 rounded-2xl flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                </div>
            </a>

            <a href="{{ route('surat-keluar.index') }}" class="group relative bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all">
                @if($suratMenunggu > 0)
                    <span class="absolute -top-2 -right-2 flex h-5 w-5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-5 w-5 bg-red-500 items-center justify-center text-[10px] text-white font-bold">{{ $suratMenunggu }}</span>
                    </span>
                @endif
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-slate-500 dark:text-slate-400 mb-1 uppercase tracking-wider">Perlu Proses</p>
                        <h4 class="text-3xl font-extrabold {{ $suratMenunggu > 0 ? 'text-red-600' : 'text-slate-800 dark:text-white' }}">{{ number_format($suratMenunggu) }}</h4>
                    </div>
                    <div class="w-14 h-14 bg-red-50 dark:bg-red-900/30 rounded-2xl flex items-center justify-center text-red-600 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
            </a>

            <a href="{{ route('users.index') }}" class="group bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-slate-500 dark:text-slate-400 mb-1 uppercase tracking-wider">Total Akun</p>
                        <h4 class="text-3xl font-extrabold text-slate-800 dark:text-white">{{ number_format($totalUser) }}</h4>
                    </div>
                    <div class="w-14 h-14 bg-amber-50 dark:bg-amber-900/30 rounded-2xl flex items-center justify-center text-amber-600 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                </div>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
                <div class="flex justify-between items-center p-5 border-b border-slate-200 dark:border-slate-700 bg-red-50/50 dark:bg-red-900/10">
                    <h2 class="text-base font-bold text-red-700 dark:text-red-400 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg> Menunggu Persetujuan
                    </h2>
                    <a href="{{ route('surat-keluar.index') }}" class="text-xs font-bold text-red-600 hover:underline">Kelola &rarr;</a>
                </div>
                <div class="p-0 overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                            @forelse($latestPengajuan as $peng)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                <td class="py-3 px-4">
                                    <span class="font-bold block text-slate-800 dark:text-white">{{ $peng->penduduk->nama_lengkap ?? 'Unknown' }}</span>
                                    <span class="text-xs text-slate-500">{{ $peng->jenis_surat->nama_surat }}</span>
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <a href="{{ route('surat-keluar.show', $peng->id) }}" class="px-3 py-1.5 bg-red-100 text-red-700 hover:bg-red-200 rounded-lg text-xs font-bold transition">Proses</a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="2" class="py-6 text-center text-slate-400 text-xs">Semua pengajuan sudah diproses.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
                <div class="flex justify-between items-center p-5 border-b border-slate-200 dark:border-slate-700 bg-blue-50/50 dark:bg-blue-900/10">
                    <h2 class="text-base font-bold text-blue-700 dark:text-blue-400 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg> Surat Masuk Terbaru
                    </h2>
                    <a href="{{ route('surat-masuk.index') }}" class="text-xs font-bold text-blue-600 hover:underline">Lihat &rarr;</a>
                </div>
                <div class="p-0 overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                            @forelse($latestSuratMasuk as $sm)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50">
                                <td class="py-3 px-4">
                                    <span class="font-bold block text-slate-800 dark:text-white">{{ $sm->asal_surat }}</span>
                                    <span class="text-xs text-slate-500">{{ Str::limit($sm->perihal, 40) }}</span>
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <span class="text-[10px] text-slate-400 font-mono">{{ \Carbon\Carbon::parse($sm->tanggal_diterima)->format('d/m/Y') }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="2" class="py-6 text-center text-slate-400 text-xs">Belum ada surat masuk.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div>
            <h3 class="text-xl font-extrabold text-slate-800 dark:text-white mb-5 flex items-center">
                <span class="w-2 h-8 bg-blue-600 rounded-full mr-3"></span> Indikator Pertumbuhan Desa
            </h3>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
                    <h4 class="font-bold text-slate-700 dark:text-slate-200 mb-2">Pertumbuhan SDM & Pendidikan</h4>
                    <div id="chartSDM" class="min-h-[260px]"></div>
                </div>
                <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
                    <h4 class="font-bold text-slate-700 dark:text-slate-200 mb-2">SDA, Pertanian & Ekonomi</h4>
                    <div id="chartEkonomi" class="min-h-[260px]"></div>
                </div>
            </div>
        </div>

        <div>
            <h3 class="text-xl font-extrabold text-slate-800 dark:text-white mb-5 flex items-center">
                <span class="w-2 h-8 bg-indigo-600 rounded-full mr-3"></span> Kinerja Lembaga & Group
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
                    <h4 class="font-bold text-sm text-center text-slate-700 dark:text-slate-200 mb-2">Desa & BPD</h4>
                    <div id="chartDesaBpd" class="flex justify-center"></div>
                </div>
                <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
                    <h4 class="font-bold text-sm text-center text-slate-700 dark:text-slate-200 mb-2">Karang Taruna & PKK</h4>
                    <div id="chartPemudaPkk"></div>
                </div>
                <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
                    <h4 class="font-bold text-sm text-center text-slate-700 dark:text-slate-200 mb-2">BUMDES</h4>
                    <div id="chartBumdesKoperasi"></div>
                </div>
            </div>
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        if (!document.getElementById("chartEkonomi")) return;

        const isDark = document.documentElement.classList.contains('dark');
        const themeMode = isDark ? 'dark' : 'light';
        const fontColor = isDark ? '#94a3b8' : '#64748b';

        const commonOptions = {
            chart: { toolbar: { show: false }, background: 'transparent' },
            theme: { mode: themeMode },
            dataLabels: { enabled: false },
            legend: { labels: { colors: fontColor } },
            xaxis: { labels: { style: { colors: fontColor } } },
            yaxis: { labels: { style: { colors: fontColor } } }
        };

        // BACA DATA DARI DATABASE (DYNAMIC)
        const labelsBulan = @json($months ?? []);
        const dataSuratMasuk = @json($suratMasukTrend ?? []);
        const dataSuratKeluar = @json($suratKeluarTrend ?? []);
        const dataStatusSurat = @json($chartDesaBpd ?? [0,0,0]);

        // CHART TREN SURAT
        new ApexCharts(document.querySelector("#chartEkonomi"), {
            ...commonOptions,
            series: [
                { name: 'Surat Masuk', data: dataSuratMasuk },
                { name: 'Surat Keluar', data: dataSuratKeluar }
            ],
            chart: { type: 'area', height: 260, toolbar: { show: false } },
            colors: ['#3b82f6', '#10b981'],
            stroke: { curve: 'smooth', width: 2 },
            xaxis: { categories: labelsBulan, labels: { style: { colors: fontColor } } }
        }).render();

        // DONUT CHART STATUS SURAT REAL-TIME
        new ApexCharts(document.querySelector("#chartDesaBpd"), {
            ...commonOptions,
            series: dataStatusSurat,
            labels: ['Selesai', 'Diproses', 'Menunggu'],
            chart: { type: 'donut', height: 220, background: 'transparent' },
            colors: ['#10b981', '#f59e0b', '#ef4444'],
            stroke: { show: isDark, colors: ['#1e293b'] }
        }).render();

        // Sisakan chart dummy lainnya jika diperlukan (chartSDM, dll)...
    });
</script>
@endsection

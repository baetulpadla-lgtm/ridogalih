@extends('layouts.app')

@section('title', 'Buku Agenda & Laporan Surat')

@section('content')
<div class="w-full xl:max-w-[96%] mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800 dark:text-white flex items-center gap-2">
                <svg class="w-7 h-7 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                Rekapitulasi Buku Agenda Surat
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Filter dan cetak buku agenda surat masuk & keluar sebagai laporan resmi.</p>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-6">
        <form action="{{ route('laporan-surat.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">

            <div class="w-full md:w-1/4">
                <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase mb-1">Jenis Agenda</label>
                <select name="jenis_laporan" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-xl text-sm bg-slate-50 dark:bg-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
                    <option value="masuk" {{ $jenisLaporan == 'masuk' ? 'selected' : '' }}>Agenda Surat Masuk</option>
                    <option value="keluar" {{ $jenisLaporan == 'keluar' ? 'selected' : '' }}>Agenda Surat Keluar</option>
                </select>
            </div>

            <div class="w-full md:w-1/4">
                <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ $startDate }}" required class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-xl text-sm bg-slate-50 dark:bg-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
            </div>

            <div class="w-full md:w-1/4">
                <label class="block text-xs font-bold text-slate-600 dark:text-slate-300 uppercase mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ $endDate }}" required class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-xl text-sm bg-slate-50 dark:bg-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
            </div>

            <div class="w-full md:w-1/4 flex gap-2">
                <button type="submit" class="w-full px-4 py-2.5 bg-slate-800 dark:bg-slate-700 text-white rounded-xl text-sm font-bold shadow-md hover:bg-slate-900 transition flex justify-center items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg> Filter
                </button>

                <a href="{{ route('laporan-surat.print', ['jenis_laporan' => $jenisLaporan, 'start_date' => $startDate, 'end_date' => $endDate]) }}" target="_blank" class="w-full px-4 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-bold shadow-md shadow-indigo-500/30 hover:bg-indigo-700 transition flex justify-center items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg> Cetak PDF
                </a>
            </div>
        </form>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="p-4 bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
            <h3 class="font-bold text-slate-700 dark:text-slate-300 text-sm">Preview Data: Buku Agenda Surat {{ ucfirst($jenisLaporan) }}</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-slate-100 dark:bg-slate-900/80 text-slate-600 dark:text-slate-400 uppercase text-xs">
                    <tr>
                        <th class="py-3 px-4 text-center w-12">No</th>
                        @if($jenisLaporan == 'masuk')
                            <th class="py-3 px-4">Tanggal Terima</th>
                            <th class="py-3 px-4">Nomor & Tanggal Surat</th>
                            <th class="py-3 px-4">Asal Surat</th>
                            <th class="py-3 px-4">Perihal / Isi Ringkas</th>
                            <th class="py-3 px-4">Disposisi</th>
                        @else
                            <th class="py-3 px-4">Tgl Pembuatan</th>
                            <th class="py-3 px-4">Nomor Surat</th>
                            <th class="py-3 px-4">Tujuan / Pemohon</th>
                            <th class="py-3 px-4">Jenis Surat & Keperluan</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($dataSurat as $index => $surat)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 text-slate-700 dark:text-slate-300">
                            <td class="py-3 px-4 text-center">{{ $index + 1 }}</td>
                            @if($jenisLaporan == 'masuk')
                                <td class="py-3 px-4">{{ \Carbon\Carbon::parse($surat->tanggal_diterima)->format('d/m/Y') }}</td>
                                <td class="py-3 px-4"><span class="font-bold">{{ $surat->nomor_surat }}</span><br><span class="text-xs text-slate-500">{{ \Carbon\Carbon::parse($surat->tanggal_surat)->format('d/m/Y') }}</span></td>
                                <td class="py-3 px-4">{{ $surat->asal_surat }}</td>
                                <td class="py-3 px-4 whitespace-normal min-w-[200px]">{{ $surat->perihal }}</td>
                                <td class="py-3 px-4">{{ $surat->disposisi_kepada ?? '-' }}</td>
                            @else
                                <td class="py-3 px-4">{{ $surat->created_at->format('d/m/Y') }}</td>
                                <td class="py-3 px-4 font-bold">{{ $surat->nomor_surat ?? 'Belum ada nomor' }}</td>
                                <td class="py-3 px-4">{{ $surat->penduduk->nama_lengkap ?? 'Umum' }}</td>
                                <td class="py-3 px-4 whitespace-normal min-w-[200px]"><span class="font-semibold text-indigo-600 dark:text-indigo-400">{{ $surat->jenis_surat->nama_surat }}</span><br>{{ $surat->keperluan }}</td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">Tidak ada data surat pada rentang tanggal tersebut.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

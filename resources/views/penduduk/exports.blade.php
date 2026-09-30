@extends('layouts.app')

@section('title', 'Unduhan Data Penduduk')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white">Unduhan Ekspor Penduduk</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">File hanya tersedia untuk akun Anda dan akan dihapus setelah masa berlaku berakhir.</p>
        </div>
        <a href="{{ route('penduduk.index') }}" class="px-4 py-2.5 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm font-bold text-slate-700 dark:text-slate-200">Kembali ke Data Penduduk</a>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 font-semibold">{{ session('success') }}</div>
    @endif

    <div class="overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 uppercase text-xs">
                <tr>
                    <th class="px-5 py-3">Nama File</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3">Kedaluwarsa</th>
                    <th class="px-5 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                @forelse($exports as $export)
                    <tr>
                        <td class="px-5 py-4 font-semibold text-slate-800 dark:text-slate-100">{{ $export->filename }}</td>
                        <td class="px-5 py-4">
                            @if($export->status === 'ready')
                                <span class="text-emerald-700 dark:text-emerald-400 font-bold">Siap diunduh</span>
                            @else
                                <span class="text-amber-700 dark:text-amber-400 font-bold">Sedang diproses</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-slate-500">{{ $export->expires_at->format('d-m-Y H:i') }}</td>
                        <td class="px-5 py-4 text-right">
                            @if($export->status === 'ready')
                                <a href="{{ route('penduduk.exports.download', $export) }}" class="inline-flex px-4 py-2 rounded-lg bg-blue-600 text-white font-bold hover:bg-blue-700">Unduh</a>
                            @else
                                <span class="text-slate-400">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-5 py-10 text-center text-slate-500">Belum ada file ekspor yang tersedia.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $exports->links() }}
</div>
@endsection

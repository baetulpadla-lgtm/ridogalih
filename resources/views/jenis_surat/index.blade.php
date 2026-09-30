@extends('layouts.app')
@section('title', 'Master Jenis Surat')
@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center bg-white p-6 rounded-xl shadow-sm border border-gray-100 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Master Jenis Surat</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola template dan format surat yang dilayani oleh desa.</p>
        </div>
        <a href="{{ route('jenis-surat.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-semibold shadow-sm transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Jenis Surat
        </a>
    </div>

    @if (session('success'))
    <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        {{ session('success') }}
    </div>
    @endif

    <div class="mb-6 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <form action="{{ route('jenis-surat.index') }}" method="GET" class="flex gap-3">
            <div class="flex-1 relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau kode surat..." class="w-full pl-4 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <button type="submit" class="px-5 py-2 bg-gray-800 text-white text-sm font-semibold rounded-lg hover:bg-gray-700 transition">Cari</button>
            @if(request('search'))
                <a href="{{ route('jenis-surat.index') }}" class="px-4 py-2 bg-gray-100 text-gray-600 text-sm font-semibold rounded-lg hover:bg-gray-200 transition flex items-center">Reset</a>
            @endif
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-gray-500 text-xs uppercase tracking-wider font-semibold">
                        <th class="py-3 px-6">Kode Surat</th>
                        <th class="py-3 px-6">Nama Surat</th>
                        <th class="py-3 px-6">Deskripsi</th>
                        <th class="py-3 px-6 text-center">Status</th>
                        <th class="py-3 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    @forelse ($jenis_surats as $surat)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-6 font-bold text-gray-800">
                            <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 rounded-lg border border-indigo-100 text-xs font-extrabold">{{ $surat->kode_surat }}</span>
                        </td>
                        <td class="py-3 px-6 font-semibold">{{ $surat->nama_surat }}</td>
                        <td class="py-3 px-6 text-gray-500 max-w-xs truncate">{{ $surat->deskripsi ?? '-' }}</td>
                        <td class="py-3 px-6 text-center">
                            @if($surat->is_active)
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">Aktif</span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">Nonaktif</span>
                            @endif
                        </td>
                        <td class="py-3 px-6 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('jenis-surat.show', $surat->uuid) }}" class="p-2 bg-teal-50 text-teal-600 dark:bg-teal-900/20 dark:text-teal-400 hover:bg-teal-100 dark:hover:bg-teal-900/40 rounded-xl transition-all shadow-sm hover:scale-105" title="Detail Arsip">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                                <a href="{{ route('jenis-surat.edit', $surat->uuid) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>
                                <form action="{{ route('jenis-surat.destroy', $surat->uuid) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus jenis surat ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-gray-400 font-medium">Belum ada jenis surat ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100">
            {{ $jenis_surats->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection

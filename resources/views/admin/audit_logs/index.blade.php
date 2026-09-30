@extends('layouts.app')

@section('title', 'Audit Log & Keamanan')

@section('content')
<div class="w-full xl:max-w-[96%] mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    <!-- Page Header (Sesuai Dashboard) -->
    <div class="relative p-6 sm:p-8 rounded-2xl bg-gradient-to-br from-slate-700 via-slate-800 to-slate-900 text-white shadow-lg overflow-hidden flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-white opacity-5 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 space-y-3 w-full">
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 bg-white/20 backdrop-blur-md rounded-lg text-xs font-bold uppercase tracking-wider shadow-sm">
                    <i class="fas fa-shield-alt mr-1"></i> Keamanan Sistem
                </span>
                <nav aria-label="breadcrumb">
                    <ol class="flex items-center space-x-2 text-xs font-medium text-white/70">
                        <li>Dashboard</li>
                        <li><span class="mx-1">/</span></li>
                        <li class="text-white font-bold">Audit Log</li>
                    </ol>
                </nav>
            </div>
            <div class="pt-2">
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight drop-shadow-md">Jejak Aktivitas & Audit Log</h1>
                <p class="text-white/80 mt-1 text-sm sm:text-base max-w-xl font-medium">Pantau seluruh rekam jejak aktivitas, perubahan data, dan intelijen perangkat secara real-time.</p>
            </div>
        </div>
    </div>

    <!-- Advanced Filter & Search -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 shadow-sm">
        <form action="{{ route('admin.audit-logs.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 items-end">
            <!-- Global Search -->
            <div>
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Pencarian Global</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-slate-400"></i>
                    </div>
                    <input type="text" name="search" class="block w-full pl-10 pr-3 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl leading-5 bg-slate-50 dark:bg-slate-900/50 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-all shadow-sm" placeholder="IP, User, Record ID..." value="{{ request('search') }}">
                </div>
            </div>

            <!-- Event Filter -->
            <div>
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Filter Aksi</label>
                <select name="event" class="block w-full px-3 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-slate-50 dark:bg-slate-900/50 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-all shadow-sm">
                    <option value="">Semua Aksi</option>
                    <option value="CREATED" {{ request('event') == 'CREATED' ? 'selected' : '' }}>CREATED (Tambah)</option>
                    <option value="UPDATED" {{ request('event') == 'UPDATED' ? 'selected' : '' }}>UPDATED (Ubah)</option>
                    <option value="DELETED" {{ request('event') == 'DELETED' ? 'selected' : '' }}>DELETED (Hapus)</option>
                    <option value="RESTORED" {{ request('event') == 'RESTORED' ? 'selected' : '' }}>RESTORED (Pulih)</option>
                </select>
            </div>

            <!-- Table Filter -->
            <div>
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Tabel Target</label>
                <select name="table_name" class="block w-full px-3 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-slate-50 dark:bg-slate-900/50 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-all shadow-sm">
                    <option value="">Semua Tabel</option>
                    @foreach($tables as $tbl)
                        <option value="{{ $tbl }}" {{ request('table_name') == $tbl ? 'selected' : '' }}>{{ $tbl }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2">
                <button type="submit" class="flex-1 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold shadow-md transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <a href="{{ route('admin.audit-logs.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-600 dark:text-slate-300 rounded-xl text-sm font-bold shadow-sm transition-all flex items-center justify-center" title="Reset Filter">
                    <i class="fas fa-sync-alt"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 uppercase tracking-wider text-xs border-b border-slate-200 dark:border-slate-700">
                        <th class="py-4 px-6 font-bold">Waktu Eksekusi</th>
                        <th class="py-4 px-6 font-bold">Eksekutor</th>
                        <th class="py-4 px-6 font-bold">Aksi</th>
                        <th class="py-4 px-6 font-bold">Tabel & Record ID</th>
                        <th class="py-4 px-6 font-bold">Informasi Jaringan</th>
                        <th class="py-4 px-6 text-right font-bold">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                    @forelse($auditLogs as $log)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                        <td class="py-4 px-6">
                            <span class="block font-bold text-slate-800 dark:text-white">{{ $log->created_at->format('d/m/Y') }}</span>
                            <span class="text-xs text-slate-500 font-mono">{{ $log->created_at->format('H:i:s') }} WIB</span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold text-sm shadow-inner">
                                    {{ strtoupper(substr($log->user?->name ?? 'S', 0, 1)) }}
                                </div>
                                <div>
                                    <span class="block font-bold text-slate-800 dark:text-white truncate max-w-[150px]">{{ $log->user?->name ?? 'System/Guest' }}</span>
                                    <span class="text-xs text-slate-500 font-mono">ID: {{ $log->user_id ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            @php
                                $badgeStyle = match($log->event) {
                                    'CREATED' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800',
                                    'UPDATED' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 border-amber-200 dark:border-amber-800',
                                    'DELETED' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 border-red-200 dark:border-red-800',
                                    'RESTORED' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 border-blue-200 dark:border-blue-800',
                                    default => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 border-slate-200'
                                };
                            @endphp
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold border {{ $badgeStyle }} inline-flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full currentColor bg-current"></span>
                                {{ $log->event }}
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <span class="px-2 py-1 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded border border-slate-200 dark:border-slate-600 text-xs font-bold block w-max mb-1">{{ $log->table_name }}</span>
                            <span class="text-xs text-slate-500 font-mono" title="{{ $log->record_id }}">Key: {{ Str::limit($log->record_id, 12) }}</span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-mono text-slate-800 dark:text-white text-sm font-bold">{{ $log->ip_address }}</span>
                                @if($log->is_robot)
                                    <span class="px-1.5 py-0.5 bg-red-100 text-red-600 rounded text-[10px] font-bold"><i class="fas fa-robot"></i> Bot</span>
                                @endif
                            </div>
                            <span class="text-xs text-slate-500 flex items-center gap-1.5">
                                @if($log->device_type == 'Mobile') <i class="fas fa-mobile-alt"></i> @else <i class="fas fa-desktop"></i> @endif
                                {{ $log->platform ?? 'OS' }} &bull; {{ $log->browser ?? 'Browser' }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-right">
                            <a href="{{ route('admin.audit-logs.show', $log->id) }}" class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/20 dark:hover:bg-blue-900/40 text-blue-700 dark:text-blue-400 rounded-lg text-xs font-bold transition-colors border border-blue-200 dark:border-blue-800/50">
                                <i class="fas fa-search-plus"></i> Inspeksi
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-400 mb-4">
                                <i class="fas fa-folder-open text-2xl"></i>
                            </div>
                            <h3 class="text-slate-800 dark:text-white font-bold mb-1">Belum ada riwayat aktivitas</h3>
                            <p class="text-slate-500 text-sm">Aktivitas sistem atau perubahan data akan tercatat otomatis di sini.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($auditLogs->hasPages())
        <div class="p-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50">
            {{ $auditLogs->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

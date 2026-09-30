@extends('layouts.app')

@section('title', 'Inspeksi Audit Log')

@section('content')
<div class="w-full xl:max-w-[96%] mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    <!-- Back Button -->
    <a href="{{ route('admin.audit-logs.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white transition-colors mb-2">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Log
    </a>

    <!-- Header Summary Card -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 sm:p-8 shadow-sm">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Analisis Forensik Keamanan</p>
                <h2 class="text-2xl font-extrabold text-slate-800 dark:text-white flex items-center gap-3">
                    Inspeksi Data
                    <span class="px-2.5 py-1 bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-300 text-xs font-mono rounded border border-slate-200 dark:border-slate-600 shadow-inner">ID: {{ $auditLog->id }}</span>
                </h2>
                <p class="text-slate-500 text-sm mt-1">Terekam pada: <strong class="text-slate-700 dark:text-slate-300">{{ $auditLog->created_at->translatedFormat('d F Y - H:i:s') }} WIB</strong></p>
            </div>

            @php
                $badgeStyle = match($auditLog->event) {
                    'CREATED' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 border-emerald-200',
                    'UPDATED' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 border-amber-200',
                    'DELETED' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 border-red-200',
                    'RESTORED' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 border-blue-200',
                    default => 'bg-slate-100 text-slate-700 dark:bg-slate-800 border-slate-200'
                };
            @endphp
            <div class="px-4 py-2 rounded-xl text-sm font-extrabold border {{ $badgeStyle }} shadow-sm">
                {{ $auditLog->event }} EVENT
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-t border-slate-200 dark:border-slate-700 pt-8">
            <!-- Executor -->
            <div class="bg-slate-50 dark:bg-slate-900/50 p-4 rounded-xl border border-slate-100 dark:border-slate-800">
                <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2"><i class="fas fa-user-shield mr-1"></i> Eksekutor</p>
                <p class="font-bold text-slate-800 dark:text-white truncate">{{ $auditLog->user?->name ?? 'System Console / Guest' }}</p>
                <p class="text-sm font-mono text-slate-500">User ID: {{ $auditLog->user_id ?? 'N/A' }}</p>
            </div>

            <!-- Target -->
            <div class="bg-slate-50 dark:bg-slate-900/50 p-4 rounded-xl border border-slate-100 dark:border-slate-800">
                <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2"><i class="fas fa-database mr-1"></i> Target Basis Data</p>
                <p class="font-bold text-slate-800 dark:text-white font-mono truncate">{{ $auditLog->table_name }}</p>
                <p class="text-sm font-mono text-slate-500 truncate" title="{{ $auditLog->record_id }}">Key: {{ $auditLog->record_id }}</p>
            </div>

            <!-- Network & Device -->
            <div class="bg-slate-50 dark:bg-slate-900/50 p-4 rounded-xl border border-slate-100 dark:border-slate-800">
                <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2"><i class="fas fa-network-wired mr-1"></i> Jaringan & Perangkat</p>
                <div class="flex items-center gap-2 mb-0.5">
                    <p class="font-bold text-slate-800 dark:text-white font-mono truncate">{{ $auditLog->ip_address }}</p>
                    @if($auditLog->is_robot)
                        <span class="px-1.5 py-0.5 bg-red-100 text-red-600 rounded text-[10px] font-bold"><i class="fas fa-robot"></i></span>
                    @endif
                </div>
                <p class="text-xs text-slate-500 truncate mb-1">
                    @if($auditLog->device_type == 'Mobile') <i class="fas fa-mobile-alt"></i> @else <i class="fas fa-desktop"></i> @endif
                    {{ $auditLog->platform ?? 'OS' }} &bull; {{ $auditLog->browser ?? 'Browser' }}
                </p>
                <p class="text-[10px] text-slate-400 font-mono truncate" title="{{ $auditLog->user_agent }}">{{ $auditLog->user_agent }}</p>
            </div>
        </div>
    </div>

    <!-- Diff Viewer (Old vs New Values) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Old Values -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-red-200 dark:border-red-900/50 shadow-sm overflow-hidden flex flex-col h-full">
            <div class="bg-red-50 dark:bg-red-900/20 px-6 py-4 border-b border-red-100 dark:border-red-900/50 flex justify-between items-center">
                <h3 class="font-bold text-red-700 dark:text-red-400 flex items-center gap-2">
                    <i class="fas fa-history"></i> Data Lama (Sebelum)
                </h3>
            </div>
            <div class="p-4 bg-slate-900 flex-1 overflow-x-auto">
                <pre class="text-xs font-mono text-red-300"><code>@if($auditLog->old_values)
{{ json_encode($auditLog->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}
@else
<span class="text-slate-500 italic">// Tidak ada data lama (Kondisi Insert/Created)</span>
@endif</code></pre>
            </div>
        </div>

        <!-- New Values -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-emerald-200 dark:border-emerald-900/50 shadow-sm overflow-hidden flex flex-col h-full">
            <div class="bg-emerald-50 dark:bg-emerald-900/20 px-6 py-4 border-b border-emerald-100 dark:border-emerald-900/50 flex justify-between items-center">
                <h3 class="font-bold text-emerald-700 dark:text-emerald-400 flex items-center gap-2">
                    <i class="fas fa-cube"></i> Data Baru (Sesudah)
                </h3>
            </div>
            <div class="p-4 bg-slate-900 flex-1 overflow-x-auto">
                <pre class="text-xs font-mono text-emerald-300"><code>@if($auditLog->new_values)
{{ json_encode($auditLog->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}
@else
<span class="text-slate-500 italic">// Tidak ada data baru (Kondisi Delete/Hapus)</span>
@endif</code></pre>
            </div>
        </div>

    </div>
</div>
@endsection

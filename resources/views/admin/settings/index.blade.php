@extends('layouts.app')

@section('title', 'Pengaturan Umum & Keamanan Sistem')

@section('content')
<div class="w-full xl:max-w-[96%] mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8" x-data="{ activeTab: 'general' }">

    <!-- Header Section (Dashboard Aligned) -->
    <div class="relative p-6 sm:p-8 rounded-2xl bg-gradient-to-br from-indigo-600 via-blue-600 to-indigo-800 text-white shadow-lg overflow-hidden flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-48 h-48 bg-white opacity-10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 space-y-3 w-full">
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 bg-white/20 backdrop-blur-md rounded-lg text-xs font-bold uppercase tracking-wider shadow-sm">
                    Konfigurasi Sistem & Keamanan
                </span>
            </div>
            <div class="pt-2">
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight drop-shadow-md">Pengaturan Umum (General Settings)</h1>
                <p class="text-white/80 mt-1 text-sm sm:text-base max-w-xl font-medium">Kelola parameter kebijakan keamanan sandi, kontrol sesi multi-perangkat, dan pusat cadangan (backup) data sistem E-Office.</p>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" class="p-4 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800/50 text-emerald-700 dark:text-emerald-400 rounded-2xl font-semibold flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-3">
                <div class="p-1.5 bg-emerald-100 dark:bg-emerald-800/50 rounded-lg shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                </div>
                {{ session('success') }}
            </div>
            <button @click="show = false" class="text-emerald-600 hover:text-emerald-800 transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
        </div>
    @endif

    <!-- Navigation Tabs -->
    <div class="flex flex-wrap gap-2 border-b border-slate-200 dark:border-slate-700 pb-3">
        <button @click="activeTab = 'general'" :class="activeTab === 'general' ? 'bg-blue-600 text-white shadow-md' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700'" class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all flex items-center gap-2">
            <i class="fas fa-sliders-h"></i> Konfigurasi Umum
        </button>
        <button @click="activeTab = 'security'" :class="activeTab === 'security' ? 'bg-blue-600 text-white shadow-md' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700'" class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all flex items-center gap-2">
            <i class="fas fa-shield-alt"></i> Kebijakan Sandi & Sesi
        </button>
        <button @click="activeTab = 'backup'" :class="activeTab === 'backup' ? 'bg-blue-600 text-white shadow-md' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700'" class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all flex items-center gap-2">
            <i class="fas fa-database"></i> Backup & Database
        </button>
    </div>

    <!-- Main Settings Form -->
    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- TAB 1: GENERAL CONFIGURATION -->
        <div x-show="activeTab === 'general'" class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 sm:p-8 shadow-sm space-y-6">
            <div class="border-b border-slate-100 dark:border-slate-700 pb-4">
                <h2 class="text-lg font-extrabold text-slate-800 dark:text-white flex items-center gap-2">
                    <i class="fas fa-globe text-blue-500"></i> Identitas & Konfigurasi Aplikasi Desa
                </h2>
                <p class="text-xs text-slate-500 mt-1">Pengaturan dasar operasional instansi dan sistem pemerintahan desa.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Nama Instansi / Desa</label>
                    <input type="text" name="app_name" value="{{ $settings['app_name'] ?? 'Pemerintahan Desa Ridogalih' }}" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Email Layanan Resmi</label>
                    <input type="email" name="app_email" value="{{ $settings['app_email'] ?? 'admin@ridogalih.desa.id' }}" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Alamat Kantor Desa</label>
                    <textarea name="app_address" rows="2" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">{{ $settings['app_address'] ?? 'Jl. Raya Ridogalih No. 1, Kec. Cikarang Pusat, Kabupaten Bekasi' }}</textarea>
                </div>
            </div>
        </div>

        <!-- TAB 2: SECURITY POLICY & SESSION CONTROL -->
        <div x-show="activeTab === 'security'" class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 sm:p-8 shadow-sm space-y-6">
            <div class="border-b border-slate-100 dark:border-slate-700 pb-4">
                <h2 class="text-lg font-extrabold text-slate-800 dark:text-white flex items-center gap-2">
                    <i class="fas fa-lock text-emerald-500"></i> Kebijakan Sandi & Manajemen Sesi Ketat
                </h2>
                <p class="text-xs text-slate-500 mt-1">Tentukan kompleksitas wajib kata sandi pengguna dan batasan multi-sesi.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Minimal Panjang Sandi</label>
                    <input type="number" name="password_min_length" value="{{ $settings['password_min_length'] ?? 8 }}" min="6" max="32" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-mono focus:ring-2 focus:ring-emerald-500">
                </div>
                <div class="flex flex-col justify-end space-y-3 pb-2 md:col-span-2">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <!-- Uppercase -->
                        <input type="checkbox" name="password_require_uppercase" value="1" {{ ($settings['password_require_uppercase'] ?? '1') == '1' ? 'checked' : '' }} class="rounded border-slate-300 text-blue-600 h-5 w-5 focus:ring-blue-500">
                        <span class="text-sm font-bold text-slate-700 dark:text-slate-300">Wajib Huruf Besar (Uppercase - A-Z)</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <!-- Symbols -->
                        <input type="checkbox" name="password_require_symbols" value="1" {{ ($settings['password_require_symbols'] ?? '1') == '1' ? 'checked' : '' }} class="rounded border-slate-300 text-blue-600 h-5 w-5 focus:ring-blue-500">
                        <span class="text-sm font-bold text-slate-700 dark:text-slate-300">Wajib Karakter Khusus / Simbol (!@#$%^&*)</span>
                    </label>
                </div>
            </div>

            <hr class="border-slate-100 dark:border-slate-700">

            <!-- Session & Multi-Login Control -->
            <div class="space-y-4">
                <h3 class="font-bold text-slate-800 dark:text-white text-sm uppercase tracking-wide">Kontrol Akses Bersamaan (Concurrency & Tab Control)</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="p-4 bg-slate-50 dark:bg-slate-900/50 rounded-xl border border-slate-200 dark:border-slate-700 flex items-start gap-3">
                        <!-- Single Session -->
                        <input type="checkbox" name="strict_single_session" value="1" {{ ($settings['strict_single_session'] ?? '1') == '1' ? 'checked' : '' }} class="mt-1 rounded border-slate-300 text-blue-600 h-5 w-5 focus:ring-blue-500">
                        <div>
                            <p class="text-sm font-bold text-slate-800 dark:text-white">Anti-Concurrent Login (Akun Terpental)</p>
                            <p class="text-xs text-slate-500 mt-0.5">Jika akun yang sama login di perangkat atau browser lain, sesi sebelumnya akan otomatis diputus (terpental).</p>
                        </div>
                    </div>
                    <div class="p-4 bg-slate-50 dark:bg-slate-900/50 rounded-xl border border-slate-200 dark:border-slate-700 flex items-start gap-3">
                        <!-- Single Tab -->
                        <input type="checkbox" name="strict_single_tab" value="1" {{ ($settings['strict_single_tab'] ?? '1') == '1' ? 'checked' : '' }} class="mt-1 rounded border-slate-300 text-blue-600 h-5 w-5 focus:ring-blue-500">
                        <div>
                            <p class="text-sm font-bold text-slate-800 dark:text-white">Blokir Multi-Tab Browser</p>
                            <p class="text-xs text-slate-500 mt-0.5">Mencegah pengguna membuka aplikasi E-Office di banyak tab browser secara bersamaan untuk mencegah konflik state.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 3: BACKUP & DATABASE CENTER -->
        <div x-show="activeTab === 'backup'" class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 sm:p-8 shadow-sm space-y-6">
            <div class="border-b border-slate-100 dark:border-slate-700 pb-4">
                <h2 class="text-lg font-extrabold text-slate-800 dark:text-white flex items-center gap-2">
                    <i class="fas fa-database text-indigo-500"></i> Pusat Cadangan & Ekspor Data (Backup Center)
                </h2>
                <p class="text-xs text-slate-500 mt-1">Unduh rekap database secara berkala untuk keperluan pemulihan darurat sistem.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <!-- SQL Backup -->
                <div class="p-5 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-slate-900 dark:to-slate-900/80 rounded-2xl border border-blue-200 dark:border-slate-700 flex flex-col justify-between gap-4">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold mb-3 shadow-sm">
                            <i class="fas fa-file-code"></i>
                        </div>
                        <h3 class="font-bold text-slate-800 dark:text-white text-base">Cadangan Format .SQL</h3>
                        <p class="text-xs text-slate-500 mt-1">Mencakup struktur tabel lengkap beserta seluruh isi data menggunakan mysqldump.</p>
                    </div>
                    <a href="{{ route('admin.settings.backup') }}" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold text-center shadow-md transition flex items-center justify-center gap-2">
                        <i class="fas fa-download"></i> Unduh File .SQL
                    </a>
                </div>

                <!-- Excel / CSV Export Placeholder -->
                <div class="p-5 bg-gradient-to-br from-emerald-50 to-teal-50 dark:from-slate-900 dark:to-slate-900/80 rounded-2xl border border-emerald-200 dark:border-slate-700 flex flex-col justify-between gap-4">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold mb-3 shadow-sm">
                            <i class="fas fa-file-excel"></i>
                        </div>
                        <h3 class="font-bold text-slate-800 dark:text-white text-base">Ekspor Format Excel / CSV</h3>
                        <p class="text-xs text-slate-500 mt-1">Rekap data penduduk dan surat-menyurat dalam format spreadsheet tabular.</p>
                    </div>
                    <button type="button" onclick="alert('Fitur ekspor spreadsheet massal dapat dihubungkan ke controller export masing-masing modul.')" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold text-center shadow-md transition flex items-center justify-center gap-2">
                        <i class="fas fa-file-export"></i> Ekspor Spreadsheet
                    </button>
                </div>
            </div>
        </div>

        <!-- Global Save Button -->
        <div class="flex justify-end pt-4">
            <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-extrabold shadow-lg shadow-blue-500/30 transition-all active:scale-95 flex items-center gap-2">
                <i class="fas fa-save"></i> Simpan Seluruh Perubahan Konfigurasi
            </button>
        </div>
    </form>
</div>
@endsection

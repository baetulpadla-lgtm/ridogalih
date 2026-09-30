@extends('layouts.app')

@section('title', 'Pendaftaran Akun Pengguna')

@section('content')
<div class="w-full max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6 animate-fade-in">

    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800 dark:text-white tracking-tight">Pendaftaran Akun Pengguna Baru</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Hanya penduduk dengan NIK terdaftar di master data yang dapat dibuatkan akses sistem E-Office.</p>
        </div>
        <a href="{{ route('users.index') }}" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-700/50 text-slate-700 dark:text-slate-300 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition text-sm font-semibold flex items-center gap-2 shadow-sm focus:ring-2 focus:ring-slate-400 focus:outline-none">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar
        </a>
    </div>

    @if ($errors->any())
    <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-2xl p-5 shadow-sm animate-fade-in">
        <div class="flex items-center gap-2 mb-2 text-red-700 dark:text-red-400 font-bold text-sm">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Terdapat beberapa kesalahan pengisian form:
        </div>
        <ul class="text-xs text-red-600 dark:text-red-400 list-disc list-inside ml-5 space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('users.store') }}" method="POST" class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 md:p-8 space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="md:col-span-2 group">
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-blue-500 group-focus-within:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Pilih Penduduk (Master Data NIK) <span class="text-red-500">*</span>
                </label>
                <select name="penduduk_id" required class="w-full px-4 py-3 rounded-xl border @error('penduduk_id') border-red-500 ring-1 ring-red-500 @else border-slate-300 dark:border-slate-600 @enderror bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-blue-500 dark:text-white text-sm transition cursor-pointer">
                    <option value="">-- Cari dan Pilih Berdasarkan NIK / Nama Lengkap --</option>
                    @foreach($penduduks as $p)
                        <option value="{{ $p->id }}" {{ (string) old('penduduk_id') === (string) $p->id ? 'selected' : '' }}>
                            {{ e($p->nik) }} - {{ e($p->nama_lengkap) }} ({{ e($p->dusun ?? 'Dusun belum diset') }})
                        </option>
                    @endforeach
                </select>
                @error('penduduk_id') <p class="text-red-500 text-xs mt-1.5 font-semibold">{{ $message }}</p> @enderror
                <p class="text-xs text-slate-400 mt-1.5">*Hanya menampilkan penduduk yang belum memiliki akun login aktif.</p>
            </div>

            <div class="group">
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-blue-500 group-focus-within:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    Alamat Email (Opsional)
                </label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="contoh@ridogalih.desa.id" class="w-full px-4 py-3 rounded-xl border @error('email') border-red-500 ring-1 ring-red-500 @else border-slate-300 dark:border-slate-600 @enderror bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-blue-500 dark:text-white text-sm transition">
                @error('email') <p class="text-red-500 text-xs mt-1.5 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div class="group">
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-blue-500 group-focus-within:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Status Akun <span class="text-red-500">*</span>
                </label>
                <select name="status_akun" required class="w-full px-4 py-3 rounded-xl border @error('status_akun') border-red-500 ring-1 ring-red-500 @else border-slate-300 dark:border-slate-600 @enderror bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-blue-500 dark:text-white text-sm transition cursor-pointer">
                    <option value="Aktif" {{ old('status_akun', 'Aktif') == 'Aktif' ? 'selected' : '' }}>🟢 Aktif (Dapat Login)</option>
                    <option value="Nonaktif" {{ old('status_akun') == 'Nonaktif' ? 'selected' : '' }}>⚪ Nonaktif (Terkunci)</option>
                    <option value="Suspend" {{ old('status_akun') == 'Suspend' ? 'selected' : '' }}>🔴 Suspend (Dibekukan)</option>
                </select>
                @error('status_akun') <p class="text-red-500 text-xs mt-1.5 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div class="group">
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-blue-500 group-focus-within:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    Kata Sandi Akun <span class="text-red-500">*</span>
                </label>
                <input type="password" name="password" required minlength="8" placeholder="Minimal 8 karakter" class="w-full px-4 py-3 rounded-xl border @error('password') border-red-500 ring-1 ring-red-500 @else border-slate-300 dark:border-slate-600 @enderror bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-blue-500 dark:text-white text-sm transition">
                @error('password') <p class="text-red-500 text-xs mt-1.5 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div class="group">
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-blue-500 group-focus-within:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    Konfirmasi Kata Sandi <span class="text-red-500">*</span>
                </label>
                <input type="password" name="password_confirmation" required minlength="8" placeholder="Ketik ulang kata sandi" class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-blue-500 dark:text-white text-sm transition">
            </div>

            <div class="group">
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-blue-500 group-focus-within:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    Group Lembaga <span class="text-red-500">*</span>
                </label>
                <select id="group_select" name="group_id" required class="w-full px-4 py-3 rounded-xl border @error('group_id') border-red-500 ring-1 ring-red-500 @else border-slate-300 dark:border-slate-600 @enderror bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-blue-500 dark:text-white text-sm transition cursor-pointer">
                    <option value="">-- Pilih Group / Unit Kerja --</option>
                    @foreach($groups as $g)
                        <option value="{{ $g->id }}" {{ (string) old('group_id') === (string) $g->id ? 'selected' : '' }}>
                            {{ e($g->name) }}
                        </option>
                    @endforeach
                </select>
                @error('group_id') <p class="text-red-500 text-xs mt-1.5 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div class="group">
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-blue-500 group-focus-within:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    Role Jabatan Akses <span class="text-red-500">*</span>
                </label>
                <select id="role_select" name="role_id" required class="w-full px-4 py-3 rounded-xl border @error('role_id') border-red-500 ring-1 ring-red-500 @else border-slate-300 dark:border-slate-600 @enderror bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-blue-500 dark:text-white disabled:opacity-50 disabled:cursor-not-allowed text-sm transition cursor-pointer" disabled>
                    <option value="">-- Pilih Group Terlebih Dahulu --</option>
                </select>
                @error('role_id') <p class="text-red-500 text-xs mt-1.5 font-semibold">{{ $message }}</p> @enderror
            </div>

        </div>

        <div class="mt-8 pt-6 border-t border-slate-200 dark:border-slate-700 flex flex-col sm:flex-row justify-end gap-3">
            <a href="{{ route('users.index') }}" class="px-6 py-3 bg-slate-100 dark:bg-slate-700/50 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl font-semibold text-center transition focus:ring-2 focus:ring-slate-400 focus:outline-none">
                Batal
            </a>
            <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold shadow-md shadow-blue-500/30 transition-all flex items-center justify-center gap-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Simpan & Daftarkan Akun
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const groupsData = @json($groups);
        const oldRoleId = String("{{ old('role_id') }}");

        const groupSelect = document.getElementById('group_select');
        const roleSelect = document.getElementById('role_select');

        function updateRoleDropdown() {
            const selectedGroupId = String(groupSelect.value);
            roleSelect.innerHTML = '<option value="">-- Pilih Role / Jabatan --</option>';

            if (selectedGroupId) {
                roleSelect.disabled = false;
                const selectedGroup = groupsData.find(g => String(g.id) === selectedGroupId);

                if (selectedGroup && selectedGroup.roles) {
                    selectedGroup.roles.forEach(role => {
                        const option = document.createElement('option');
                        option.value = role.id;
                        option.textContent = role.name;

                        // Validasi dengan Strict String Equality untuk PHP 8.4
                        if (String(role.id) === oldRoleId) {
                            option.selected = true;
                        }

                        roleSelect.appendChild(option);
                    });
                }
            } else {
                roleSelect.disabled = true;
                roleSelect.innerHTML = '<option value="">-- Pilih Group Terlebih Dahulu --</option>';
            }
        }

        groupSelect.addEventListener('change', updateRoleDropdown);

        if (groupSelect.value !== '') {
            updateRoleDropdown();
        }
    });
</script>
@endsection

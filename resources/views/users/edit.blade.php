@extends('layouts.app')

@section('title', 'Edit Akun Pengguna')

@section('content')
<div class="w-full max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6 animate-fade-in">

    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 gap-4 transition-all">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800 dark:text-white tracking-tight">Edit Akun Pengguna</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Perbarui hak akses lembaga, jabatan, status, atau reset kata sandi akun.</p>
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
            Terdapat kesalahan pada input formulir:
        </div>
        <ul class="text-xs text-red-600 dark:text-red-400 list-disc list-inside ml-5 space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('users.update', $user->id) }}" method="POST" autocomplete="off" class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 md:p-8 space-y-6">
        @csrf
        @method('PUT')

        <input type="text" name="fake_email" style="display:none;" aria-hidden="true">
        <input type="password" name="fake_password" style="display:none;" aria-hidden="true">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2 p-4 bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-slate-200 dark:border-slate-700/60 flex items-center gap-4 hover:border-blue-200 dark:hover:border-blue-800/50 transition-colors">
                @if($user->penduduk && !empty($user->penduduk->foto) && \Illuminate\Support\Facades\Storage::disk('private')->exists($user->penduduk->foto))
                    <img src="{{ route('penduduk.photo', $user->penduduk) }}" alt="Foto {{ e($user->name) }}" class="w-12 h-12 rounded-xl object-cover shrink-0 shadow-sm border border-slate-200 dark:border-slate-600">
                @else
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-white font-extrabold text-lg shadow-sm shrink-0 border border-blue-500 dark:border-indigo-500">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
                <div>
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Pemilik Akun (Master Kependudukan)</p>
                    <p class="text-base font-extrabold text-slate-800 dark:text-white mt-0.5">
                        {{ e($user->name) }}
                        <span class="text-xs font-mono font-normal text-slate-500 ml-1">(NIK: {{ e($user->penduduk->nik ?? $user->nik ?? 'Tidak diketahui') }})</span>
                    </p>
                </div>
            </div>

            <div class="group">
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-blue-500 group-focus-within:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    Alamat Email (Opsional)
                </label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" autocomplete="off" placeholder="contoh@email.com" class="w-full px-4 py-3 rounded-xl border @error('email') border-red-500 ring-1 ring-red-500 @else border-slate-300 dark:border-slate-600 @enderror bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-blue-500 dark:text-white text-sm transition">
                @error('email') <p class="text-red-500 text-xs mt-1.5 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div class="group">
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-blue-500 group-focus-within:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Status Akun <span class="text-red-500">*</span>
                </label>
                <select name="status_akun" required class="w-full px-4 py-3 rounded-xl border @error('status_akun') border-red-500 ring-1 ring-red-500 @else border-slate-300 dark:border-slate-600 @enderror bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-blue-500 dark:text-white text-sm transition cursor-pointer">
                    <option value="Aktif" {{ old('status_akun', $user->status_akun) == 'Aktif' ? 'selected' : '' }}>🟢 Aktif</option>
                    <option value="Nonaktif" {{ old('status_akun', $user->status_akun) == 'Nonaktif' ? 'selected' : '' }}>⚪ Nonaktif</option>
                    <option value="Suspend" {{ old('status_akun', $user->status_akun) == 'Suspend' ? 'selected' : '' }}>🔴 Suspend</option>
                </select>
                @error('status_akun') <p class="text-red-500 text-xs mt-1.5 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div class="group">
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-blue-500 group-focus-within:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    Kata Sandi Baru (Opsional)
                </label>
                <input type="password" name="password" autocomplete="new-password" minlength="8" placeholder="Kosongkan jika tidak ingin diubah" class="w-full px-4 py-3 rounded-xl border @error('password') border-red-500 ring-1 ring-red-500 @else border-slate-300 dark:border-slate-600 @enderror bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-blue-500 dark:text-white text-sm transition">
                @error('password') <p class="text-red-500 text-xs mt-1.5 font-semibold">{{ $message }}</p> @enderror
                <p class="text-xs text-slate-400 mt-1">Minimal 8 karakter jika diisi.</p>
            </div>

            <div class="group">
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-blue-500 group-focus-within:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    Konfirmasi Kata Sandi Baru
                </label>
                <input type="password" name="password_confirmation" autocomplete="new-password" minlength="8" placeholder="Ketik ulang kata sandi baru" class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-blue-500 dark:text-white text-sm transition">
            </div>

            <div class="group">
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-blue-500 group-focus-within:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    Group Lembaga <span class="text-red-500">*</span>
                </label>
                <select id="group_select" name="group_id" required class="w-full px-4 py-3 rounded-xl border @error('group_id') border-red-500 ring-1 ring-red-500 @else border-slate-300 dark:border-slate-600 @enderror bg-slate-50 dark:bg-slate-900 focus:ring-2 focus:ring-blue-500 dark:text-white text-sm transition cursor-pointer">
                    <option value="">-- Pilih Group / Unit Kerja --</option>
                    @foreach($groups as $g)
                        <option value="{{ $g->id }}" {{ (string) old('group_id', $user->group_id) === (string) $g->id ? 'selected' : '' }}>
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
            <button type="submit" class="px-8 py-3 bg-amber-600 hover:bg-amber-700 text-white rounded-xl font-bold shadow-md shadow-amber-500/20 transition-all flex items-center justify-center gap-2 focus:ring-2 focus:ring-amber-500 focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Simpan Perubahan Akun
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const groupsData = @json($groups);
        const oldRoleId = String("{{ old('role_id', $user->role_id) }}");

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

@extends('layouts.app')

@section('title', 'Tambah Data Penduduk')

@section('content')

<div class="w-full max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 animate-fade-in">

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white/80 dark:bg-slate-800/80 backdrop-blur-md p-6 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700">
        <div class="flex items-start sm:items-center gap-4">
            <div class="p-3.5 bg-blue-600 text-white rounded-2xl flex-shrink-0 shadow-lg shadow-blue-500/30">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
            </div>
            <div>
                <h2 class="text-xl sm:text-2xl font-extrabold text-slate-800 dark:text-white tracking-tight">Pendaftaran Penduduk Baru</h2>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">Lengkapi form sesuai dokumen resmi (KTP/KK). Form dengan tanda <span class="text-red-500 font-bold">*</span> wajib diisi.</p>
            </div>
        </div>
        <a href="{{ route('penduduk.index') }}" class="flex-shrink-0 px-5 py-2.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-600 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition-all text-sm font-bold flex items-center shadow-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg> Kembali
        </a>
    </div>

    @if ($errors->any())
    <div class="mb-6 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-2xl p-5 flex items-start shadow-sm">
        <div class="flex-shrink-0 mr-4 text-red-600 dark:text-red-400 mt-0.5"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg></div>
        <div>
            <h3 class="text-sm font-extrabold text-red-800 dark:text-red-400">Gagal menyimpan data! Periksa input berikut:</h3>
            <ul class="mt-2 text-sm text-red-700 dark:text-red-300 list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
    </div>
    @endif

    <form action="{{ route('penduduk.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden mb-6">
            <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50">
                <h3 class="font-extrabold text-slate-800 dark:text-white flex items-center text-lg"><div class="w-2.5 h-6 bg-blue-600 rounded-full mr-3 shadow-sm shadow-blue-500/50"></div> 1. Identitas Utama & Foto Pribadi</h3>
            </div>
            <div class="p-6 sm:p-8">

                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 border-b border-slate-100 dark:border-slate-700/50 pb-8 mb-8" x-data="{ photoPreview: '', updatePreview(event) { const file = event.target.files[0]; if(file) { this.photoPreview = URL.createObjectURL(file); } } }">
                    <div class="w-32 h-40 flex-shrink-0 rounded-2xl border-2 border-dashed border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900/50 flex items-center justify-center overflow-hidden shadow-inner relative group">
                        <template x-if="photoPreview"><img :src="photoPreview" class="w-full h-full object-cover transition-opacity duration-300"></template>
                        <template x-if="!photoPreview"><svg class="w-10 h-10 text-slate-400 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg></template>
                    </div>
                    <div class="flex-1 w-full text-center sm:text-left">
                        <label for="foto_create" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Upload Pas Foto Resmi</label>

                        <input type="file" id="foto_create" name="foto" @change="updatePreview" accept="image/jpeg, image/png, image/jpg" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900/30 dark:file:text-blue-400 transition cursor-pointer border border-slate-200 dark:border-slate-700 rounded-xl">
                        <p class="text-xs font-medium text-slate-500 mt-2">* Rasio 3x4 (Latar Belakang Bebas). Maksimal 2MB (JPG/PNG).</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label for="nik" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">NIK <span class="text-red-500">*</span></label>
                        <input type="text" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 16)" id="nik" name="nik" value="{{ old('nik', $penduduk->nik ?? '') }}" required minlength="16" maxlength="16" placeholder="16 Digit Angka NIK" class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition font-mono tracking-wider shadow-sm hover:border-blue-400">
                    </div>

                    <div>
                        <label for="no_kk" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">No. Kartu Keluarga <span class="text-red-500">*</span></label>
                        <input type="text" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 16)" id="no_kk" name="no_kk" value="{{ old('no_kk', $penduduk->no_kk ?? '') }}" required minlength="16" maxlength="16" placeholder="16 Digit Angka KK" class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition font-mono tracking-wider shadow-sm hover:border-blue-400">
                    </div>
                    <div>
                        <label for="nama_lengkap" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required placeholder="Sesuai e-KTP" class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition shadow-sm font-medium hover:border-blue-400">
                    </div>
                    <div>
                        <label for="tempat_lahir" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Tempat Lahir <span class="text-red-500">*</span></label>
                        <input type="text" id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir') }}" required placeholder="Contoh: Bekasi" class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition shadow-sm font-medium hover:border-blue-400">
                    </div>
                    <div>
                        <label for="tanggal_lahir" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Tanggal Lahir <span class="text-red-500">*</span></label>
                        <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white dark:[color-scheme:dark] focus:ring-2 focus:ring-blue-500 outline-none transition shadow-sm font-medium hover:border-blue-400">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="relative">
                            <label for="jenis_kelamin" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Jenis Kelamin <span class="text-red-500">*</span></label>
                            <select id="jenis_kelamin" name="jenis_kelamin" required class="w-full pl-4 pr-10 py-3 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white appearance-none focus:ring-2 focus:ring-blue-500 outline-none transition shadow-sm font-medium cursor-pointer hover:border-blue-400">
                                <option value="" disabled {{ old('jenis_kelamin') ? '' : 'selected' }}>Pilih...</option>
                                @foreach(\App\Enums\JenisKelamin::cases() as $jk)
                                    <option value="{{ $jk->value }}" {{ old('jenis_kelamin') == $jk->value ? 'selected' : '' }}>{{ $jk->value }}</option>
                                @endforeach
                            </select>
                            <svg class="w-5 h-5 absolute right-3 bottom-3.5 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg>
                        </div>
                        <div class="relative">
                            <label for="agama" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Agama <span class="text-red-500">*</span></label>
                            <select id="agama" name="agama" required class="w-full pl-4 pr-10 py-3 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white appearance-none focus:ring-2 focus:ring-blue-500 outline-none transition shadow-sm font-medium cursor-pointer hover:border-blue-400">
                                @foreach(\App\Enums\Agama::cases() as $agama)
                                    <option value="{{ $agama->value }}" {{ old('agama', \App\Enums\Agama::ISLAM->value) == $agama->value ? 'selected' : '' }}>{{ $agama->value }}</option>
                                @endforeach
                            </select>
                            <svg class="w-5 h-5 absolute right-3 bottom-3.5 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden mb-6">
            <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50">
                <h3 class="font-extrabold text-slate-800 dark:text-white flex items-center text-lg"><div class="w-2.5 h-6 bg-emerald-500 rounded-full mr-3 shadow-sm shadow-emerald-500/50"></div> 2. Data Keluarga, Pendidikan & Pekerjaan</h3>
            </div>
            <div class="p-6 sm:p-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="relative">
                    <label for="pendidikan" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Pendidikan Terakhir <span class="text-red-500">*</span></label>
                    <select id="pendidikan" name="pendidikan" required class="w-full pl-4 pr-10 py-3 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white appearance-none focus:ring-2 focus:ring-emerald-500 outline-none transition shadow-sm font-medium cursor-pointer hover:border-emerald-400">
                        <option value="" disabled {{ old('pendidikan') ? '' : 'selected' }}>Pilih Pendidikan</option>
                        @foreach(\App\Enums\Pendidikan::cases() as $pendidikan)
                            <option value="{{ $pendidikan->value }}" {{ old('pendidikan') == $pendidikan->value ? 'selected' : '' }}>{{ $pendidikan->value }}</option>
                        @endforeach
                    </select>
                    <svg class="w-5 h-5 absolute right-3 bottom-3.5 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg>
                </div>
                <div class="relative">
                    <label for="pekerjaan" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Pekerjaan <span class="text-red-500">*</span></label>
                    <select id="pekerjaan" name="pekerjaan" required class="w-full pl-4 pr-10 py-3 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white appearance-none focus:ring-2 focus:ring-emerald-500 outline-none transition shadow-sm font-medium cursor-pointer hover:border-emerald-400">
                        <option value="" disabled {{ old('pekerjaan') ? '' : 'selected' }}>Pilih Pekerjaan</option>
                        @foreach(\App\Enums\Pekerjaan::cases() as $pekerjaan)
                            <option value="{{ $pekerjaan->value }}" {{ old('pekerjaan') == $pekerjaan->value ? 'selected' : '' }}>{{ $pekerjaan->value }}</option>
                        @endforeach
                    </select>
                    <svg class="w-5 h-5 absolute right-3 bottom-3.5 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="relative">
                        <label for="golongan_darah" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Gol. Darah</label>
                        <select id="golongan_darah" name="golongan_darah" class="w-full pl-4 pr-10 py-3 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white appearance-none focus:ring-2 focus:ring-emerald-500 outline-none transition shadow-sm font-medium cursor-pointer hover:border-emerald-400">
                            @foreach(\App\Enums\GolonganDarah::cases() as $gd)
                                <option value="{{ $gd->value }}" {{ old('golongan_darah') == $gd->value ? 'selected' : '' }}>
                                    {{ $gd->value == 'Tidak Tahu' ? 'N/A' : $gd->value }}
                                </option>
                            @endforeach
                        </select>
                        <svg class="w-5 h-5 absolute right-3 bottom-3.5 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg>
                    </div>
                    <div class="relative">
                        <label for="kewarganegaraan" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Warga</label>
                        <select id="kewarganegaraan" name="kewarganegaraan" class="w-full pl-4 pr-10 py-3 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white appearance-none focus:ring-2 focus:ring-emerald-500 outline-none transition shadow-sm font-medium cursor-pointer hover:border-emerald-400">
                            <option value="WNI" {{ old('kewarganegaraan') == 'WNI' ? 'selected' : '' }}>WNI</option>
                            <option value="WNA" {{ old('kewarganegaraan') == 'WNA' ? 'selected' : '' }}>WNA</option>
                        </select>
                        <svg class="w-5 h-5 absolute right-3 bottom-3.5 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg>
                    </div>
                </div>
                <div class="relative">
                    <label for="status_perkawinan" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Status Perkawinan <span class="text-red-500">*</span></label>
                    <select id="status_perkawinan" name="status_perkawinan" required class="w-full pl-4 pr-10 py-3 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white appearance-none focus:ring-2 focus:ring-emerald-500 outline-none transition shadow-sm font-medium cursor-pointer hover:border-emerald-400">
                        <option value="" disabled {{ old('status_perkawinan') ? '' : 'selected' }}>Pilih Status</option>
                        @foreach(\App\Enums\StatusPerkawinan::cases() as $sp)
                            <option value="{{ $sp->value }}" {{ old('status_perkawinan') == $sp->value ? 'selected' : '' }}>{{ $sp->value }}</option>
                        @endforeach
                    </select>
                    <svg class="w-5 h-5 absolute right-3 bottom-3.5 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg>
                </div>
                <div class="relative">
                    <label for="status_hubungan_keluarga" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">SHDK (Hubungan Keluarga) <span class="text-red-500">*</span></label>
                    <select id="status_hubungan_keluarga" name="status_hubungan_keluarga" required class="w-full pl-4 pr-10 py-3 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white appearance-none focus:ring-2 focus:ring-emerald-500 outline-none transition shadow-sm font-medium cursor-pointer hover:border-emerald-400">
                        <option value="" disabled {{ old('status_hubungan_keluarga') ? '' : 'selected' }}>Pilih SHDK</option>
                            @foreach(\App\Enums\StatusHubunganKeluarga::cases() as $shdk)
                                <option value="{{ $shdk->value }}" {{ old('status_hubungan_keluarga') == $shdk->value ? 'selected' : '' }}>
                                    {{ $shdk->value }}
                                </option>
                            @endforeach
                    </select>
                    <svg class="w-5 h-5 absolute right-3 bottom-3.5 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="nama_ayah" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Nama Ayah <span class="text-red-500">*</span></label>
                        <input type="text" id="nama_ayah" name="nama_ayah" value="{{ old('nama_ayah') }}" required class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 outline-none transition shadow-sm font-medium hover:border-emerald-400">
                    </div>
                    <div>
                        <label for="nama_ibu" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Nama Ibu <span class="text-red-500">*</span></label>
                        <input type="text" id="nama_ibu" name="nama_ibu" value="{{ old('nama_ibu') }}" required class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500 outline-none transition shadow-sm font-medium hover:border-emerald-400">
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden mb-8">
            <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50">
                <h3 class="font-extrabold text-slate-800 dark:text-white flex items-center text-lg"><div class="w-2.5 h-6 bg-purple-600 rounded-full mr-3 shadow-sm shadow-purple-500/50"></div> 3. Alamat Domisili Terperinci</h3>
            </div>
            <div class="p-6 sm:p-8 grid grid-cols-1 md:grid-cols-12 gap-6">

                <div class="col-span-1 md:col-span-12">
                    <label for="alamat_lengkap" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Alamat Lengkap (Jalan/Blok) <span class="text-red-500">*</span></label>
                    <textarea id="alamat_lengkap" name="alamat_lengkap" rows="2" required placeholder="Contoh: Jl. Raya Ridogalih Blok A" class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-purple-500 outline-none transition shadow-sm font-medium hover:border-purple-400">{{ old('alamat_lengkap') }}</textarea>
                </div>

                <div class="col-span-1 md:col-span-3 relative">
                    <label for="dusun" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Dusun <span class="text-red-500">*</span></label>
                    <select id="dusun" name="dusun" required class="w-full pl-4 pr-10 py-3 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white appearance-none focus:ring-2 focus:ring-purple-500 outline-none transition shadow-sm font-medium cursor-pointer hover:border-purple-400">
                        <option value="" disabled {{ old('dusun') ? '' : 'selected' }}>Pilih Dusun</option>
                        @foreach(\App\Enums\Dusun::cases() as $dusun)
                            <option value="{{ $dusun->value }}" {{ old('dusun') == $dusun->value ? 'selected' : '' }}>
                                {{ $dusun->value }}
                            </option>
                        @endforeach
                    </select>
                    <svg class="w-5 h-5 absolute right-3 bottom-3.5 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg>
                </div>

                <div class="col-span-1 md:col-span-2 relative">
                    <label for="rt" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">RT <span class="text-red-500">*</span></label>
                    <select id="rt" name="rt" required class="w-full pl-4 pr-10 py-3 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white appearance-none focus:ring-2 focus:ring-purple-500 outline-none transition shadow-sm font-mono cursor-pointer hover:border-purple-400">
                        <option value="" disabled {{ old('rt') ? '' : 'selected' }}>RT</option>
                        @foreach(\App\Enums\Rt::cases() as $rt)
                            <option value="{{ $rt->value }}" {{ old('rt') == $rt->value ? 'selected' : '' }}>{{ str_pad($rt->value, 3, '0', STR_PAD_LEFT) }}</option>
                        @endforeach
                    </select>
                    <svg class="w-5 h-5 absolute right-3 bottom-3.5 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg>
                </div>

                <div class="col-span-1 md:col-span-2 relative">
                    <label for="rw" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">RW <span class="text-red-500">*</span></label>
                    <select id="rw" name="rw" required class="w-full pl-4 pr-10 py-3 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white appearance-none focus:ring-2 focus:ring-purple-500 outline-none transition shadow-sm font-mono cursor-pointer hover:border-purple-400">
                        <option value="" disabled {{ old('rw') ? '' : 'selected' }}>RW</option>
                        @foreach(\App\Enums\Rw::cases() as $rw)
                            <option value="{{ $rw->value }}" {{ old('rw') == $rw->value ? 'selected' : '' }}>{{ str_pad($rw->value, 3, '0', STR_PAD_LEFT) }}</option>
                        @endforeach
                    </select>
                    <svg class="w-5 h-5 absolute right-3 bottom-3.5 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg>
                </div>

                <div class="col-span-1 md:col-span-5">
                    <label for="kode_pos" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Kode Pos <span class="text-red-500">*</span></label>
                    <input type="text" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 5)" id="kode_pos" name="kode_pos" value="{{ old('kode_pos', $profilDesa->kode_pos ?? config('app.kode_pos_default', '17340')) }}" required maxlength="5" class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-purple-500 outline-none transition shadow-sm font-mono tracking-widest hover:border-purple-400">
                </div>

                <div class="col-span-1 md:col-span-3">
                    <label for="desa" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Desa <span class="text-red-500">*</span></label>
                    <input type="text" id="desa" name="desa" value="{{ old('desa', $profilDesa->nama_desa ?? config('app.desa_default')) }}" required class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-purple-500 outline-none transition shadow-sm font-medium hover:border-purple-400">
                </div>
                <div class="col-span-1 md:col-span-3">
                    <label for="kecamatan" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Kecamatan <span class="text-red-500">*</span></label>
                    <input type="text" id="kecamatan" name="kecamatan" value="{{ old('kecamatan', $profilDesa->kecamatan ?? config('app.kecamatan_default')) }}" required class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-purple-500 outline-none transition shadow-sm font-medium hover:border-purple-400">
                </div>
                <div class="col-span-1 md:col-span-3">
                    <label for="kabupaten" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Kabupaten <span class="text-red-500">*</span></label>
                    <input type="text" id="kabupaten" name="kabupaten" value="{{ old('kabupaten', $profilDesa->kabupaten ?? config('app.kabupaten_default')) }}" required class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-purple-500 outline-none transition shadow-sm font-medium hover:border-purple-400">
                </div>
                <div class="col-span-1 md:col-span-3">
                    <label for="provinsi" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Provinsi <span class="text-red-500">*</span></label>
                    <input type="text" id="provinsi" name="provinsi" value="{{ old('provinsi', $profilDesa->provinsi ?? config('app.provinsi_default')) }}" required class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-purple-500 outline-none transition shadow-sm font-medium hover:border-purple-400">
                </div>

                <div class="col-span-1 md:col-span-12 mt-2">
                    <label for="status_kependudukan" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Status Penduduk <span class="text-red-500">*</span></label>
                    <div class="relative w-full md:w-1/3">
                        <select id="status_kependudukan" name="status_kependudukan" required class="w-full pl-4 pr-10 py-3 rounded-xl border-2 border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white appearance-none focus:ring-2 focus:ring-purple-500 outline-none transition shadow-sm font-bold cursor-pointer hover:border-purple-400">
                            @foreach(\App\Enums\StatusKependudukan::cases() as $status)
                                <option value="{{ $status->value }}" {{ old('status_kependudukan', \App\Enums\StatusKependudukan::AKTIF->value) == $status->value ? 'selected' : '' }}>
                                    {{ $status->value }}
                                </option>
                            @endforeach
                        </select>
                        <svg class="w-5 h-5 absolute right-3 bottom-3.5 text-slate-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg>
                    </div>
                </div>

            </div>
        </div>

        <div class="sticky bottom-4 z-40 bg-white/90 dark:bg-slate-800/90 backdrop-blur-xl p-4 md:px-6 md:py-4 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-2xl shadow-slate-200/50 dark:shadow-black/50 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="hidden md:flex items-center text-sm font-medium text-slate-500">
                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Semua kolom kini dapat diubah sesuai dokumen resmi (KTP/KK).
            </div>
            <div class="flex gap-3 w-full md:w-auto">
                <button type="reset" class="px-6 py-3 rounded-xl border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 font-extrabold text-sm transition shadow-sm w-full md:w-auto">Reset Form</button>
                <button type="submit" class="flex-1 md:flex-none px-8 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-sm shadow-lg shadow-blue-500/30 transition transform hover:-translate-y-0.5 flex items-center justify-center w-full md:w-auto">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Simpan Data Penduduk
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

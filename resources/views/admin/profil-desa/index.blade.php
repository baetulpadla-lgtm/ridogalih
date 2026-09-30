@extends('layouts.app')

@section('title', 'Pengaturan Profil Desa')

@section('content')
<div class="w-full xl:max-w-[96%] mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6 relative">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800 dark:text-white flex items-center gap-2">
                <svg class="w-7 h-7 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                Identitas & Profil Pemerintahan Desa
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Kelola informasi utama kantor desa, logo resmi, dan pejabat penandatangan surat keluar.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 text-emerald-700 dark:text-emerald-400 rounded-xl font-medium flex items-center gap-3 shadow-sm">
            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 bg-red-50 dark:bg-red-900/30 border border-red-200 text-red-700 dark:text-red-400 rounded-xl text-sm shadow-sm">
            <span class="font-bold flex items-center gap-1.5 mb-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                Perhatian! Terdapat kesalahan input data:
            </span>
            <ul class="list-disc list-inside mt-1 pl-5 space-y-0.5">
                @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
        <form action="{{ route('admin.profil-desa.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 p-6 sm:p-8">

                <div class="col-span-1 space-y-6">
                    <div class="border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-2xl p-6 text-center flex flex-col items-center justify-center bg-slate-50 dark:bg-slate-900/50 hover:border-blue-400 transition-colors">

                        <div class="relative group mb-4">
                            @if($profil->logo_path)
                                <img id="logoPreview" src="{{ asset('storage/' . $profil->logo_path) }}" alt="Logo Desa" class="w-36 h-36 object-contain drop-shadow-md rounded-xl bg-white p-2 border border-slate-200 dark:border-slate-700">
                            @else
                                <div id="logoPlaceholder" class="w-36 h-36 bg-slate-200 dark:bg-slate-700 rounded-2xl flex items-center justify-center text-slate-400 shadow-inner">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <img id="logoPreview" src="" class="w-36 h-36 object-contain drop-shadow-md rounded-xl bg-white p-2 border border-slate-200 dark:border-slate-700 hidden">
                            @endif
                        </div>

                        @can('security.settings.update')
                        <h4 class="font-bold text-slate-800 dark:text-white text-sm">Logo Resmi Desa</h4>
                        <p class="text-xs text-slate-500 mb-4 mt-1 leading-normal">Format PNG / JPG transparan.<br>Ukuran maksimal 2MB.</p>

                        <label class="cursor-pointer px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-xs tracking-wide transition shadow-md shadow-blue-500/20 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            Pilih Logo Baru
                            <input type="file" name="logo" id="logoInput" class="hidden" accept="image/png, image/jpeg, image/jpg">
                        </label>
                        @endcan
                    </div>

                    <div class="bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-800/50 p-4 rounded-xl space-y-2">
                        <h5 class="text-xs font-extrabold text-indigo-900 dark:text-indigo-300 uppercase flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Pusat Otomasi Surat
                        </h5>
                        <p class="text-xs text-indigo-700 dark:text-indigo-400 leading-relaxed">
                            Informasi ini terhubung langsung secara dinamis ke <b>Kop Surat PDF</b>, QR Code tanda tangan Kepala Desa, dan metadata dokumen resmi E-Office.
                        </p>
                    </div>
                </div>

                <div class="col-span-1 lg:col-span-2 space-y-6">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Nama Desa <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_desa" value="{{ old('nama_desa', $profil->nama_desa) }}" required class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-xl text-sm bg-white dark:bg-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Kode Pos</label>
                            <input type="text" name="kode_pos" value="{{ old('kode_pos', $profil->kode_pos) }}" placeholder="Cth: 17330" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-xl text-sm bg-white dark:bg-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Kecamatan <span class="text-red-500">*</span></label>
                            <input type="text" name="kecamatan" value="{{ old('kecamatan', $profil->kecamatan) }}" required class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-xl text-sm bg-white dark:bg-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Kabupaten / Kota <span class="text-red-500">*</span></label>
                            <input type="text" name="kabupaten" value="{{ old('kabupaten', $profil->kabupaten) }}" required class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-xl text-sm bg-white dark:bg-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Alamat Lengkap Kantor Desa <span class="text-red-500">*</span></label>
                        <textarea name="alamat" rows="2" required class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-xl text-sm bg-white dark:bg-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition">{{ old('alamat', $profil->alamat) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-4 border-t border-slate-200 dark:border-slate-700">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Nama Kepala Desa (Kades) <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_kepala_desa" value="{{ old('nama_kepala_desa', $profil->nama_kepala_desa) }}" placeholder="Cth: H. Ahmad Supardi" required class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-xl text-sm bg-white dark:bg-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">NIP Kepala Desa</label>
                            <input type="text" name="nip_kepala_desa" value="{{ old('nip_kepala_desa', $profil->nip_kepala_desa) }}" placeholder="Kosongkan jika non-PNS" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-xl text-sm bg-white dark:bg-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">No. Telepon / WhatsApp Kantor</label>
                            <input type="text" name="telepon" value="{{ old('telepon', $profil->telepon) }}" placeholder="Cth: 081234567890" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-xl text-sm bg-white dark:bg-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Email Resmi Pemerintahan Desa</label>
                            <input type="email" name="email" value="{{ old('email', $profil->email) }}" placeholder="Cth: desa@ridogalih.id" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-xl text-sm bg-white dark:bg-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition">
                        </div>
                    </div>


                    @can('security.settings.update')
                    <div class="flex justify-end gap-3 pt-6 mt-6 border-t border-slate-200 dark:border-slate-700">
                        <button type="submit" class="px-8 py-3.5 bg-blue-600 text-white rounded-xl text-sm font-extrabold shadow-lg shadow-blue-500/25 hover:bg-blue-700 transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Simpan Perubahan Profil Desa
                        </button>
                    </div>
                    @endcan

                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('logoInput').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('logoPreview');
                preview.src = e.target.result;
                preview.classList.remove('hidden');

                const placeholder = document.getElementById('logoPlaceholder');
                if (placeholder) placeholder.classList.add('hidden');
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection

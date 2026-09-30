@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Tambah Jenis Surat</h2>
            <p class="text-sm text-gray-500 mt-1">Tambahkan format surat baru yang akan dilayani oleh desa.</p>
        </div>
        <a href="{{ route('jenis-surat.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-sm font-semibold transition">
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sm:p-8">
        <form action="{{ route('jenis-surat.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kode Surat <span class="text-red-500">*</span></label>
                    <input type="text" name="kode_surat" value="{{ old('kode_surat') }}" required placeholder="Cth: SKD"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('kode_surat') border-red-500 @enderror">
                    @error('kode_surat')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Surat <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_surat" value="{{ old('nama_surat') }}" required placeholder="Cth: Surat Keterangan Domisili"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('nama_surat') border-red-500 @enderror">
                    @error('nama_surat')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi / Keterangan Singkat</label>
                <textarea name="deskripsi" rows="3" placeholder="Jelaskan kegunaan surat ini..."
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">{{ old('deskripsi') }}</textarea>
            </div>

            <div class="mb-8">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Status Template <span class="text-red-500">*</span></label>
                <select name="is_active" required class="w-full md:w-1/2 px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>Aktif (Bisa digunakan)</option>
                    <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Nonaktif (Disembunyikan)</option>
                </select>
            </div>

            <div class="flex justify-end pt-4 border-t border-gray-100">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-bold shadow-sm transition">
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

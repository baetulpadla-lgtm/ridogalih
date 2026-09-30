<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Dokumen Elektronik - Desa Ridogalih</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4 font-sans">

    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl overflow-hidden border border-slate-200">

        <!-- HEADER BRANDING -->
        <div class="bg-slate-900 px-6 py-8 text-center relative overflow-hidden">
            <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
            <div class="relative z-10">
                <svg class="w-12 h-12 text-white mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                <h1 class="text-xl font-bold text-white tracking-wide">E-OFFICE DESA RIDOGALIH</h1>
                <p class="text-slate-400 text-sm mt-1">Sistem Verifikasi Dokumen Elektronik</p>
            </div>
        </div>

        <!-- KONTEN UTAMA VERIFIKASI -->
        <div class="p-6 sm:p-8">

            @if($status === 'valid' && isset($surat))
                <div class="flex flex-col items-center justify-center mb-6">
                    <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center mb-4 ring-4 ring-emerald-50">
                        <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h2 class="text-2xl font-extrabold text-slate-800 text-center">DOKUMEN VALID</h2>
                    <p class="text-emerald-600 font-semibold text-sm mt-1 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-200">Tanda Tangan Elektronik Sah</p>
                </div>

                <div class="space-y-4 bg-slate-50 p-5 rounded-2xl border border-slate-100">
                    <div>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Nomor Dokumen</p>
                        <p class="text-sm font-bold text-slate-900">{{ $surat->nomor_surat ?? '-' }}</p>
                    </div>
                    <div class="h-px w-full bg-slate-200"></div>
                    <div>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Jenis Surat</p>
                        <p class="text-sm font-semibold text-slate-800">{{ $surat->jenis_surat?->nama_surat ?? '-' }}</p>
                    </div>
                    <div class="h-px w-full bg-slate-200"></div>
                    <div>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Dikeluarkan Atas Nama</p>
                        <p class="text-sm font-semibold text-slate-800">{{ $surat->penduduk?->nama_lengkap ?? '-' }}</p>
                        <p class="text-xs text-slate-500 font-mono">
                            NIK:
                            @php
                                $nik = $surat->penduduk?->nik ?? '';
                                echo strlen($nik) >= 6 ? substr($nik, 0, 6) . '**********' : '**************';
                            @endphp
                        </p>
                    </div>
                    <div class="h-px w-full bg-slate-200"></div>
                    <div>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Tanggal Pengesahan</p>
                        <p class="text-sm font-semibold text-slate-800">
                            {{ $surat->updated_at ? \Carbon\Carbon::parse($surat->updated_at)->translatedFormat('l, d F Y - H:i') . ' WIB' : '-' }}
                        </p>
                    </div>
                </div>

            @else
                <div class="flex flex-col items-center justify-center mb-6 py-4">
                    <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mb-4 ring-4 ring-red-50">
                        <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </div>
                    <h2 class="text-xl font-extrabold text-slate-800 text-center uppercase tracking-wide">{{ str_replace('_', ' ', $status ?? 'Gagal') }}</h2>
                    <p class="text-red-600 font-semibold text-sm mt-3 text-center bg-red-50 p-3 rounded-xl border border-red-200 leading-relaxed">{{ $pesan ?? 'Verifikasi dokumen gagal dilakukan.' }}</p>
                </div>
            @endif

        </div>

        <!-- FOOTER -->
        <div class="bg-slate-50 p-4 text-center border-t border-slate-200">
            <p class="text-xs text-slate-500 leading-relaxed">
                Dokumen resmi ini dilindungi oleh Kriptografi SHA-256.<br>
                &copy; {{ date('Y') }} Pemerintah Desa Ridogalih.
            </p>
        </div>
    </div>

</body>
</html>

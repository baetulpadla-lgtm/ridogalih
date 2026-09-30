<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat {{ $suratKeluar->jenis_surat?->nama_surat ?? 'Desa' }}</title>
    <style>
        @page { size: A4; margin: 1.5cm 2cm 1.5cm 2cm; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; line-height: 1.5; color: #000; margin: 0; padding: 0; }
        table.kop-surat { width: 100%; border-bottom: 3.5px double #000; padding-bottom: 6px; margin-bottom: 20px; }
        table.kop-surat td { vertical-align: middle; }
        .teks-kop { text-align: center; }
        .teks-kop h2 { margin: 0; font-size: 13pt; font-weight: normal; text-transform: uppercase; letter-spacing: 0.5px; }
        .teks-kop h1 { margin: 2px 0; font-size: 16pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
        .teks-kop p { margin: 0; font-size: 10pt; }
        .judul-surat { text-align: center; margin-bottom: 20px; }
        .judul-surat h3 { margin: 0; font-size: 13pt; text-decoration: underline; text-transform: uppercase; letter-spacing: 0.5px; }
        .judul-surat p { margin: 3px 0 0 0; font-size: 11pt; }
        .content { margin-bottom: 20px; text-align: justify; }
        .content p { margin: 0 0 10px 0; text-indent: 40px; }
        .content p.no-indent { text-indent: 0; }
        table.biodata { width: 92%; margin-left: 35px; margin-bottom: 12px; margin-top: 5px; border-collapse: collapse; }
        table.biodata td { padding: 3px 0; vertical-align: top; font-size: 11.5pt; }
        table.biodata td.label { width: 28%; }
        table.biodata td.titik { width: 3%; text-align: center; }
        table.ttd-table { width: 100%; margin-top: 20px; page-break-inside: avoid; border-collapse: collapse; }
        table.ttd-table td.kolom-ttd { width: 50%; vertical-align: top; padding: 0 10px; text-align: center; }
        .nama-terang { font-weight: bold; text-decoration: underline; text-transform: uppercase; }
    </style>
</head>
<body>
    @php
        $logoBase64 = null;
        if(isset($profil->logo_path) && file_exists(storage_path('app/public/' . $profil->logo_path))) {
            $path = storage_path('app/public/' . $profil->logo_path);
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }
    @endphp

    <table class="kop-surat">
        <tr>
            <td width="15%" style="text-align: left;">
                @if($logoBase64)
                    <img src="{{ $logoBase64 }}" style="max-width: 65px; max-height: 75px; display: block;" alt="Logo">
                @endif
            </td>
            <td width="70%" class="teks-kop">
                <h2>Pemerintah {{ $profil->kabupaten ?? '[KABUPATEN]' }}</h2>
                <h2>Kecamatan {{ $profil->kecamatan ?? '[KECAMATAN]' }}</h2>
                <h1>{{ $profil->nama_desa ?? '[NAMA DESA]' }}</h1>
                <p>
                    {{ $profil->alamat ?? '[ALAMAT]' }}
                    {{ isset($profil->kode_pos) ? ' - Kode Pos: '.$profil->kode_pos : '' }}
                </p>
                @if(isset($profil->telepon) || isset($profil->email))
                <p>Telp: {{ $profil->telepon ?? '-' }} | Email: {{ $profil->email ?? '-' }}</p>
                @endif
            </td>
            <td width="15%">&nbsp;</td>
        </tr>
    </table>

    <div class="judul-surat">
        <h3>{{ strtoupper($suratKeluar->jenis_surat?->nama_surat ?? 'SURAT KETERANGAN') }}</h3>
        <p>Nomor: {{ $suratKeluar->nomor_surat ?? '......./...../...../'.date('Y') }}</p>
    </div>

    <div class="content">
        <p class="no-indent">Yang bertanda tangan di bawah ini, Kepala {{ $profil->nama_desa ?? '[NAMA DESA]' }}, Kecamatan {{ $profil->kecamatan ?? '[KECAMATAN]' }}, {{ $profil->kabupaten ?? '[KABUPATEN]' }}, menerangkan dengan sebenarnya bahwa:</p>
        <table class="biodata">
            <tr><td class="label">Nama Lengkap</td><td class="titik">:</td><td><strong>{{ $suratKeluar->penduduk?->nama_lengkap ?? '-' }}</strong></td></tr>
            <tr><td class="label">NIK / No. KTP</td><td class="titik">:</td><td>{{ $suratKeluar->penduduk?->nik ?? '-' }}</td></tr>
            <tr><td class="label">Jenis Kelamin</td><td class="titik">:</td><td>{{ $suratKeluar->penduduk?->jenis_kelamin ?? '-' }}</td></tr>
            <tr><td class="label">Agama</td><td class="titik">:</td><td>{{ $suratKeluar->penduduk?->agama ?? '-' }}</td></tr>
            <tr><td class="label">Alamat Lengkap</td><td class="titik">:</td><td>{{ $suratKeluar->penduduk?->alamat_lengkap ?? '-' }}</td></tr>
        </table>
        <p>Orang tersebut di atas adalah benar-benar warga domisili resmi penduduk {{ $profil->nama_desa ?? '[NAMA DESA]' }}. Surat keterangan ini dikeluarkan untuk keperluan: <strong>{{ $suratKeluar->keperluan ?? '-' }}</strong>.</p>
        <p>Demikian surat keterangan ini dibuat dengan sebenarnya dan untuk dapat dipergunakan sebagaimana mestinya.</p>
    </div>

    <table class="ttd-table">
        <tr>
            <td class="kolom-ttd">
                <table style="width: 100%; border-collapse: collapse; text-align: center;">
                    <tr><td style="height: 24px;">&nbsp;</td></tr>
                    <tr><td style="height: 24px; font-weight: normal;">Pemohon,</td></tr>
                    <tr><td style="height: 75px;">&nbsp;</td></tr>
                    <tr>
                        <td style="height: 24px; vertical-align: bottom;">
                            <span class="nama-terang">{{ $suratKeluar->penduduk?->nama_lengkap ?? '________________________' }}</span>
                        </td>
                    </tr>
                    <tr><td style="height: 25px;">&nbsp;</td></tr>
                </table>
            </td>
            <td class="kolom-ttd">
                <table style="width: 100%; border-collapse: collapse; text-align: center;">
                    <tr><td style="height: 24px;">{{ ucwords(strtolower(str_replace('Desa ', '', $profil->nama_desa ?? '[KOTA]'))) }}, {{ isset($suratKeluar->updated_at) ? \Carbon\Carbon::parse($suratKeluar->updated_at)->translatedFormat('d F Y') : date('d F Y') }}</td></tr>
                    <tr><td style="height: 24px; font-weight: normal;">Kepala {{ $profil->nama_desa ?? '[NAMA DESA]' }}</td></tr>
                    <tr>
                        <td style="height: 75px; vertical-align: middle;">
                            @if(isset($suratKeluar->qr_code_path) && \Illuminate\Support\Facades\Storage::disk('private')->exists($suratKeluar->qr_code_path))
                                @php
                                    $path = storage_path('app/private/' . $suratKeluar->qr_code_path);
                                    $type = pathinfo($path, PATHINFO_EXTENSION);
                                    $data = file_get_contents($path);
                                    $qrBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                                @endphp
                                <img src="{{ $qrBase64 }}" width="65" height="65" alt="TTE QR Code" style="display: block; margin: 0 auto;">
                            @else
                                @php
                                    $verificationUrl = route('validasi.surat', $suratKeluar->uuid ?? 'error');
                                    $qrCodeSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(90)->margin(1)->errorCorrection('H')->generate($verificationUrl);
                                    $qrFallbackBase64 = 'data:image/svg+xml;base64,' . base64_encode($qrCodeSvg);
                                @endphp
                                <img src="{{ $qrFallbackBase64 }}" width="65" height="65" alt="QR Code TTE" style="display: block; margin: 0 auto;">
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="height: 24px; vertical-align: bottom;">
                            <span class="nama-terang">{{ $profil->nama_kepala_desa ?? '________________________' }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="height: 25px; vertical-align: top;">
                            <div style="font-size: 7.5pt; color: #333; line-height: 1.1; margin-top: 2px;">
                                Dokumen ini ditandatangani secara elektronik.<br>Scan QR Code untuk verifikasi.
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

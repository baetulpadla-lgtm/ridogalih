<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penduduk - {{ $profilDesa->nama_desa ?? config('app.desa_default', 'Ridogalih') }}</title>
    <style>
        body { font-family: sans-serif; font-size: 9px; color: #1e293b; }
        h2 { text-align: center; margin-bottom: 2px; text-transform: uppercase; font-size: 14px; }
        p { text-align: center; font-size: 9px; margin-bottom: 12px; color: #64748b; }
        table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        th, td { border: 1px solid #cbd5e1; padding: 5px 6px; text-align: left; vertical-align: middle; }
        th { background-color: #f1f5f9; font-weight: bold; text-align: center; color: #334155; font-size: 9px; text-transform: uppercase; }
        .text-center { text-align: center; }
        .sub-text { font-size: 8px; color: #64748b; }
        .footer-section { margin-top: 25px; width: 100%; page-break-inside: avoid; }
        .signature-box { float: right; text-align: center; width: 210px; }
        .signature-box p { margin: 2px 0; font-size: 9px; color: #1e293b; text-align: center; }
        .qr-code { width: 70px; height: 70px; margin: 4px auto; display: block; border: 1px solid #cbd5e1; padding: 2px; background: #fff; border-radius: 4px; }
        .clear { clear: both; }
    </style>
</head>
<body>
    <h2>LAPORAN DATA PENDUDUK {{ strtoupper($profilDesa->nama_desa ?? config('app.desa_default', 'Ridogalih')) }}</h2>
    <p>Dicetak pada: {{ \Carbon\Carbon::now()->setTimezone('Asia/Jakarta')->translatedFormat('d F Y H:i') }} WIB</p>

    <table>
        <thead>
            <tr>
                <th style="width: 20px;">No</th>
                <th>NIK / No. KK</th>
                <th>Nama Lengkap</th>
                <th>Tempat, Tgl Lahir</th>
                <th style="width: 25px;">L/P</th>
                <th>Agama</th>
                <th>Pendidikan & Pekerjaan</th>
                <th>Status Kawin</th>
                <th>Alamat Domisili</th>
                <th style="width: 45px;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($penduduk ?? [] as $index => $p)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>
                    <strong>{{ $p->nik ?? '-' }}</strong><br>
                    <span class="sub-text">KK: {{ $p->no_kk ?? '-' }}</span>
                </td>
                <td><strong>{{ $p->nama_lengkap ?? '-' }}</strong></td>
                <td>
                    {{ $p->tempat_lahir ?? '-' }}<br>
                    <span class="sub-text">{{ isset($p->tanggal_lahir) ? \Carbon\Carbon::parse($p->tanggal_lahir)->format('d-m-Y') : '-' }}</span>
                </td>
                <td class="text-center">{{ $p->jenis_kelamin?->value === \App\Enums\JenisKelamin::LAKI_LAKI->value ? 'L' : 'P' }}</td>
                <td>{{ $p->agama?->value ?? '-' }}</td>
                <td>
                    {{ $p->pendidikan?->value ?? '-' }}<br>
                    <span class="sub-text">{{ $p->pekerjaan?->value ?? '-' }}</span>
                </td>
                <td>{{ $p->status_perkawinan?->value ?? '-' }}</td>
                <td>
                    {{ $p->alamat_lengkap ?? '-' }}<br>
                    <span class="sub-text">Dusun {{ $p->dusun?->value ?? '-' }} (RT {{ str_pad($p->rt?->value ?? 0, 3, '0', STR_PAD_LEFT) }}/RW {{ str_pad($p->rw?->value ?? 0, 3, '0', STR_PAD_LEFT) }})</span>
                </td>
                <td class="text-center"><strong>{{ $p->status_kependudukan?->value ?? '-' }}</strong></td>
            </tr>
            @empty
            <tr><td colspan="10" class="text-center" style="padding:15px;">Belum ada data penduduk.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer-section">
        <div class="signature-box">
            <p>{{ ucwords(strtolower(str_replace('Desa ', '', $profilDesa->nama_desa ?? config('app.desa_default', 'Ridogalih')))) }}, {{ \Carbon\Carbon::now()->setTimezone('Asia/Jakarta')->translatedFormat('d F Y') }}</p>
            <p><strong>Kepala {{ $profilDesa->nama_desa ?? config('app.desa_default', 'Ridogalih') }}</strong></p>
            @php
                $totalWarga = isset($penduduk) ? $penduduk->count() : 0;
                $qrDesa = strtoupper($profilDesa->nama_desa ?? config('app.desa_default', 'Ridogalih'));
                $qrData = 'TTE RESMI PEMDES ' . $qrDesa . ' - Rekapitulasi - Total: ' . $totalWarga . ' Warga';
                $qrCodeSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(70)->margin(0)->generate($qrData);
                $qrBase64 = base64_encode($qrCodeSvg);
            @endphp
            <img class="qr-code" src="data:image/svg+xml;base64,{!! $qrBase64 !!}" alt="QR Code TTE">
            <p style="margin-top: 2px;"><u><b>{{ $profilDesa->nama_kepala_desa ?? 'NAMA KEPALA DESA' }}</b></u></p>
            <p style="margin-top: 1px; font-size: 8.5px;">NIP. {{ $profilDesa->nip_kepala_desa ?? '-' }}</p>
        </div>
        <div class="clear"></div>
    </div>
</body>
</html>

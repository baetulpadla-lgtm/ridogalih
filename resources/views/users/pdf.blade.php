<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Akun Pengguna</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Times+New+Roman&family=Inter:wght@400;600;700&display=swap');
        body { font-family: 'Times New Roman', Times, serif; font-size: 11pt; color: #000; margin: 0; padding: 10px; background: #ffffff; }
        .header { text-align: center; border-bottom: 4px double #000000; padding-bottom: 8px; margin-bottom: 20px; }
        .header h3 { margin: 0; font-size: 14pt; font-weight: bold; letter-spacing: 0.5px; text-transform: uppercase; }
        .header h2 { margin: 2px 0; font-size: 16pt; font-weight: bold; text-transform: uppercase; }
        .header p { margin: 2px 0; font-size: 10pt; font-style: italic; color: #333; }
        .report-title { text-align: center; margin-bottom: 15px; }
        .report-title h4 { margin: 0; font-size: 12pt; text-transform: uppercase; text-decoration: underline; font-weight: bold; }
        .report-title p { margin: 4px 0 0 0; font-size: 10pt; }
        table { width: 100%; border-collapse: collapse; margin-top: 5px; margin-bottom: 25px; }
        th, td { border: 1px solid #222; padding: 6px 8px; vertical-align: middle; }
        th { background-color: #e2e8f0; font-family: 'Inter', sans-serif; font-size: 9.5pt; font-weight: 700; text-align: center; text-transform: uppercase; }
        td { font-size: 10.5pt; }
        tr:nth-child(even) { background-color: #f8fafc; }
        .signature-section { width: 100%; page-break-inside: avoid; margin-top: 20px; }
        .signature-box { float: right; text-align: center; width: 260px; font-family: 'Times New Roman', Times, serif; }
        .signature-box .date { margin-bottom: 5px; font-size: 11pt; }
        .signature-box .title-jabatan { font-weight: bold; margin-bottom: 5px; text-transform: uppercase; }
        .qr-container { margin: 10px auto; display: inline-block; padding: 3px; background: #fff; border: 1px solid #cbd5e1; }
        .qr-container svg { width: 65px !important; height: 65px !important; display: block; }
        .sign-name { font-weight: bold; text-decoration: underline; font-size: 11pt; margin-top: 2px; text-transform: uppercase; }
        .clear { clear: both; }
        @media print {
            @page { size: A4 landscape; margin: 15mm; }
            body { padding: 0; }
            th { background-color: #cbd5e1 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            tr:nth-child(even) { background-color: #f1f5f9 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h3>PEMERINTAH {{ strtoupper($profil->kabupaten ?? '[KABUPATEN BELUM DIATUR]') }}</h3>
        <h3>KECAMATAN {{ strtoupper($profil->kecamatan ?? '[KECAMATAN BELUM DIATUR]') }}</h3>
        <h2>PEMERINTAH {{ strtoupper($profil->nama_desa ?? '[NAMA DESA BELUM DIATUR]') }}</h2>
        <p><i>{{ $profil->alamat ?? '[ALAMAT KANTOR BELUM DIATUR]' }}</i></p>
    </div>

    <div class="report-title">
        <h4>LAPORAN REKAPITULASI AKUN PENGGUNA E-OFFICE</h4>
        <p>Periode Cetak: {{ date('d F Y / H:i:s') }} WIB</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 4%;">No</th>
                <th style="width: 16%;">NIK Pengguna</th>
                <th style="width: 22%;">Nama Lengkap</th>
                <th style="width: 16%;">Alamat Email</th>
                <th style="width: 15%;">Group Lembaga</th>
                <th style="width: 15%;">Role / Jabatan</th>
                <th style="width: 12%;">Status Akun</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users ?? [] as $index => $u)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td style="font-family: monospace; text-align: center;">{{ e($u->penduduk?->nik ?? $u->nik ?? '-') }}</td>
                <td><strong>{{ e($u->name ?? '-') }}</strong></td>
                <td>{{ e($u->email ?? '-') }}</td>
                <td>{{ e($u->group?->name ?? '-') }}</td>
                <td>{{ e($u->role?->name ?? '-') }}</td>
                <td style="text-align: center; font-weight: bold;">{{ e($u->status_akun ?? '-') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 20px; font-style: italic; color: #666;">
                    Belum ada data akun pengguna yang terdaftar di sistem.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="signature-section">
        <div class="signature-box">
            <div class="date">{{ ucwords(strtolower(str_replace('Desa ', '', $profil->nama_desa ?? '[KOTA]'))) }}, {{ date('d F Y') }}</div>
            <div class="title-jabatan">Kepala {{ $profil->nama_desa ?? '[NAMA DESA]' }}</div>
            <div class="qr-container">
                @php
                    $kadesName = $profil->nama_kepala_desa ?? '[NAMA KEPALA DESA]';
                    $qrText = 'Dokumen Sah E-Office - Disetujui Oleh Kades: ' . $kadesName;
                @endphp
                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(70)->margin(0)->generate($qrText) !!}
            </div>
            <div class="sign-name">{{ e($kadesName) }}</div>
        </div>
        <div class="clear"></div>
    </div>
</body>
</html>

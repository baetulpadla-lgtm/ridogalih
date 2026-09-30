<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Biodata - {{ $penduduk->nama_lengkap ?? 'Penduduk' }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');
        @page { size: A4; margin: 10mm 12mm; }
        * { box-sizing: border-box; }
        body { font-family: 'Inter', system-ui, sans-serif; color: #0f172a; background: #ffffff; margin: 0; padding: 0; font-size: 10px; line-height: 1.45; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        .sheet { width: 100%; max-width: 210mm; margin: 0 auto; }
        .doc-header { background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #fff; padding: 14px 20px; border-radius: 12px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .doc-header .left h4 { margin: 0; font-size: 8.5px; text-transform: uppercase; letter-spacing: 1.5px; color: #94a3b8; font-weight: 600; }
        .doc-header .left h2 { margin: 2px 0 0 0; font-size: 15px; font-weight: 800; letter-spacing: 0.5px; color: #f8fafc; }
        .doc-header .right { text-align: right; font-size: 8.5px; color: #cbd5e1; }
        .doc-header .right span { display: block; font-weight: 700; color: #38bdf8; font-size: 9px; }
        .main-grid { display: flex; gap: 14px; }
        .sidebar { width: 190px; flex-shrink: 0; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px 12px; text-align: center; }
        .photo-box { width: 110px; height: 145px; border-radius: 8px; border: 2px solid #cbd5e1; margin: 0 auto 10px auto; overflow: hidden; background: #e2e8f0; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .photo-box img { width: 100%; height: 100%; object-fit: cover; }
        .photo-placeholder { color: #64748b; font-size: 8px; font-weight: 600; text-align: center; padding: 10px; }
        .status-badge { display: inline-block; padding: 3px 10px; font-size: 8px; font-weight: 700; text-transform: uppercase; border-radius: 20px; margin-bottom: 10px; }
        .status-active { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .status-inactive { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .id-card-box { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 6px 8px; margin-top: 6px; text-align: left; }
        .id-card-box label { font-size: 7.5px; color: #64748b; font-weight: 700; text-transform: uppercase; display: block; }
        .id-card-box val { font-size: 10px; font-family: monospace; font-weight: 800; color: #0f172a; display: block; margin-top: 1px; }
        .content-area { flex: 1; display: flex; flex-direction: column; gap: 8px; }
        .card-section { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 10px 14px; box-shadow: 0 1px 2px rgba(0,0,0,0.02); }
        .card-title { font-size: 9.5px; font-weight: 800; color: #1e293b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; display: flex; align-items: center; gap: 6px; border-bottom: 1px solid #f1f5f9; padding-bottom: 4px; }
        .card-title span.dot { width: 5px; height: 5px; background: #2563eb; border-radius: 50%; display: inline-block; }
        .fields-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 6px 12px; }
        .fields-grid.full { grid-template-columns: 1fr; }
        .field-item label { display: block; font-size: 7.5px; color: #64748b; text-transform: uppercase; font-weight: 700; margin-bottom: 1px; }
        .field-item span { font-size: 9.5px; color: #0f172a; font-weight: 600; }
        .footer-section { margin-top: 10px; display: flex; justify-content: space-between; align-items: flex-end; page-break-inside: avoid; border-top: 1px dashed #cbd5e1; padding-top: 8px; }
        .footer-note { font-size: 8px; color: #64748b; max-width: 320px; line-height: 1.4; }
        .signature-container { display: flex; justify-content: flex-end; width: 100%; }
        .signature-box { text-align: center; width: 220px; }
        .signature-box p { margin: 1px 0; font-size: 9px; }
        .qr-code { width: 65px; height: 65px; margin: 3px auto; display: block; border: 1px solid #cbd5e1; padding: 2px; border-radius: 6px; background: #fff; }
    </style>
</head>
<body onload="window.print()">
    <div class="sheet">
        <div class="doc-header">
            <div class="left">
                <h4>Pemerintah {{ $profilDesa->kabupaten ?? config('app.kabupaten_default', 'Bekasi') }} &bull; Kecamatan {{ $profilDesa->kecamatan ?? config('app.kecamatan_default', 'Cibarusah') }}</h4>
                <h2>E-OFFICE {{ strtoupper($profilDesa->nama_desa ?? config('app.desa_default', 'Ridogalih')) }}</h2>
            </div>
            <div class="right">
                <span>DOKUMEN KEPENDUDUKAN</span>
                ID Ref: #{{ isset($penduduk) ? str_pad($penduduk->id, 6, '0', STR_PAD_LEFT) : '000000' }}
            </div>
        </div>

        <div class="main-grid">
            <div class="sidebar">
                <div class="photo-box">
                    @if(!empty($penduduk->foto))
                        <img src="{{ route('penduduk.photo', $penduduk) }}" alt="Foto Penduduk">
                    @else
                        <div class="photo-placeholder">
                            <span>PAS FOTO</span>
                        </div>
                    @endif
                </div>
                <div>
                    @php
                        $statusEnumVal = $penduduk->status_kependudukan?->value ?? $penduduk->status_kependudukan ?? \App\Enums\StatusKependudukan::AKTIF->value;
                    @endphp
                    <span class="status-badge {{ $statusEnumVal === \App\Enums\StatusKependudukan::AKTIF->value ? 'status-active' : 'status-inactive' }}">
                        {{ $statusEnumVal }}
                    </span>
                </div>
                <div class="id-card-box">
                    <label>Nomor Induk Kependudukan</label>
                    <val style="color: #2563eb;">{{ $penduduk->nik ?? '-' }}</val>
                </div>
                <div class="id-card-box" style="margin-top: 5px;">
                    <label>Nomor Kartu Keluarga</label>
                    <val>{{ $penduduk->no_kk ?? '-' }}</val>
                </div>
            </div>

            <div class="content-area">
                <div class="card-section" style="padding: 8px 14px;">
                    <div style="font-size: 7.5px; font-weight: 700; color: #2563eb; text-transform: uppercase; letter-spacing: 1px;">Nama Lengkap Penduduk</div>
                    <div style="font-size: 14px; font-weight: 800; color: #0f172a; margin-top: 1px;">{{ $penduduk->nama_lengkap ?? '-' }}</div>
                </div>
                <div class="card-section">
                    <div class="card-title"><span class="dot"></span> Informasi Pribadi & Keluarga</div>
                    <div class="fields-grid">
                        <div class="field-item">
                            <label>Tempat, Tanggal Lahir</label>
                            <span>{{ $penduduk->tempat_lahir ?? '-' }}, {{ isset($penduduk->tanggal_lahir) ? \Carbon\Carbon::parse($penduduk->tanggal_lahir)->translatedFormat('d F Y') : '-' }}</span>
                        </div>
                        <div class="field-item">
                            <label>Jenis Kelamin & Agama</label>
                            <span>{{ $penduduk->jenis_kelamin?->value ?? $penduduk->jenis_kelamin ?? '-' }} &bull; {{ $penduduk->agama?->value ?? $penduduk->agama ?? '-' }}</span>
                        </div>
                        <div class="field-item">
                            <label>Status Perkawinan</label>
                            <span>{{ $penduduk->status_perkawinan?->value ?? $penduduk->status_perkawinan ?? '-' }}</span>
                        </div>
                        <div class="field-item">
                            <label>Hubungan Keluarga</label>
                            <span>{{ $penduduk->status_hubungan_keluarga?->value ?? $penduduk->status_hubungan_keluarga ?? '-' }}</span>
                        </div>
                        <div class="field-item">
                            <label>Orang Tua</label>
                            <span>A: {{ $penduduk->nama_ayah ?? '-' }} / I: {{ $penduduk->nama_ibu ?? '-' }}</span>
                        </div>
                    </div>
                </div>
                <div class="card-section">
                    <div class="card-title"><span class="dot"></span> Alamat Domisili</div>
                    <div class="fields-grid full" style="margin-bottom: 4px;">
                        <div class="field-item">
                            <label>Jalan / Detail</label>
                            <span>{{ $penduduk->alamat_lengkap ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="fields-grid">
                        <div class="field-item">
                            <label>RT / RW / Dusun</label>
                            <span>RT {{ str_pad($penduduk->rt?->value ?? $penduduk->rt ?? 0, 3, '0', STR_PAD_LEFT) }} / RW {{ str_pad($penduduk->rw?->value ?? $penduduk->rw ?? 0, 3, '0', STR_PAD_LEFT) }} &bull; Dusun {{ $penduduk->dusun?->value ?? $penduduk->dusun ?? '-' }}</span>
                        </div>
                        <div class="field-item">
                            <label>Wilayah</label>
                            <span>Desa {{ $penduduk->desa ?? $profilDesa->nama_desa ?? config('app.desa_default', 'Ridogalih') }}, Kec. {{ $penduduk->kecamatan ?? $profilDesa->kecamatan ?? config('app.kecamatan_default', 'Cibarusah') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-section">
            <div class="footer-note">
                <strong>Catatan Sistem:</strong> Dokumen ini digenerate oleh sistem E-Office {{ $profilDesa->nama_desa ?? config('app.desa_default', 'Ridogalih') }} dan sah tanpa stempel basah.
            </div>
            <div class="signature-container">
                <div class="signature-box">
                    <p>{{ ucwords(strtolower(str_replace('Desa ', '', $profilDesa->nama_desa ?? config('app.desa_default', 'Ridogalih')))) }}, {{ \Carbon\Carbon::now()->setTimezone('Asia/Jakarta')->translatedFormat('d F Y') }}</p>
                    <p><strong>Kepala {{ $profilDesa->nama_desa ?? config('app.desa_default', 'Ridogalih') }}</strong></p>
                    @php
                        $qrDesa = strtoupper($profilDesa->nama_desa ?? config('app.desa_default', 'Ridogalih'));
                        $qrData = 'TTE RESMI PEMDES ' . $qrDesa . ' - NIK: ' . ($penduduk->nik ?? '0') . ' - Nama: ' . ($penduduk->nama_lengkap ?? '-');
                        $qrCodeSvg = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(65)->margin(0)->generate($qrData);
                        $qrBase64 = base64_encode($qrCodeSvg);
                    @endphp
                    <img class="qr-code" src="data:image/svg+xml;base64,{!! $qrBase64 !!}" alt="QR Code TTE">
                    <p style="margin-top: 2px;"><u><b>{{ $profilDesa->nama_kepala_desa ?? 'NAMA KEPALA DESA' }}</b></u></p>
                    <p style="margin-top: 1px; font-size: 8.5px;">NIP. {{ $profilDesa->nip_kepala_desa ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

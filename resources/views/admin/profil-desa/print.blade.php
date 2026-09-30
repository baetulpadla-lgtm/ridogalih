<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil {{ $profil->nama_desa ?? 'Desa' }}</title>
    <style>
        @page { size: A4 landscape; margin: 0; }
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background: #f8fafc; color: #1e293b; margin: 0; padding: 0; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        .page { width: 297mm; height: 210mm; box-sizing: border-box; padding: 20mm; background: #ffffff; position: relative; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between; margin: 0 auto; }
        .bg-shape { position: absolute; top: 0; right: 0; width: 45%; height: 100%; background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); z-index: 1; clip-path: polygon(15% 0%, 100% 0%, 100% 100%, 0% 100%); }
        .content { position: relative; z-index: 2; display: flex; height: 100%; gap: 40px; }
        .left-col { flex: 1.2; display: flex; flex-direction: column; justify-content: center; }
        .right-col { flex: 0.8; z-index: 3; display: flex; flex-direction: column; justify-content: center; align-items: center; color: white; text-align: center; }
        .badge { display: inline-block; background: #dbeafe; color: #1d4ed8; font-weight: 800; font-size: 11px; text-transform: uppercase; padding: 6px 14px; border-radius: 20px; letter-spacing: 1px; margin-bottom: 15px; }
        h1 { font-size: 44px; font-weight: 900; color: #0f172a; line-height: 1.1; margin: 0 0 10px 0; text-transform: uppercase; }
        .subtitle { font-size: 18px; color: #475569; font-weight: 500; margin-bottom: 30px; }
        .grid-info { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; border-top: 2px solid #e2e8f0; padding-top: 25px; }
        .info-item span { display: block; font-size: 11px; text-transform: uppercase; color: #64748b; font-weight: 700; letter-spacing: 0.5px; }
        .info-item strong { font-size: 16px; color: #0f172a; font-weight: 700; display: block; margin-top: 3px; }
        .logo-box { width: 150px; height: 150px; background: white; border-radius: 30px; display: flex; align-items: center; justify-content: center; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.2); margin-bottom: 25px; padding: 15px; }
        .logo-box img { max-width: 100%; max-height: 100%; object-fit: contain; }
        .kades-title { font-size: 12px; text-transform: uppercase; letter-spacing: 2px; opacity: 0.9; margin-bottom: 5px; font-weight: 600; }
        .kades-name { font-size: 26px; font-weight: 800; margin-bottom: 5px; letter-spacing: 0.5px; }
        .kades-nip { font-size: 13px; opacity: 0.9; font-family: monospace; }
        .footer-tag { font-size: 11px; color: #94a3b8; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 600; }
        @media print { body { background: #fff; } .page { box-shadow: none; } }
    </style>
</head>
<body onload="window.print()">
    <div class="page">
        <div class="bg-shape"></div>
        <div style="display: flex; justify-content: space-between; align-items: center; position: relative; z-index: 2;">
            <span class="badge">E-Office Official Publication</span>
            <span class="footer-tag">Pemerintahan {{ $profil->nama_desa ?? '[NAMA DESA]' }} • {{ $profil->kabupaten ?? '[KABUPATEN]' }}</span>
        </div>
        <div class="content">
            <div class="left-col">
                <h1>{{ $profil->nama_desa ?? '[NAMA DESA]' }}</h1>
                <div class="subtitle">Pusat Administrasi, Informasi Publik, & Pelayanan Mandiri Desa</div>
                <div class="grid-info">
                    <div class="info-item"><span>Kecamatan</span><strong>{{ $profil->kecamatan ?? '-' }}</strong></div>
                    <div class="info-item"><span>Kabupaten</span><strong>{{ $profil->kabupaten ?? '-' }}</strong></div>
                    <div class="info-item"><span>Kontak Resmi</span><strong>{{ $profil->telepon ?? 'Belum ada data' }}</strong></div>
                    <div class="info-item"><span>Email Kantor</span><strong>{{ $profil->email ?? 'Belum ada data' }}</strong></div>
                    <div class="info-item" style="grid-column: span 2;">
                        <span>Alamat Kantor Desa</span>
                        <strong>{{ $profil->alamat ?? '-' }} (Kode Pos: {{ $profil->kode_pos ?? '-' }})</strong>
                    </div>
                </div>
            </div>
            <div class="right-col">
                <div class="logo-box">
                    @if(isset($profil->logo_path) && \Illuminate\Support\Facades\Storage::disk('public')->exists($profil->logo_path))
                        <img src="{{ asset('storage/' . $profil->logo_path) }}" alt="Logo">
                    @else
                        <span style="color: #1e3a8a; font-weight: 900; font-size: 40px;">{{ strtoupper(substr($profil->nama_desa ?? 'DS', 0, 2)) }}</span>
                    @endif
                </div>
                <div class="kades-title">Kepala {{ $profil->nama_desa ?? '[NAMA DESA]' }}</div>
                <div class="kades-name">{{ $profil->nama_kepala_desa ?? '[NAMA KEPALA DESA]' }}</div>
                <div class="kades-nip">NIP. {{ $profil->nip_kepala_desa ?? '-----------------' }}</div>
            </div>
        </div>
        <div style="position: relative; z-index: 2; border-top: 1px solid #e2e8f0; padding-top: 15px; display: flex; justify-content: space-between; font-size: 11px; color: #64748b; font-weight: 500;">
            <span>Dokumen Resmi Dicetak Melalui Sistem E-Office {{ $profil->nama_desa ?? '[NAMA DESA]' }}</span>
            <span>Tanggal Cetak: {{ date('d-m-Y H:i') }} WIB</span>
        </div>
    </div>
</body>
</html>

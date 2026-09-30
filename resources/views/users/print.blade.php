<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ID Card - {{ e($user->name ?? 'User') }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');
        @page { size: 54mm 86mm; margin: 0; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: #94a3b8; display: flex; justify-content: center; align-items: center; height: 100vh; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
        .id-card { width: 54mm; height: 86mm; background: #ffffff; position: relative; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between; box-shadow: 0 10px 25px rgba(0,0,0,0.2); border-radius: 2mm; }
        .header-bg { background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 50%, #4f46e5 100%); height: 31mm; position: absolute; top: 0; left: 0; right: 0; clip-path: polygon(0 0, 100% 0, 100% 80%, 0% 100%); }
        .logo-text { text-align: center; color: white; position: relative; z-index: 10; padding-top: 3mm; font-size: 5.5px; font-weight: 700; letter-spacing: 0.8px; text-transform: uppercase; }
        .logo-text span { display: block; font-size: 9px; font-weight: 900; color: #ffffff; margin-top: 1px; letter-spacing: 1px; }
        .photo { width: 18mm; height: 18mm; background: #ffffff; border-radius: 50%; border: 2px solid #ffffff; box-shadow: 0 4px 10px rgba(0,0,0,0.2); position: absolute; top: 17mm; left: 50%; transform: translateX(-50%); z-index: 20; display: flex; align-items: center; justify-content: center; overflow: hidden; font-size: 7.5mm; font-weight: 900; color: #2563eb; }
        .photo img { width: 100%; height: 100%; object-fit: cover; object-position: top center; display: block; }
        .content { margin-top: 33mm; text-align: center; padding: 0 2.5mm; flex-grow: 1; display: flex; flex-direction: column; align-items: center; }
        .name { font-size: 8px; font-weight: 900; color: #0f172a; text-transform: uppercase; line-height: 1.2; max-width: 49mm; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .role { font-size: 5.5px; font-weight: 800; color: #c2410c; margin-top: 2px; text-transform: uppercase; background: #ffedd5; padding: 1.5px 5px; border-radius: 3px; display: inline-block; max-width: 48mm; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; border: 1px solid #fed7aa; }
        .group { font-size: 5px; font-weight: 700; color: #475569; margin-top: 1.5px; text-transform: uppercase; }
        .nik { font-size: 5.5px; color: #64748b; margin-top: 2px; font-weight: 600; font-family: monospace; }
        .qr-wrapper { margin-top: 2mm; display: flex; justify-content: center; align-items: center; background: #ffffff; padding: 1px; border-radius: 2px; }
        .qr-wrapper svg { width: 14mm !important; height: 14mm !important; display: block; }
        .footer { background: #0f172a; color: #94a3b8; text-align: center; font-size: 4.5px; padding: 1.5mm 0; font-weight: 700; letter-spacing: 0.8px; width: 100%; z-index: 10; text-transform: uppercase; }
        @media print { body { background: none; display: block; height: auto; } .id-card { box-shadow: none; border-radius: 0; page-break-after: avoid; page-break-before: avoid; } }
    </style>
</head>
<body onload="window.print()">
    <div class="id-card">
        <div>
            <div class="header-bg"></div>
            <div class="logo-text">
                PEMERINTAH
                <span>{{ strtoupper(str_replace('Desa ', '', $profil->nama_desa ?? 'DESA')) }}</span>
            </div>

            <div class="photo">
                @if(isset($user->penduduk->foto) && \Illuminate\Support\Facades\Storage::disk('private')->exists($user->penduduk->foto))
                    <img src="{{ route('penduduk.photo', $user->penduduk) }}" alt="Foto">
                @else
                    {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                @endif
            </div>

            <div class="content">
                <div class="name" title="{{ e($user->name ?? 'User') }}">{{ e($user->name ?? 'User') }}</div>
                <div class="role" title="{{ e($user->role?->name ?? 'Pegawai') }}">{{ e($user->role?->name ?? 'Pegawai') }}</div>
                <div class="group">{{ e($user->group?->name ?? 'E-Office System') }}</div>
                <div class="nik">ID Pegawai: {{ e(str_pad($user->id ?? 0, 5, '0', STR_PAD_LEFT)) }}</div>
                <div class="qr-wrapper">
                    @php
                        $secureId = $user->uuid ?? md5(($user->id ?? 0) . env('APP_KEY'));
                        $verificationUrl = url('/validasi/pegawai/' . $secureId);
                    @endphp
                    {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(70)->margin(0)->generate($verificationUrl) !!}
                </div>
            </div>
        </div>

        <div class="footer">
            E-OFFICE {{ strtoupper(str_replace('Desa ', '', $profil->nama_desa ?? 'SISTEM')) }} &copy; {{ date('Y') }}
        </div>
    </div>
</body>
</html>

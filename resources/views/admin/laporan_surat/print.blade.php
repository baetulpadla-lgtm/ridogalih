<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $judul ?? 'Laporan Surat' }}</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 11pt; color: #000; }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        table.kop-surat { width: 100%; border-bottom: 3.5px double #000; padding-bottom: 5px; margin-bottom: 20px; }
        .teks-kop h2 { margin: 0; font-size: 14pt; text-transform: uppercase; font-weight: normal; }
        .teks-kop h1 { margin: 2px 0; font-size: 18pt; font-weight: bold; text-transform: uppercase; }
        .teks-kop p { margin: 0; font-size: 10pt; }
        .judul-laporan { text-align: center; margin-bottom: 20px; }
        .judul-laporan h3 { margin: 0; font-size: 14pt; text-decoration: underline; text-transform: uppercase; }
        .judul-laporan p { margin: 5px 0 0 0; font-size: 11pt; }
        table.data { width: 100%; border-collapse: collapse; margin-bottom: 30px; font-size: 10pt; }
        table.data th, table.data td { border: 1px solid #000; padding: 6px 8px; vertical-align: top; }
        table.data th { background-color: #e5e5e5; text-align: center; font-weight: bold; text-transform: uppercase; font-size: 9.5pt; }
        .ttd-container { width: 100%; margin-top: 30px; page-break-inside: avoid; }
        .ttd-kanan { float: right; width: 40%; text-align: center; }
        .nama-kades { font-weight: bold; text-decoration: underline; text-transform: uppercase; margin-top: 70px; }
        .clear { clear: both; }
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
            <td width="15%" class="text-center">
                @if($logoBase64)
                    <img src="{{ $logoBase64 }}" style="max-height: 80px;" alt="Logo">
                @endif
            </td>
            <td width="70%" class="text-center teks-kop">
                <h2>Pemerintah {{ $profil->kabupaten ?? '[KABUPATEN BELUM DIATUR]' }}</h2>
                <h2>Kecamatan {{ $profil->kecamatan ?? '[KECAMATAN BELUM DIATUR]' }}</h2>
                <h1>{{ $profil->nama_desa ?? '[NAMA DESA BELUM DIATUR]' }}</h1>
                <p>{{ $profil->alamat ?? '[ALAMAT BELUM DIATUR]' }}</p>
            </td>
            <td width="15%">&nbsp;</td>
        </tr>
    </table>

    <div class="judul-laporan">
        <h3>{{ $judul ?? 'LAPORAN SURAT' }}</h3>
        <p>Periode: {{ isset($startDate) ? \Carbon\Carbon::parse($startDate)->format('d/m/Y') : '-' }} s.d. {{ isset($endDate) ? \Carbon\Carbon::parse($endDate)->format('d/m/Y') : '-' }}</p>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th width="5%">No</th>
                @if(isset($jenisLaporan) && $jenisLaporan == 'masuk')
                    <th width="12%">Tgl Terima</th>
                    <th width="18%">No & Tgl Surat</th>
                    <th width="20%">Asal Surat</th>
                    <th width="30%">Perihal / Isi Ringkas</th>
                    <th width="15%">Disposisi</th>
                @else
                    <th width="12%">Tgl Buat</th>
                    <th width="18%">Nomor Surat</th>
                    <th width="22%">Nama Pemohon</th>
                    <th width="18%">Jenis Surat</th>
                    <th width="25%">Keperluan</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($dataSurat ?? [] as $index => $surat)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    @if(isset($jenisLaporan) && $jenisLaporan == 'masuk')
                        <td class="text-center">{{ \Carbon\Carbon::parse($surat->tanggal_diterima)->format('d/m/Y') }}</td>
                        <td>
                            <strong>{{ $surat->nomor_surat ?? '-' }}</strong><br>
                            <span style="font-size:8pt;">Tgl: {{ \Carbon\Carbon::parse($surat->tanggal_surat)->format('d/m/Y') }}</span>
                        </td>
                        <td>{{ $surat->asal_surat ?? '-' }}</td>
                        <td>{{ $surat->perihal ?? '-' }}</td>
                        <td>{{ $surat->disposisi_kepada ?? '-' }}</td>
                    @else
                        <td class="text-center">{{ $surat->created_at->format('d/m/Y') }}</td>
                        <td>{{ $surat->nomor_surat ?? '-' }}</td>
                        <td>{{ $surat->penduduk?->nama_lengkap ?? 'Pemohon Umum' }}</td>
                        <td>{{ $surat->jenis_surat?->nama_surat ?? '-' }}</td>
                        <td>{{ $surat->keperluan ?? '-' }}</td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 15px; font-style: italic;">Nihil / Tidak ada catatan surat pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="ttd-container">
        <div class="ttd-kanan">
            <p>{{ ucwords(strtolower(str_replace('Desa ', '', $profil->nama_desa ?? '[KOTA]'))) }}, {{ date('d F Y') }}</p>
            <p>Kepala {{ $profil->nama_desa ?? '[NAMA DESA]' }}</p>
            <div class="nama-kades">{{ $profil->nama_kepala_desa ?? '[NAMA KEPALA DESA]' }}</div>
            @if(isset($profil->nip_kepala_desa) && $profil->nip_kepala_desa != '')
                <div style="font-size: 10pt; margin-top:2px;">NIP: {{ $profil->nip_kepala_desa }}</div>
            @endif
        </div>
        <div class="clear"></div>
    </div>
</body>
</html>

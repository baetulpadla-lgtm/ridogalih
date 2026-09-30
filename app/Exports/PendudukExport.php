<?php

namespace App\Exports;

use App\Models\Penduduk;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\Queue\ShouldQueue;

class PendudukExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, ShouldQueue
{
    // PHP 8.4: Readonly properties mutlak
    private readonly ?string $search;
    private readonly ?string $filter;

    // SECURITY: Jangan pernah inject class Request langsung ke constructor jika menggunakan Queue
    public function __construct(?string $search = null, ?string $filter = null)
    {
        $this->search = strip_tags($search);
        $this->filter = strip_tags($filter);
    }

    public function query(): Builder
    {
        return Penduduk::query()
            ->search($this->search)
            ->filterWilayah($this->filter)
            ->latest();
    }

    public function headings(): array
    {
        return [
            'NIK', 'NO KK', 'NAMA LENGKAP', 'TEMPAT LAHIR', 'TANGGAL LAHIR',
            'JENIS KELAMIN', 'AGAMA', 'PENDIDIKAN', 'PEKERJAAN', 'GOLONGAN DARAH',
            'STATUS PERKAWINAN', 'STATUS HUB. KELUARGA', 'KEWARGANEGARAAN',
            'NAMA AYAH', 'NAMA IBU KANDUNG', 'ALAMAT LENGKAP', 'DUSUN', 'RT', 'RW',
            'DESA', 'KECAMATAN', 'KABUPATEN', 'PROVINSI', 'KODE POS', 'STATUS KEPENDUDUKAN'
        ];
    }

    public function map($penduduk): array
    {
        return [
            "'" . $penduduk->nik,
            "'" . $penduduk->no_kk,
            strtoupper($penduduk->nama_lengkap),
            $penduduk->tempat_lahir,
            $penduduk->tanggal_lahir->format('Y-m-d'),
            $penduduk->jenis_kelamin,
            $penduduk->agama,
            $penduduk->pendidikan,
            $penduduk->pekerjaan,
            $penduduk->golongan_darah,
            $penduduk->status_perkawinan,
            $penduduk->status_hubungan_keluarga,
            $penduduk->kewarganegaraan,
            $penduduk->nama_ayah,
            $penduduk->nama_ibu,
            $penduduk->alamat_lengkap,
            $penduduk->dusun,
            $penduduk->rt,
            $penduduk->rw,
            $penduduk->desa,
            $penduduk->kecamatan,
            $penduduk->kabupaten,
            $penduduk->provinsi,
            $penduduk->kode_pos,
            $penduduk->status_kependudukan,
        ];
    }
}

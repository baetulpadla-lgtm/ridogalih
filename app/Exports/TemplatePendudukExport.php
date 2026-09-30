<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class TemplatePendudukExport implements FromArray
{
    public function array(): array
    {
        return [[
            'nik', 'no_kk', 'nama_lengkap', 'tempat_lahir', 'tanggal_lahir',
            'jenis_kelamin', 'agama', 'pendidikan', 'pekerjaan', 'golongan_darah',
            'status_perkawinan', 'status_hubungan_keluarga', 'kewarganegaraan',
            'nama_ayah', 'nama_ibu', 'alamat_lengkap', 'dusun', 'rt', 'rw',
            'desa', 'kecamatan', 'kabupaten', 'provinsi', 'kode_pos', 'status_kependudukan'
        ]];
    }
}

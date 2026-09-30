<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisSurat;

class JenisSuratSeeder extends Seeder
{
    public function run(): void
    {
        $surats = [
            [
                'kode_surat' => 'SKD',
                'nama_surat' => 'Surat Keterangan Domisili',
                'deskripsi'  => 'Surat untuk menerangkan domisili warga di Desa Ridogalih.',
            ],
            [
                'kode_surat' => 'SKU',
                'nama_surat' => 'Surat Keterangan Usaha',
                'deskripsi'  => 'Surat pengantar untuk keperluan izin atau permohonan bantuan usaha warga.',
            ],
            [
                'kode_surat' => 'SKCK',
                'nama_surat' => 'Surat Pengantar SKCK',
                'deskripsi'  => 'Surat pengantar dari desa sebagai syarat pembuatan SKCK di Kepolisian.',
            ],
            [
                'kode_surat' => 'SKTM',
                'nama_surat' => 'Surat Keterangan Tidak Mampu',
                'deskripsi'  => 'Surat keterangan untuk warga kurang mampu (biasanya untuk keperluan Rumah Sakit atau Beasiswa Pendidikan).',
            ],
        ];

        foreach ($surats as $surat) {
            JenisSurat::create($surat);
        }
    }
}

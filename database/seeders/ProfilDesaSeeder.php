<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProfilDesa;

class ProfilDesaSeeder extends Seeder
{
    public function run(): void
    {
        ProfilDesa::updateOrCreate(
            ['id' => 1], // Selalu gunakan ID 1 (Singleton)
            [
                'kabupaten' => 'Kabupaten Bekasi',
                'kecamatan' => 'Setu',
                'nama_desa' => 'Desa Ridogalih',
                'alamat' => 'Jl. Pemda Desa Ridogalih No. 1, Kec. Setu, Jawa Barat',
                'kode_pos' => '17320',
                'telepon' => '021-12345678',
                'email' => 'admin@ridogalih.desa.id',
                'nama_kepala_desa' => 'H. A. Supriatna, S.IP',
                'nip_kepala_desa' => '19700101 200001 1 001',
                // logo_path biarkan null, diupload via UI nanti
            ]
        );
    }
}

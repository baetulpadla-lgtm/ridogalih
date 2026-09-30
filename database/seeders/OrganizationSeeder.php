<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Group;
use App\Models\Role;
use App\Models\User;
use App\Models\Penduduk;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use RuntimeException;

class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        $adminNik = (string) env('SUPERADMIN_NIK', '');
        $adminEmail = (string) env('SUPERADMIN_EMAIL', '');
        $adminPassword = (string) env('SUPERADMIN_PASSWORD', '');

        if (
            !preg_match('/^\d{16}$/', $adminNik)
            || !filter_var($adminEmail, FILTER_VALIDATE_EMAIL)
            || strlen($adminPassword) < 12
        ) {
            throw new RuntimeException(
                'Set SUPERADMIN_NIK (16 digits), SUPERADMIN_EMAIL, and SUPERADMIN_PASSWORD (at least 12 characters) before seeding.'
            );
        }

        // 1. BUAT SEMUA GROUP DAN ROLE (Struktur Organisasi & Eksternal Desa)
        $structures = [
            'Sistem Utama'    => ['Super Admin'],
            'Pemerintah Desa' => ['Kepala Desa', 'Sekretaris Desa', 'Kaur Keuangan', 'Kaur Perencanaan', 'Kasi Pemerintahan', 'Kasi Kesejahteraan', 'Kasi Pelayanan', 'Kepala Dusun', 'Ketua RW', 'Ketua RT'],
            'BPD'             => ['Ketua BPD', 'Anggota BPD'],
            'Karang Taruna'   => ['Ketua Karang Taruna', 'Anggota Karang Taruna'],
            'BUMDES'          => ['Direktur BUMDES', 'Staff BUMDES'],
            'Koperasi'        => ['Direktur Koperasi', 'Staff Koperasi'], // Penambahan Baru
            'PKK'             => ['Ketua PKK', 'Anggota PKK'],
            'Warga'           => ['Warga'],
            'Tamu'            => ['Tamu'], // Penambahan Baru (Mitra/Swasta/Instansi Luar)
        ];

        foreach ($structures as $groupName => $roles) {
            // Buat Group / Lembaga
            $group = Group::firstOrCreate(
                ['slug' => Str::slug($groupName)],
                ['name' => $groupName]
            );

            // Buat Role untuk masing-masing Group
            foreach ($roles as $roleName) {
                Role::firstOrCreate([
                    'name'     => $roleName,
                    'group_id' => $group->id,
                ]);
            }
        }

        // 2. BUAT DATA PENDUDUK (Identitas Master untuk Super Admin)
        $pendudukAdmin = Penduduk::firstOrCreate(
            ['nik' => $adminNik], // NIK Login Utama
            [
                'no_kk'                    => '3216000000000099',
                'nama_lengkap'             => 'Administrator Utama',
                'tempat_lahir'             => 'Bekasi',
                'tanggal_lahir'            => '1990-01-01',
                'jenis_kelamin'            => 'Laki-laki',
                'agama'                    => 'Islam',
                'pendidikan'               => 'S1',
                'pekerjaan'                => 'Lainnya',
                'status_perkawinan'        => 'Kawin',
                'status_hubungan_keluarga' => 'Kepala Keluarga',
                'kewarganegaraan'          => 'WNI',
                'nama_ayah'                => 'Bapak Fulan',
                'nama_ibu'                 => 'Ibu Fulanah',
                'alamat_lengkap'           => 'Kantor Kepala Desa Ridogalih',
                'dusun'                    => '1',
                'rt'                       => '001',
                'rw'                       => '001',
                'desa'                     => 'Ridogalih',
                'kecamatan'                => 'Cibarusah',
                'kabupaten'                => 'Bekasi',
                'provinsi'                 => 'Jawa Barat',
                'status_kependudukan'      => 'Aktif',
            ]
        );

        // 3. BUAT AKUN LOGIN SUPER ADMIN
        $groupSistem = Group::where('slug', 'sistem-utama')->first();
        $roleSuperAdmin = Role::where('name', 'Super Admin')->first();
        User::firstOrCreate(
            ['email' => $adminEmail],
            [
                'penduduk_id' => $pendudukAdmin->id,
                'group_id'    => $groupSistem?->id,
                'role_id'     => $roleSuperAdmin?->id,
                'name'        => 'Super Admin e-Office',
                'password'    => Hash::make($adminPassword),
                'status_akun' => 'Aktif',
                'is_active'   => true,
            ]
        );
    }
}

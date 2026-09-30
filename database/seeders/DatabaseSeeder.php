<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // PENTING: Urutan pemanggilan ini tidak boleh diubah
        // karena saling bergantung satu sama lain (Relasi RDBMS).
        $this->call([
            OrganizationSeeder::class, // Membuat Group, Role, dan User Super Admin
            PermissionSeeder::class,   // Menyiapkan permission keys dan migrasi grant legacy
            MenuSeeder::class,         // Membuat metadata navigasi berbasis permission
            JenisSuratSeeder::class,   // Membuat format Master Surat
            ProfilDesaSeeder::class,
        ]);
    }
}

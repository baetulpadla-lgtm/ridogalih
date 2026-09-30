<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profil_desas', function (Blueprint $table) {
            // [SECURITY LAYER] 1. Internal Auto-Increment ID
            $table->id();

            // [SECURITY LAYER] 2. Public UUID untuk Route Binding (Konsistensi Arsitektur)
            $table->uuid('uuid')->unique();

            // 3. Batasi alokasi memori string (Performance Tweak)
            $table->string('nama_desa', 100)->default('Desa Ridogalih');
            $table->string('kecamatan', 100)->default('Cibarusah');
            $table->string('kabupaten', 100)->default('Kabupaten Bekasi');
            $table->string('alamat', 500)->nullable();

            // 4. Standarisasi panjang data spesifik
            $table->string('kode_pos', 5)->nullable();
            $table->string('telepon', 20)->nullable();
            $table->string('email', 100)->nullable();

            // 5. Data Pejabat (NIP umumnya 18 digit, disisihkan 25 untuk format spasi/titik)
            $table->string('nama_kepala_desa', 150)->nullable();
            $table->string('nip_kepala_desa', 25)->nullable();

            $table->string('logo_path')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profil_desas');
    }
};

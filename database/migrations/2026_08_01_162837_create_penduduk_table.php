<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('penduduk', function (Blueprint $table) {
            // [SECURITY LAYER] 1. Internal Auto-Increment ID untuk relasi DB yang sangat cepat
            $table->id();

            // [SECURITY LAYER] 2. Public UUID untuk Route Binding (Anti-IDOR)
            $table->uuid('uuid')->unique();

            // 3. Data Inti (Diindeks untuk kecepatan pencarian)
            $table->string('nik', 16)->unique();
            $table->string('no_kk', 16)->index();
            $table->string('nama_lengkap')->index();
            $table->string('foto')->nullable();

            // 4. Biodata
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');

            // [PERFORMANCE TWEAK] Hindari tipe data ENUM di database.
            // Gunakan string dengan batasan karakter, lalu validasi menggunakan PHP 8.4 Enums di Model.
            $table->string('jenis_kelamin', 20);
            $table->string('agama', 30);
            $table->string('pendidikan', 50);
            $table->string('pekerjaan', 100);
            $table->string('golongan_darah', 15)->default('Tidak Tahu');
            $table->string('status_perkawinan', 30);
            $table->string('status_hubungan_keluarga', 50);
            $table->string('kewarganegaraan', 10)->default('WNI');

            // 5. Keluarga
            $table->string('nama_ayah');
            // FIXED: Sebelumnya text, diubah menjadi string karena nama tidak butuh ruang data hingga 64KB
            $table->string('nama_ibu');

            // 6. Alamat
            $table->text('alamat_lengkap');
            $table->string('dusun')->nullable();
            $table->string('rt', 3);
            $table->string('rw', 3);
            $table->string('desa')->default('Ridogalih');
            $table->string('kecamatan')->default('Cibarusah');
            $table->string('kabupaten')->default('Kabupaten Bekasi');
            $table->string('provinsi')->default('Jawa Barat');
            $table->string('kode_pos', 5)->nullable();

            // 7. Status Kependudukan
            $table->string('status_kependudukan', 20)->default('Aktif');

            // 8. Log Waktu & Soft Deletes (Penting untuk audit log)
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void {
        Schema::dropIfExists('penduduk');
    }
};

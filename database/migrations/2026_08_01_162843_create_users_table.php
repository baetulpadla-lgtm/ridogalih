<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('users', function (Blueprint $table) {
            // [SECURITY LAYER] 1. Internal Auto-Increment ID untuk kecepatan JOIN dan Foreign Key
            $table->id();

            // [SECURITY LAYER] 2. Public UUID untuk Route Binding (Anti-IDOR)
            $table->uuid('uuid')->unique();

            // 3. Relasi Internal (Tetap menggunakan Integer ID yang sangat cepat)
            $table->foreignId('penduduk_id')->nullable()->constrained('penduduk')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('group_id')->nullable()->constrained('groups')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('role_id')->nullable()->constrained('roles')->cascadeOnUpdate()->nullOnDelete();

            // 4. Data Kredensial (Pembatasan alokasi memori string untuk optimasi indeks)
            $table->string('name', 150);
            $table->string('email', 100)->unique()->nullable();
            $table->string('password', 255);

            // 5. Status & Konfigurasi
            $table->boolean('is_active')->default(true);

            // [PERFORMANCE TWEAK] Mengubah Enum database menjadi String (20)
            // Validasi 'Aktif', 'Nonaktif', 'Suspend' akan ditangani oleh PHP 8.4 Enums di level Request/Model
            $table->string('status_akun', 20)->default('Aktif');
            $table->string('theme', 20)->default('light');

            // 6. Audit & Log Akses
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip', 45)->nullable(); // Kapasitas 45 karakter sudah mencakup format maksimal IPv6

            // 7. Keamanan Sesi & Penghapusan Lembut
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void {
        Schema::dropIfExists('users');
    }
};

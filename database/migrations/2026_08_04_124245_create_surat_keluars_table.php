<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('surat_keluars', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            $table->string('nomor_surat', 100)->nullable()->unique();

            $table->foreignId('penduduk_id')->constrained('penduduk')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('jenis_surat_id')->constrained('jenis_surats')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();

            $table->string('keperluan', 500);

            // [SECURITY LAYER] Enum database diubah menjadi string(20).
            // Validasi 'Menunggu', 'Diproses', dll ditangani PHP 8.4 Enums di Form Request.
            $table->string('status', 20)->default('Menunggu');
            $table->text('keterangan_status')->nullable();
            $table->string('file_surat', 255)->nullable();

            // --- INTEGRASI FITUR TTE & VALIDASI QR CODE ---
            $table->string('qr_code_path', 255)->nullable();
            $table->string('digital_signature_hash', 255)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void {
        Schema::dropIfExists('surat_keluars');
    }
};

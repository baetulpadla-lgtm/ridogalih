<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_masuks', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique(); // Terintegrasi langsung

            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('nomor_surat', 100)->index();
            $table->string('asal_surat', 150);
            $table->string('perihal', 500);
            $table->date('tanggal_surat');
            $table->date('tanggal_diterima')->index();

            $table->string('disposisi_kepada', 255)->nullable();
            $table->string('file_scan', 255)->nullable();
            $table->text('keterangan')->nullable(); // Text dipertahankan hanya untuk keterangan panjang

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_masuks');
    }
};

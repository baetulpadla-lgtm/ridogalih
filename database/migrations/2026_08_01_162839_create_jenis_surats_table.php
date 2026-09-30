<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jenis_surats', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique(); // Terintegrasi langsung

            // Pembatasan memori string untuk optimasi indeks
            $table->string('kode_surat', 50)->unique();
            $table->string('nama_surat', 150)->index();
            $table->string('deskripsi', 500)->nullable(); // Mengganti text menjadi string(500)
            $table->string('template_dokumen', 255)->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jenis_surats');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            // [PERFORMANCE TWEAK] ULID sangat ideal untuk tabel riwayat yang tumbuh eksponensial
            $table->ulid('id')->primary();

            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            // Limitasi karakter untuk kecepatan Indexing
            $table->string('event', 50)->index(); // created, updated, deleted, login, dll
            $table->string('table_name', 100);
            $table->unsignedBigInteger('record_id')->nullable();

            // Payload perubahan
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();

            // Device Intelligence & Tracking
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable(); // Mengganti text menjadi string(500)
            $table->string('device_type', 50)->nullable();
            $table->string('platform', 50)->nullable();
            $table->string('browser', 100)->nullable();
            $table->boolean('is_robot')->default(false);

            $table->timestamps();

            // Composite Index untuk pelacakan sejarah data spesifik (misal: "Siapa yang ubah penduduk ID 5?")
            $table->index(['table_name', 'record_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};

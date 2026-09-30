<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // A. Tabel Induk Perangkat IoT
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique(); // Route Binding

            $table->string('nama_perangkat', 100);
            $table->string('lokasi', 100);
            $table->string('mac_address', 17)->unique(); // Format presisi: XX:XX:XX:XX:XX:XX

            // [SECURITY LAYER] Token komunikasi API IoT wajib disimpan sebagai Hash
            $table->string('api_token_hash', 128);

            // Mengganti Enum tingkat Database ke String untuk stabilitas skema
            $table->string('status', 20)->default('Aktif');

            $table->timestamp('last_ping')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // B. Tabel Log Akses Fisik (Forensik Buka Pintu)
        Schema::create('device_logs', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignId('device_id')->constrained('devices')->restrictOnDelete();
            $table->foreignId('penduduk_id')->nullable()->constrained('penduduk')->nullOnDelete();

            // Mengganti Enum: QR, RFID, FINGERPRINT
            $table->string('metode_akses', 20);
            $table->string('payload_scanned', 255);

            // Mengganti Enum: Granted, Denied, Invalid_Device
            $table->string('status_akses', 20);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('device_logs');
        Schema::dropIfExists('devices');
    }
};

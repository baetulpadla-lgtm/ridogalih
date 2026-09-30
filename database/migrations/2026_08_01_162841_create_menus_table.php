<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique(); // Integrasi langsung

            $table->string('nama_menu', 100);
            $table->string('url_route', 150)->nullable()->index(); // Cukup 150 karakter untuk nama route Laravel
            $table->boolean('is_sidebar')->default(true);
            $table->string('icon', 50)->nullable(); // Class icon (cth: fas fa-home) tidak butuh 255 karakter

            $table->foreignId('parent_id')->nullable()->constrained('menus')->cascadeOnDelete();
            $table->integer('urutan')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('menus');
    }
};

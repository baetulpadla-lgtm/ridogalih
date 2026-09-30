<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique(); // Standarisasi UUID

            // Limitasi memori untuk mempercepat pencarian
            $table->string('name', 100)->unique();
            $table->string('slug', 100)->unique();

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('groups');
    }
};

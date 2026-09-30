<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->foreignId('permission_id')
                ->nullable()
                ->after('parent_id')
                ->constrained('permissions')
                ->nullOnDelete();
            $table->boolean('is_active')->default(true)->after('is_sidebar');
        });
    }

    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->dropConstrainedForeignId('permission_id');
            $table->dropColumn('is_active');
        });
    }
};

<?php

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penduduk', function (Blueprint $table): void {
            $table->text('nama_ibu')->change();
        });

        DB::table('penduduk')
            ->select(['id', 'nama_ibu'])
            ->whereNotNull('nama_ibu')
            ->orderBy('id')
            ->chunkById(100, function ($rows): void {
                foreach ($rows as $row) {
                    try {
                        Crypt::decryptString($row->nama_ibu);
                    } catch (DecryptException) {
                        DB::table('penduduk')
                            ->where('id', $row->id)
                            ->update(['nama_ibu' => Crypt::encryptString($row->nama_ibu)]);
                    }
                }
            });
    }

    public function down(): void
    {
        // Encrypted values are intentionally not reverted to plaintext.
    }
};

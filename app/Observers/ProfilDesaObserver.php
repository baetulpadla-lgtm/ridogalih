<?php

namespace App\Observers;

use App\Models\ProfilDesa;
use Illuminate\Support\Facades\Cache;

class ProfilDesaObserver
{
    /**
     * Dieksekusi setiap kali data berhasil disimpan (Create atau Update).
     */
    public function saved(ProfilDesa $profilDesa): void
    {
        // Membersihkan cache agar cetakan surat langsung menggunakan nama Kades baru
        Cache::forget('nama_kepala_desa_aktif');
    }
}

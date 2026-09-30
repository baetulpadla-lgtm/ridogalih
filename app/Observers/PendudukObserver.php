<?php

namespace App\Observers;

use App\Models\Penduduk;
use Illuminate\Support\Facades\Cache;

class PendudukObserver
{
    // 'saved' mencakup proses create dan update sekaligus
    public function saved(Penduduk $penduduk): void
    {
        Cache::forget('penduduk_stats');
    }

    public function deleted(Penduduk $penduduk): void
    {
        Cache::forget('penduduk_stats');
    }

    public function restored(Penduduk $penduduk): void
    {
        Cache::forget('penduduk_stats');
    }

    public function forceDeleted(Penduduk $penduduk): void
    {
        Cache::forget('penduduk_stats');
    }
}

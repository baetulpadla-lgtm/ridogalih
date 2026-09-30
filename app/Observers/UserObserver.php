<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

class UserObserver
{
    private function checkAndClearKadesCache(User $user): void
    {
        $user->loadMissing('role');
        if (str_contains(strtolower($user->role?->name ?? ''), 'kepala desa')) {
            Cache::forget('nama_kepala_desa_aktif');
        }
    }

    public function saved(User $user): void
    {
        $this->checkAndClearKadesCache($user);
    }

    public function deleted(User $user): void
    {
        $this->checkAndClearKadesCache($user);
    }
}

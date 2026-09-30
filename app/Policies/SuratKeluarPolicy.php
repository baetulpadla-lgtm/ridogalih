<?php

namespace App\Policies;

use App\Models\SuratKeluar;
use App\Models\User;

class SuratKeluarPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('surat_keluar.view');
    }

    public function view(User $user, SuratKeluar $suratKeluar): bool
    {
        return $user->hasPermission('surat_keluar.view')
            || $suratKeluar->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('surat_keluar.create');
    }

    public function update(User $user, SuratKeluar $suratKeluar): bool
    {
        return $user->hasPermission('surat_keluar.update')
            || ($suratKeluar->user_id === $user->id && $suratKeluar->status === 'Menunggu');
    }

    public function delete(User $user, SuratKeluar $suratKeluar): bool
    {
        return $user->hasPermission('surat_keluar.delete')
            || ($suratKeluar->user_id === $user->id && $suratKeluar->status === 'Menunggu');
    }

    public function print(User $user, SuratKeluar $suratKeluar): bool
    {
        return $user->hasPermission('surat_keluar.print');
    }
}

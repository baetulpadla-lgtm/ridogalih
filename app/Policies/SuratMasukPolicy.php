<?php

namespace App\Policies;

use App\Models\SuratMasuk;
use App\Models\User;

class SuratMasukPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('surat_masuk.view');
    }

    public function view(User $user, SuratMasuk $suratMasuk): bool
    {
        return $user->hasPermission('surat_masuk.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('surat_masuk.create');
    }

    public function update(User $user, SuratMasuk $suratMasuk): bool
    {
        return $user->hasPermission('surat_masuk.update');
    }

    public function delete(User $user, SuratMasuk $suratMasuk): bool
    {
        return $user->hasPermission('surat_masuk.delete');
    }
}

<?php

namespace App\Policies;

use App\Models\Penduduk;
use App\Models\User;

class PendudukPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('penduduk.view');
    }

    public function view(User $user, Penduduk $penduduk): bool
    {
        return $user->hasPermission('penduduk.view');
    }

    public function photo(User $user, Penduduk $penduduk): bool
    {
        return $user->hasPermission('penduduk.view')
            || $user->hasPermission('users.view')
            || $user->penduduk_id === $penduduk->id;
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('penduduk.create');
    }

    public function update(User $user, Penduduk $penduduk): bool
    {
        return $user->hasPermission('penduduk.update');
    }

    public function delete(User $user, Penduduk $penduduk): bool
    {
        return $user->hasPermission('penduduk.delete');
    }
}

<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('users.view');
    }

    public function view(User $user, User $target): bool
    {
        return $user->id === $target->id || $user->hasPermission('users.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('users.create');
    }

    public function update(User $user, User $target): bool
    {
        return $user->hasPermission('users.update')
            && (!$target->isSuperAdmin() || $user->isSuperAdmin());
    }

    public function delete(User $user, User $target): bool
    {
        return $user->hasPermission('users.delete')
            && $user->id !== $target->id
            && (!$target->isSuperAdmin() || $user->isSuperAdmin());
    }
}

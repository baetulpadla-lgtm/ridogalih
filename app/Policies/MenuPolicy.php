<?php

namespace App\Policies;

use App\Models\Menu;
use App\Models\User;

class MenuPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('security.settings.update');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('security.settings.update');
    }

    public function update(User $user, Menu $menu): bool
    {
        return $user->hasPermission('security.settings.update');
    }

    public function delete(User $user, Menu $menu): bool
    {
        return $user->hasPermission('security.settings.update');
    }
}

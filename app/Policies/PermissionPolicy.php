<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\User;

class PermissionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('security.permissions.assign');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('security.permissions.assign');
    }

    public function update(User $user, Permission $permission): bool
    {
        return !$permission->is_protected && $user->hasPermission('security.permissions.assign');
    }
}

<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;

class RolePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('roles.view');
    }

    public function view(User $user, Role $role): bool
    {
        return $user->hasPermission('roles.view');
    }

    public function update(User $user, Role $role): bool
    {
        return $user->hasPermission('roles.update')
            && (!$role->permissions()->where('key', 'system.admin')->exists() || $user->isSuperAdmin());
    }

    public function assignPermissions(User $user, Role $role): bool
    {
        return $this->update($user, $role)
            && $user->hasPermission('security.permissions.assign');
    }
}

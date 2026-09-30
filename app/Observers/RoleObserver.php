<?php

namespace App\Observers;

use App\Models\Role;
use Illuminate\Support\Facades\Cache;

class RoleObserver
{
    private function clearRoleCache(Role $role): void
    {
        Cache::forget('privilege_routes_role_' . $role->id);
        Cache::forget('sidebar_menus_role_' . $role->id);
    }

    public function saved(Role $role): void
    {
        $this->clearRoleCache($role);
    }

    public function deleted(Role $role): void
    {
        $this->clearRoleCache($role);
    }
}

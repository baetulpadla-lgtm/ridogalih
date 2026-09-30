<?php

namespace App\Observers;

use App\Models\Menu;
use App\Models\Role;
use Illuminate\Support\Facades\Cache;

class MenuObserver
{
    /**
     * Membersihkan cache navigasi dan hak akses untuk seluruh Role
     * setiap kali ada struktur menu atau URL yang diubah.
     */
    private function clearAllMenuCaches(): void
    {
        $roleIds = Role::pluck('id');

        foreach ($roleIds as $roleId) {
            Cache::forget('privilege_routes_role_' . $roleId);
            Cache::forget('sidebar_menus_role_' . $roleId);
        }
    }

    public function saved(Menu $menu): void
    {
        $this->clearAllMenuCaches();
    }

    public function deleted(Menu $menu): void
    {
        $this->clearAllMenuCaches();
    }
}

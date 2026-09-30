<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Permission;
use App\Models\Role;
use App\Services\Authorization\PermissionRegistry;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $definitions = [
            'dashboard.view' => ['Dashboard', false],
            'users.view' => ['View users', false],
            'users.create' => ['Create users', false],
            'users.update' => ['Update users', false],
            'users.delete' => ['Delete users', false],
            'users.export' => ['Export users', false],
            'penduduk.view' => ['View residents', false],
            'penduduk.create' => ['Create residents', false],
            'penduduk.update' => ['Update residents', false],
            'penduduk.delete' => ['Delete residents', false],
            'penduduk.export' => ['Export residents', false],
            'surat_masuk.view' => ['View incoming mail', false],
            'surat_masuk.create' => ['Create incoming mail', false],
            'surat_masuk.update' => ['Update incoming mail', false],
            'surat_masuk.delete' => ['Delete incoming mail', false],
            'surat_keluar.view' => ['View outgoing mail', false],
            'surat_keluar.create' => ['Create outgoing mail', false],
            'surat_keluar.update' => ['Update outgoing mail', false],
            'surat_keluar.delete' => ['Delete outgoing mail', false],
            'surat_keluar.print' => ['Print outgoing mail', false],
            'jenis_surat.view' => ['View letter types', false],
            'jenis_surat.create' => ['Create letter types', false],
            'jenis_surat.update' => ['Update letter types', false],
            'jenis_surat.delete' => ['Delete letter types', false],
            'laporan_surat.view' => ['View letter reports', false],
            'laporan_surat.print' => ['Print letter reports', false],
            'roles.view' => ['View roles', false],
            'roles.update' => ['Update roles', false],
            'security.audit.view' => ['View security audit logs', true],
            'security.permissions.assign' => ['Assign role permissions', true],
            'security.settings.update' => ['Update security settings', true],
            'system.admin' => ['System administrator', true],
        ];

        foreach ($definitions as $key => [$name, $protected]) {
            Permission::updateOrCreate(
                ['key' => $key],
                ['name' => $name, 'is_protected' => $protected]
            );
        }

        foreach (PermissionRegistry::menuRoutePermissions() as $routeName => $permissionKey) {
            $permission = Permission::where('key', $permissionKey)->first();
            if (!$permission) {
                continue;
            }

            Menu::where('url_route', $routeName)->update(['permission_id' => $permission->id]);

            $roleIds = Menu::where('url_route', $routeName)
                ->with('roles:id')
                ->get()
                ->flatMap(fn (Menu $menu) => $menu->roles->pluck('id'))
                ->unique();

            foreach ($roleIds as $roleId) {
                Role::find($roleId)?->permissions()->syncWithoutDetaching([$permission->id]);
            }
        }

        foreach (PermissionRegistry::legacyWildcardPermissions() as $wildcard => $permissionKeys) {
            $roleIds = Menu::where('url_route', $wildcard)
                ->with('roles:id')
                ->get()
                ->flatMap(fn (Menu $menu) => $menu->roles->pluck('id'))
                ->unique();

            foreach ($roleIds as $roleId) {
                $role = Role::find($roleId);
                if (!$role) {
                    continue;
                }

                $permissionIds = Permission::whereIn('key', $permissionKeys)->pluck('id');
                $role->permissions()->syncWithoutDetaching($permissionIds);
            }
        }

        Menu::whereIn('url_route', array_keys(PermissionRegistry::legacyWildcardPermissions()))
            ->update(['is_sidebar' => false]);

        $dashboardPermissionId = Permission::where('key', 'dashboard.view')->value('id');
        if ($dashboardPermissionId) {
            foreach (Role::all() as $role) {
                $role->permissions()->syncWithoutDetaching([$dashboardPermissionId]);
            }
        }

        $superAdminRole = Role::where('name', 'Super Admin')->first();
        if ($superAdminRole) {
            $superAdminRole->permissions()->sync(Permission::pluck('id')->all());
        }
    }
}

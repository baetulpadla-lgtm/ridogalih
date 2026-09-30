<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Menu;
use App\Models\Group;
use App\Models\Permission;
use App\Http\Requests\MenuRequest;
use App\Http\Requests\PermissionRequest;
use App\Http\Requests\RoleMenuSyncRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RoleMenuController extends Controller
{
    public function index(): View
    {
        Gate::authorize('viewAny', Role::class);

        $groups = Group::with(['roles.permissions', 'roles.group'])->get();
        return view('admin.role-menus.index', compact('groups'));
    }

    public function edit(Role $role): View
    {
        Gate::authorize('view', $role);

        $role->load('permissions');
        $menus = Menu::whereNull('parent_id')->with('children')->orderBy('urutan')->get();
        $permissions = Permission::orderBy('key')->get();
        $assignedPermissions = $role->permissions->pluck('id')->all();
        $routeNames = collect(Route::getRoutes()->getRoutesByName())
            ->filter(fn ($route) => $route->parameterNames() === [])
            ->keys()
            ->all();

        return view('admin.role-menus.edit', compact('role', 'menus', 'permissions', 'assignedPermissions', 'routeNames'));
    }

    public function update(RoleMenuSyncRequest $request, Role $role): RedirectResponse
    {
        Gate::authorize('assignPermissions', $role);

        $permissions = Permission::whereIn('id', $request->validated('permissions', []))->get();
        $user = $request->user();

        if (!$user->isSuperAdmin() && $permissions->contains(fn (Permission $permission) => $permission->is_protected)) {
            abort(403, 'Protected permissions hanya dapat diberikan oleh System Admin.');
        }

        if ($role->permissions()->where('key', 'system.admin')->exists()
            && !$permissions->contains('key', 'system.admin')) {
            abort(403, 'Permission system.admin tidak dapat dicabut dari role System Admin.');
        }

        $role->permissions()->sync($permissions->modelKeys());

        return redirect()->route('admin.role-menus.index')
                         ->with('success', 'Permissions untuk role ' . $role->name . ' berhasil diperbarui.');
    }

    public function storePermission(PermissionRequest $request): RedirectResponse
    {
        Gate::authorize('create', Permission::class);
        $validated = $request->validated();
        $isProtected = str_starts_with($validated['key'], 'security.')
            || str_starts_with($validated['key'], 'system.');

        if ($isProtected && !$request->user()->isSuperAdmin()) {
            abort(403, 'Protected permissions hanya dapat dibuat oleh System Admin.');
        }

        Permission::create([
            'key' => $validated['key'],
            'name' => $validated['name'],
            'is_protected' => $isProtected,
        ]);

        return back()->with('success', 'Permission berhasil ditambahkan.');
    }

    public function storeMenu(MenuRequest $request): RedirectResponse
    {
        Gate::authorize('create', Menu::class);
        $validated = $request->validated();

        $parentId = $validated['parent_uuid'] ? Menu::where('uuid', $validated['parent_uuid'])->value('id') : null;

        Menu::create([
            'nama_menu'  => $validated['nama_menu'],
            'url_route'  => $validated['url_route'],
            'permission_id' => $validated['permission_id'] ?? null,
            'is_sidebar' => $validated['is_sidebar'],
            'is_active' => $validated['is_active'],
            'icon'       => $validated['icon'] ?: null,
            'parent_id'  => $parentId,
            'urutan'     => Menu::max('urutan') + 1,
        ]);

        return back()->with('success', 'Rute/Menu Sistem baru berhasil didaftarkan!');
    }

    public function updateMenu(MenuRequest $request, Menu $menu): RedirectResponse
    {
        Gate::authorize('update', $menu);
        $validated = $request->validated();

        $newParentId = $validated['parent_uuid'] ? Menu::where('uuid', $validated['parent_uuid'])->value('id') : null;

        $menu->update([
            'nama_menu'  => $validated['nama_menu'],
            'url_route'  => $validated['url_route'],
            'permission_id' => $validated['permission_id'] ?? null,
            'is_sidebar' => $validated['is_sidebar'],
            'is_active' => $validated['is_active'],
            'icon'       => $validated['icon'] ?: null,
            'parent_id'  => $newParentId,
        ]);

        return back()->with('success', 'Rute/Menu berhasil diperbarui secara presisi.');
    }

    public function destroyMenu(Menu $menu): RedirectResponse
    {
        Gate::authorize('delete', $menu);
        $menu->loadMissing('children');

        DB::transaction(function () use ($menu) {
            if ($menu->children->isNotEmpty()) {
                foreach ($menu->children as $child) {
                    $child->roles()->detach();
                    $child->delete();
                }
            }
            $menu->roles()->detach();
            $menu->delete();
        });

        return redirect()->back()->with('success', 'Rute/Menu sistem beserta seluruh anak menunya berhasil dicabut.');
    }
}

<?php

namespace Tests\Feature;

use App\Models\Menu;
use App\Models\Permission;
use App\Models\Role;
use App\Models\SuratKeluar;
use App\Models\User;
use App\Services\MenuService;
use App\Services\Authorization\PermissionRegistry;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Tests\TestCase;

class RbacFoundationTest extends TestCase
{
    use RefreshDatabase;

    public function test_permission_grants_are_role_based_and_system_admin_is_permission_based(): void
    {
        $role = $this->createRole('Named Super Admin');
        $user = $this->createUser($role);
        $usersView = $this->permission('users.view');

        $this->assertFalse($user->isSuperAdmin());
        $this->assertFalse(Gate::forUser($user)->allows('users.view'));

        $role->permissions()->attach($usersView);
        $user->unsetRelation('role');

        $this->assertTrue($user->hasPermission('users.view'));
        $this->assertTrue(Gate::forUser($user)->allows('users.view'));
        $this->assertFalse($user->isSuperAdmin());

        $role->permissions()->attach($this->permission('system.admin', true));
        $user->unsetRelation('role');
        $this->assertTrue($user->isSuperAdmin());
    }

    public function test_unmapped_legacy_role_menu_grants_are_not_authorization(): void
    {
        $role = $this->createRole('Clerk');
        $user = $this->createUser($role);
        $menu = Menu::create([
            'nama_menu' => 'User administration',
            'url_route' => 'users.index',
            'is_sidebar' => true,
            'is_active' => true,
            'urutan' => 1,
        ]);
        $role->menus()->attach($menu);

        $this->actingAs($user)->get(route('users.index'))->assertForbidden();
    }

    public function test_permission_middleware_enforces_the_permission_key(): void
    {
        $role = $this->createRole('Reader');
        $user = $this->createUser($role);
        $role->permissions()->attach($this->permission('users.view'));

        $this->actingAs($user)->get(route('users.index'))->assertOk();
    }

    public function test_user_policy_prevents_cross_user_access_without_users_view(): void
    {
        $role = $this->createRole('Basic User');
        $user = $this->createUser($role);
        $otherUser = $this->createUser($role);

        $this->assertTrue(Gate::forUser($user)->allows('view', $user));
        $this->assertFalse(Gate::forUser($user)->allows('view', $otherUser));

        $this->assign($role, 'users.view');
        $user->unsetRelation('role');
        $this->assertTrue(Gate::forUser($user)->allows('view', $otherUser));
    }

    public function test_outgoing_letter_policy_limits_pending_self_service_to_the_owner(): void
    {
        $role = $this->createRole('Resident');
        $owner = $this->createUser($role);
        $otherUser = $this->createUser($role);
        $letter = new SuratKeluar();
        $letter->user_id = $owner->id;
        $letter->status = 'Menunggu';

        $this->assertTrue(Gate::forUser($owner)->allows('view', $letter));
        $this->assertTrue(Gate::forUser($owner)->allows('update', $letter));
        $this->assertFalse(Gate::forUser($otherUser)->allows('view', $letter));
        $this->assertFalse(Gate::forUser($otherUser)->allows('update', $letter));

        $letter->status = 'Diproses';
        $this->assertFalse(Gate::forUser($owner)->allows('update', $letter));
    }

    public function test_every_route_using_privilege_middleware_has_an_explicit_action_mapping(): void
    {
        foreach (Route::getRoutes() as $route) {
            if (in_array('privilege', $route->gatherMiddleware(), true)) {
                $this->assertTrue(
                    PermissionRegistry::recognizes($route->getActionName()),
                    'Unmapped protected controller action: '.$route->getActionName()
                );
            }
        }
    }

    public function test_only_system_admin_can_grant_protected_permissions(): void
    {
        $actorRole = $this->createRole('Delegated Role');
        $this->assign($actorRole, 'roles.update');
        $this->assign($actorRole, 'security.permissions.assign', true);
        $actor = $this->createUser($actorRole);
        $targetRole = $this->createRole('Target Role');
        $systemAdminPermission = $this->permission('system.admin', true);

        $this->actingAs($actor)
            ->put(route('admin.role-menus.update', $targetRole->uuid), [
                'permissions' => [$systemAdminPermission->id],
            ])
            ->assertForbidden();

        $this->assertFalse($targetRole->permissions()->whereKey($systemAdminPermission->id)->exists());
    }

    public function test_a_role_with_only_roles_update_cannot_assign_any_permissions(): void
    {
        $actorRole = $this->createRole('Role Editor');
        $this->assign($actorRole, 'roles.update');
        $actor = $this->createUser($actorRole);
        $targetRole = $this->createRole('Target Role');

        $this->actingAs($actor)
            ->put(route('admin.role-menus.update', $targetRole->uuid), ['permissions' => []])
            ->assertForbidden();
    }

    public function test_only_system_admin_can_create_protected_permissions(): void
    {
        $role = $this->createRole('Permission Manager');
        $this->assign($role, 'security.permissions.assign', true);
        $actor = $this->createUser($role);

        $this->actingAs($actor)
            ->post(route('admin.role-menus.permissions.store'), [
                'key' => 'security.reports.export',
                'name' => 'Export security reports',
            ])
            ->assertForbidden();

        $this->assertDatabaseMissing('permissions', ['key' => 'security.reports.export']);
    }

    public function test_permission_manager_can_create_an_ordinary_permission(): void
    {
        $role = $this->createRole('Permission Manager');
        $this->assign($role, 'security.permissions.assign', true);
        $actor = $this->createUser($role);

        $this->actingAs($actor)
            ->post(route('admin.role-menus.permissions.store'), [
                'key' => 'arsip.export',
                'name' => 'Export archives',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('permissions', [
            'key' => 'arsip.export',
            'is_protected' => false,
        ]);
    }

    public function test_role_permission_editor_renders_database_permissions_and_navigation(): void
    {
        $adminRole = $this->createRole('Bootstrap Role');
        $this->assign($adminRole, 'system.admin', true);
        $user = $this->createUser($adminRole);
        $user->update([
            'mfa_secret' => 'GEZDGNBVGY3TQOJQGEZDGNBVGY3TQOJQ',
            'mfa_enabled_at' => now(),
        ]);
        $targetRole = $this->createRole('Target Role');
        $this->permission('users.view');

        $this->actingAs($user)
            ->get(route('admin.role-menus.edit', $targetRole->uuid))
            ->assertOk()
            ->assertSee('users.view')
            ->assertSee('Metadata navigasi');
    }

    public function test_menu_service_returns_only_navigation_authorized_by_permission(): void
    {
        $role = $this->createRole('Limited Reader');
        $user = $this->createUser($role);
        $parent = Menu::create([
            'nama_menu' => 'Administration',
            'url_route' => null,
            'is_sidebar' => true,
            'is_active' => true,
            'urutan' => 1,
        ]);
        Menu::create([
            'nama_menu' => 'Users',
            'url_route' => 'users.index',
            'permission_id' => $this->permission('users.view')->id,
            'parent_id' => $parent->id,
            'is_sidebar' => true,
            'is_active' => true,
            'urutan' => 1,
        ]);

        $this->assertCount(0, app(MenuService::class)->forUser($user));

        $this->assign($role, 'users.view');
        $user->unsetRelation('role');
        $menus = app(MenuService::class)->forUser($user);

        $this->assertCount(1, $menus);
        $this->assertSame('Users', $menus->first()->children->first()->nama_menu);
    }

    public function test_menu_navigation_is_pre_authorized_and_contains_only_static_route_urls(): void
    {
        $role = $this->createRole('Navigation Reader');
        $user = $this->createUser($role);
        $usersPermission = $this->permission('users.view');
        $this->assign($role, 'users.view');

        $parent = Menu::create([
            'nama_menu' => 'Users',
            'url_route' => null,
            'icon' => 'fas fa-users',
            'is_sidebar' => true,
            'is_active' => true,
            'urutan' => 1,
        ]);
        Menu::create([
            'nama_menu' => 'User list',
            'url_route' => 'users.index',
            'permission_id' => $usersPermission->id,
            'icon' => 'fas fa-list',
            'parent_id' => $parent->id,
            'is_sidebar' => true,
            'is_active' => true,
            'urutan' => 1,
        ]);
        Menu::create([
            'nama_menu' => 'User detail',
            'url_route' => 'users.show',
            'permission_id' => $usersPermission->id,
            'icon' => 'fas fa-user',
            'parent_id' => $parent->id,
            'is_sidebar' => true,
            'is_active' => true,
            'urutan' => 2,
        ]);
        Menu::create([
            'nama_menu' => 'Audit logs',
            'url_route' => 'admin.audit-logs.index',
            'permission_id' => $this->permission('security.audit.view')->id,
            'icon' => 'fas fa-lock',
            'is_sidebar' => true,
            'is_active' => true,
            'urutan' => 2,
        ]);

        $menuService = app(MenuService::class);
        $navigation = $menuService->navigationForUser($user, 'users.index', 'users');

        $this->assertCount(1, $navigation);
        $this->assertSame('Users', $navigation[0]['name']);
        $this->assertTrue($navigation[0]['active']);
        $this->assertCount(1, $navigation[0]['children']);
        $this->assertSame(route('users.index'), $navigation[0]['children'][0]['url']);
        $this->assertTrue($navigation[0]['children'][0]['active']);
        $this->assertSame([[
            'name' => 'User list',
            'url' => route('users.index'),
            'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707l0 9a2 2 0 01-2 2z',
        ]], $menuService->searchItems($navigation));

        $sidebarHtml = view('layouts.sidebar', ['menuNavigations' => $navigation])->render();
        $this->assertStringContainsString(route('users.index'), $sidebarHtml);
        $this->assertStringContainsString('User list', $sidebarHtml);
        $this->assertStringNotContainsString('User detail', $sidebarHtml);
    }

    public function test_permission_seeder_backfills_existing_exact_menu_grants(): void
    {
        $role = $this->createRole('Existing Reader');
        $menu = Menu::create([
            'nama_menu' => 'User list',
            'url_route' => 'users.index',
            'is_sidebar' => true,
            'is_active' => true,
            'urutan' => 1,
        ]);
        $role->menus()->attach($menu);

        app(PermissionSeeder::class)->run();

        $this->assertTrue($role->permissions()->where('key', 'users.view')->exists());
        $this->assertSame(
            $this->permission('users.view')->id,
            $menu->fresh()->permission_id
        );
    }

    private function createRole(string $name): Role
    {
        return Role::create(['name' => $name]);
    }

    private function createUser(Role $role): User
    {
        $id = DB::table('users')->insertGetId([
            'uuid' => (string) Str::uuid(),
            'name' => 'Test User',
            'email' => Str::uuid().'@example.test',
            'password' => Hash::make('password'),
            'role_id' => $role->id,
            'is_active' => true,
            'status_akun' => 'Aktif',
            'theme' => 'light',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return User::findOrFail($id);
    }

    private function permission(string $key, bool $protected = false): Permission
    {
        return Permission::firstOrCreate(
            ['key' => $key],
            ['name' => $key, 'is_protected' => $protected]
        );
    }

    private function assign(Role $role, string $key, bool $protected = false): void
    {
        $role->permissions()->syncWithoutDetaching([$this->permission($key, $protected)->id]);
    }
}

<?php

namespace App\Services;

use App\Models\Menu;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class MenuService
{
    private const SEARCH_ICON = 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707l0 9a2 2 0 01-2 2z';

    /**
     * Build the navigation tree using the same permission keys enforced by backend Gates.
     *
     * @return Collection<int, Menu>
     */
    public function forUser(?User $user): Collection
    {
        if (! $user) {
            return new Collection;
        }

        $permissionKeys = Role::with('permissions:id,key')
            ->find($user->role_id)
            ?->permissions
            ->pluck('key')
            ->all() ?? [];
        $permissionKeys = array_fill_keys($permissionKeys, true);
        $isSystemAdmin = isset($permissionKeys['system.admin']);

        $menus = Menu::query()
            ->whereNull('parent_id')
            ->sidebar()
            ->with([
                'permission',
                'children' => fn ($query) => $query->sidebar()->with('permission')->orderBy('urutan'),
            ])
            ->orderBy('urutan')
            ->get();

        return $menus->filter(function (Menu $menu) use ($permissionKeys, $isSystemAdmin): bool {
            $menu->setRelation(
                'children',
                $menu->children->filter(fn (Menu $child) => $this->authorized($child, $permissionKeys, $isSystemAdmin))->values()
            );

            if (! $this->authorized($menu, $permissionKeys, $isSystemAdmin)) {
                return false;
            }

            return $menu->url_route !== null || $menu->children->isNotEmpty();
        })->values();
    }

    /**
     * @return array<int, array{name: string, url: string|null, icon: string, active: bool, children: array<int, array{name: string, url: string, icon: string, active: bool}>}>
     */
    public function navigationForUser(?User $user, ?string $currentRouteName = null, ?string $currentPath = null): array
    {
        $menus = $this->forUser($user);
        $visibleRouteNames = $menus
            ->flatMap(fn (Menu $menu) => collect([$menu])->merge($menu->children))
            ->filter(fn (Menu $menu) => $this->routeUrl($menu->url_route) !== null)
            ->pluck('url_route')
            ->all();

        $currentRouteName ??= request()->route()?->getName() ?? '';
        $currentPath ??= request()->path();

        return $menus
            ->map(function (Menu $menu) use ($visibleRouteNames, $currentRouteName, $currentPath): ?array {
                $children = $menu->children
                    ->map(fn (Menu $child) => $this->navigationItem($child, $visibleRouteNames, $currentRouteName, $currentPath))
                    ->filter()
                    ->values()
                    ->all();
                $url = $this->routeUrl($menu->url_route);

                if ($children === [] && $url === null) {
                    return null;
                }

                $isActive = $this->routeIsActive($menu->url_route, $currentRouteName, $currentPath, $visibleRouteNames)
                    || collect($children)->contains(fn (array $child) => $child['active']);

                return [
                    'name' => $menu->nama_menu,
                    'url' => $url,
                    'icon' => $this->safeIcon($menu->icon),
                    'active' => $isActive,
                    'children' => $children,
                ];
            })
            ->filter()
            ->values()
            ->all();
    }

    /**
     * @param  array<int, array{name: string, url: string|null, icon: string, active: bool, children: array<int, array{name: string, url: string, icon: string, active: bool}>}>  $navigation
     * @return array<int, array{name: string, url: string, icon: string}>
     */
    public function searchItems(array $navigation): array
    {
        return collect($navigation)
            ->flatMap(fn (array $menu) => collect([$menu])->merge($menu['children']))
            ->filter(fn (array $menu) => $menu['url'] !== null)
            ->map(fn (array $menu) => [
                'name' => $menu['name'],
                'url' => $menu['url'],
                'icon' => self::SEARCH_ICON,
            ])
            ->values()
            ->all();
    }

    /**
     * @param  array<int, string|null>  $visibleRouteNames
     * @return array{name: string, url: string, icon: string, active: bool}|null
     */
    private function navigationItem(Menu $menu, array $visibleRouteNames, string $currentRouteName, string $currentPath): ?array
    {
        $url = $this->routeUrl($menu->url_route);
        if ($url === null) {
            return null;
        }

        return [
            'name' => $menu->nama_menu,
            'url' => $url,
            'icon' => $this->safeIcon($menu->icon),
            'active' => $this->routeIsActive($menu->url_route, $currentRouteName, $currentPath, $visibleRouteNames),
        ];
    }

    private function routeUrl(?string $routeName): ?string
    {
        $route = $routeName ? Route::getRoutes()->getByName($routeName) : null;

        return $route !== null && $route->parameterNames() === [] ? route($routeName) : null;
    }

    /**
     * @param  array<int, string|null>  $visibleRouteNames
     */
    private function routeIsActive(?string $menuRoute, string $currentRouteName, string $currentPath, array $visibleRouteNames): bool
    {
        if (! $menuRoute || $menuRoute === '#') {
            return false;
        }

        if ($currentRouteName === $menuRoute) {
            return true;
        }

        if (in_array($currentRouteName, $visibleRouteNames, true)) {
            return false;
        }

        if ($currentRouteName !== '') {
            $commonActions = ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy', 'print', 'export', 'import'];
            $currentBase = $this->routeBase($currentRouteName, $commonActions);
            $menuBase = $this->routeBase($menuRoute, $commonActions);

            if ($currentBase === $menuBase && $currentBase !== '') {
                if (Str::endsWith($menuRoute, '.index')) {
                    return true;
                }

                $firstMatchingSibling = collect($visibleRouteNames)->first(
                    fn (?string $routeName) => $routeName !== null
                        && $this->routeBase($routeName, $commonActions) === $currentBase
                );

                return $menuRoute === $firstMatchingSibling;
            }
        }

        $menuPath = ltrim($menuRoute, '/');

        return $currentRouteName === ''
            && $menuPath !== ''
            && ($currentPath === $menuPath || Str::startsWith($currentPath, $menuPath.'/'));
    }

    /**
     * @param  array<int, string>  $commonActions
     */
    private function routeBase(string $routeName, array $commonActions): string
    {
        $segments = explode('.', $routeName);
        if (in_array(end($segments), $commonActions, true)) {
            array_pop($segments);
        }

        return implode('.', $segments);
    }

    private function safeIcon(?string $icon): string
    {
        return $icon !== null && preg_match('/\A(?:fa[srb]?\s+)?fa-[a-z0-9-]+(?:\s+fa-[a-z0-9-]+)*\z/i', $icon)
            ? $icon
            : 'fas fa-folder';
    }

    /**
     * @param  array<string, bool>  $permissionKeys
     */
    private function authorized(Menu $menu, array $permissionKeys, bool $isSystemAdmin): bool
    {
        return $menu->permission_id === null
            || $isSystemAdmin
            || ($menu->permission !== null && isset($permissionKeys[$menu->permission->key]));
    }
}

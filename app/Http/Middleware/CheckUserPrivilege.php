<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Services\Authorization\PermissionRegistry;

class CheckUserPrivilege
{
    public function handle(Request $request, Closure $next)
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if (!$user) return redirect()->route('login');

        $action = $request->route()?->getActionName();
        if (!$action || !PermissionRegistry::recognizes($action)) {
            abort(403, 'Akses ditolak.');
        }

        $permission = PermissionRegistry::forAction($action);
        if ($permission !== null) {
            Gate::authorize($permission);
        }

        return $next($request);
    }
}

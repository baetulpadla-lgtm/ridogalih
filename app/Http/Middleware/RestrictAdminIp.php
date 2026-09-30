<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\IpUtils;
use Symfony\Component\HttpFoundation\Response;

class RestrictAdminIp
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! config('security.ip_restriction.enabled')) {
            return $next($request);
        }

        $allowed = config('security.ip_restriction.allowed', []);
        abort_if($allowed === [], 503, 'Administrator IP allowlist is enabled but not configured.');

        foreach ($allowed as $range) {
            if (IpUtils::checkIp($request->ip(), $range)) {
                return $next($request);
            }
        }

        abort(403, 'Akses administrator hanya tersedia dari jaringan yang diizinkan.');
    }
}

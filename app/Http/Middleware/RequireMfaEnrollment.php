<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireMfaEnrollment
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $enrollmentRequired = (bool) $request->session()->get('mfa_enrollment_required', false)
            || ($user?->isSuperAdmin() && ! $user->mfa_enabled_at);

        if ($enrollmentRequired && ! $request->routeIs('profile.security-mfa.*', 'logout')) {
            return redirect()->route('profile.security-mfa.setup');
        }

        return $next($request);
    }
}

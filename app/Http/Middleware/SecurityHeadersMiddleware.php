<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
        $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin');

        if ($request->routeIs('login.mfa', 'login.mfa.verify', 'profile.security-mfa.*')) {
            $response->headers->set('Cache-Control', 'private, no-store');
        }

        if ($request->isSecure() && app()->environment('production')) {
            $hsts = 'max-age=31536000';
            if (config('security.hsts_include_subdomains')) {
                $hsts .= '; includeSubDomains';
            }
            $response->headers->set('Strict-Transport-Security', $hsts);
        }

        if (config('security.csp.enabled')) {
            $header = config('security.csp.report_only')
                ? 'Content-Security-Policy-Report-Only'
                : 'Content-Security-Policy';

            $response->headers->set($header, $this->contentSecurityPolicy());
        }

        return $response;
    }

    private function contentSecurityPolicy(): string
    {
        $scriptSources = ["'self'", "'unsafe-inline'", 'https://cdn.jsdelivr.net', 'https://cdn.tailwindcss.com'];
        $connectSources = "connect-src 'self' https://cdn.jsdelivr.net";
        if (! app()->environment('production')) {
            $scriptSources[] = "'unsafe-eval'";
            $scriptSources[] = 'http://localhost:5173';
            $scriptSources[] = 'ws://localhost:5173';
            $connectSources .= ' http://localhost:5173 ws://localhost:5173';
        }

        $directives = [
            "default-src 'self'",
            'base-uri \'self\'',
            'object-src \'none\'',
            'frame-ancestors \'none\'',
            'form-action \'self\'',
            'script-src '.implode(' ', $scriptSources),
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com",
            "img-src 'self' data: blob: https://www.transparenttextures.com",
            "font-src 'self' data: https://fonts.gstatic.com https://cdnjs.cloudflare.com",
            $connectSources,
            "frame-src 'self'",
        ];

        if (app()->environment('production')) {
            $directives[] = 'upgrade-insecure-requests';
        }

        return implode('; ', $directives);
    }
}

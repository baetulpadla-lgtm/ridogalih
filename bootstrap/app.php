<?php

use App\Http\Middleware\CheckUserPrivilege;
use App\Http\Middleware\RequireMfaEnrollment;
use App\Http\Middleware\RestrictAdminIp;
use App\Http\Middleware\SecurityHeadersMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Session\Middleware\AuthenticateSession;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withCommands([__DIR__.'/../app/Console/Commands'])
    ->withMiddleware(function (Middleware $middleware): void {
        // HAPUS ALIAS SPATIE, CUKUP SISAKAN CUSTOM PRIVILEGE KITA
        $middleware->alias([
            'privilege' => CheckUserPrivilege::class,
            'admin.ip' => RestrictAdminIp::class,
            'mfa.enrollment' => RequireMfaEnrollment::class,
        ]);

        $middleware->append(SecurityHeadersMiddleware::class);

        $middleware->web(append: [
            AuthenticateSession::class, // <-- AKTIFKAN INI
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();

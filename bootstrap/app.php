<?php

use App\Http\Middleware\DebugSessionCookie;
use App\Http\Middleware\PreventRolePermission;
use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Session\TokenMismatchException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Behind the platform's TLS-terminating proxy
        $middleware->trustProxies(at: '*');

        // Must be in the web group so it runs AFTER StartSession
        $middleware->web(append: [
            SetLocale::class,
            DebugSessionCookie::class,
        ]);

        $middleware->alias([
            'prevent.permission' => PreventRolePermission::class,
        ]);

        $middleware->redirectUsersTo('/admin');

        $middleware->validateCsrfTokens(except: [
            'rsvp/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // TokenMismatchException is ignored by default, so un-ignore it
        $exceptions->stopIgnoring(TokenMismatchException::class);

        $exceptions->report(function (TokenMismatchException $e) {
            logger()->error('CSRF TOKEN MISMATCH', [
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'session_id' => request()->hasSession() ? request()->session()->getId() : null,
                'has_session' => request()->hasSession(),
                'has_cookie' => request()->hasCookie(config('session.cookie')),
                'session_token_exists' => request()->hasSession() && request()->session()->has('_token'),
                'request_token_exists' => request()->input('_token') !== null,
                'csrf_header_exists' => request()->header('X-CSRF-TOKEN') !== null,
                'origin' => request()->header('Origin'),
                'referer' => request()->header('Referer'),
            ]);
        });
    })
    ->create();

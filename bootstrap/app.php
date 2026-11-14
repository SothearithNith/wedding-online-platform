<?php

use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    // ->withMiddleware(function (Middleware $middleware) {
    //     $middleware->trustProxies(
    //         at: '*',
    //         headers: Request::HEADER_X_FORWARDED_FOR
    //             | Request::HEADER_X_FORWARDED_HOST
    //             | Request::HEADER_X_FORWARDED_PORT
    //             | Request::HEADER_X_FORWARDED_PROTO
    //     );

    //     $middleware->web(append: [
    //         SetLocale::class,
    //     ]);

    //     $middleware->redirectUsersTo('/admin');

    //     $middleware->validateCsrfTokens(except: [
    //         'rsvp/*',
    //     ]);
    // })
    // ->withExceptions(function (Exceptions $exceptions): void {
    //     //
    // })->create();
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(
            at: '*',
            headers: Request::HEADER_X_FORWARDED_FOR
                | Request::HEADER_X_FORWARDED_HOST
                | Request::HEADER_X_FORWARDED_PORT
                | Request::HEADER_X_FORWARDED_PROTO
        );

        $middleware->web(append: [
            SetLocale::class,
        ]);

        $middleware->redirectUsersTo('/admin');

        $middleware->validateCsrfTokens(except: [
            'rsvp/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->report(function (\Throwable $e) {
            if ($e instanceof \Illuminate\Session\TokenMismatchException) {
                logger()->error('CSRF TOKEN MISMATCH', [
                    'url' => request()->fullUrl(),
                    'method' => request()->method(),
                    'session_id' => session()->getId(),
                    'has_session' => request()->hasSession(),
                    'has_cookie' => request()->hasCookie(
                        config('session.cookie')
                    ),
                    'session_token_exists' => session()->has('_token'),
                    'request_token_exists' => request()->input('_token') !== null,
                    'csrf_header_exists' => request()->header('X-CSRF-TOKEN') !== null,
                ]);
            }
        });
    })
    ->create();

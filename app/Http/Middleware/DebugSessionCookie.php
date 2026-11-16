<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class DebugSessionCookie
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        Log::info('SESSION COOKIE DEBUG', [
            'url' => $request->fullUrl(),
            'session_id' => $request->hasSession()
                ? $request->session()->getId()
                : null,
            'set_cookie' => $response->headers->all('set-cookie'),
            'cookie_name' => config('session.cookie'),
        ]);

        return $response;
    }
}

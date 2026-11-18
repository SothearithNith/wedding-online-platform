<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventRolePermission
{
    public function handle(Request $request, Closure $next, string $module, string $action): Response
    {
        $user = $request->user();

        abort_unless($user, 403);

        // Master Admin bypass
        if ($user->hasRole('Master Admin')) {
            return $next($request);
        }

        abort_unless($user->can("{$module}.{$action}"), 403);

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;

class PreventRolePermission
{
    public function handle(Request $request, Closure $next, string $feature, string $action): Response
    {
        if (!auth()->check()) {
            abort(401);
        }

        $user = auth()->user();

        // Master Admin
        if ($user->role_id == 1) {
            return $next($request);
        }

        $role = Role::with('permissions')->find($user->role_id);

        if (!$role) {
            abort(403);
        }

        $permission = $feature . '.' . $action;

        if (!$role->permissions->contains('name', $permission)) {
            abort(403, 'You do not have permission to access this feature.');
        }

        return $next($request);
    }
}

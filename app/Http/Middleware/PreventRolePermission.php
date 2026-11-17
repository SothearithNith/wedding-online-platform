<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PreventRolePermission
{
    private array $routeGroups = [

        // Dashboard
        'dashboard.view' => [
            'admin',
        ],

        // Wedding Management
        'weddings.view' => [
            'weddings.index',
        ],

        'weddings.create' => [
            'weddings.create',
        ],

        'weddings.edit' => [
            'weddings.edit',
        ],

        // Template Management
        'templates.view' => [
            'templates.index',
        ],

        'templates.create' => [
            'templates.create',
        ],

        'templates.edit' => [
            'templates.edit',
        ],

        // User Management
        'users.view' => [
            'access-control.users.index',
        ],

        'users.create' => [
            'access-control.users.create',
        ],

        'users.edit' => [
            'access-control.users.edit',
        ],

        // Role Management
        'roles.view' => [
            'access-control.roles.index',
        ],

        'roles.create' => [
            'access-control.roles.create',
        ],

        'roles.edit' => [
            'access-control.roles.edit',
        ],

        'roles.manage' => [
            'access-control.roles.permissions',
        ],

        // Feature Management
        'features.view' => [
            'access-control.features.index',
        ],

        // RSVP Management
        'rsvp.view' => [
            'rsvp.index',
            'rsvp.detail',
        ],
    ];

    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            abort(401);
        }

        $user = Auth::user();

        // Get all permissions assigned to user's roles
        $permissions = $user->getAllPermissions()
            ->pluck('name')
            ->toArray();

        // Current route name
        $routeName = $request->route()?->getName();

        $allowed = false;

        foreach ($permissions as $permission) {

            // Direct permission match
            if ($permission === $routeName) {
                $allowed = true;
                break;
            }

            // Route group match
            if (
                isset($this->routeGroups[$permission]) &&
                in_array($routeName, $this->routeGroups[$permission], true)
            ) {
                $allowed = true;
                break;
            }
        }

        if (!$allowed) {
            abort(403, 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}

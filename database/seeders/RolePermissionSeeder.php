<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Dashboard
            'dashboard.view',

            // Weddings
            'weddings.view',
            'weddings.create',
            'weddings.edit',
            'weddings.delete',

            // Templates
            'templates.view',
            'templates.create',
            'templates.edit',
            'templates.delete',

            // Invitations
            'invitations.view',
            'invitations.create',
            'invitations.edit',
            'invitations.delete',

            // Users
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',

            // Access Control
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',
            'permissions.view',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        // Roles
        $masterAdmin = Role::firstOrCreate([
            'name' => 'Master Admin',
            'guard_name' => 'web',
        ]);

        $admin = Role::firstOrCreate([
            'name' => 'Admin',
            'guard_name' => 'web',
        ]);

        $staff = Role::firstOrCreate([
            'name' => 'Staff',
            'guard_name' => 'web',
        ]);

        // Master Admin gets all permissions
        $masterAdmin->syncPermissions(Permission::all());

        // Admin permissions
        $admin->syncPermissions([
            'dashboard.view',
            'weddings.view',
            'weddings.create',
            'weddings.edit',
            'templates.view',
            'invitations.view',
            'invitations.create',
            'invitations.edit',
            'users.view',
        ]);

        // Staff permissions
        $staff->syncPermissions([
            'dashboard.view',
            'weddings.view',
            'invitations.view',
        ]);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}

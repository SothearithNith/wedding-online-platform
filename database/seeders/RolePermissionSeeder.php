<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    // public function run(): void
    // {
    //     app(PermissionRegistrar::class)->forgetCachedPermissions();

    //     $modules = [
    //         'dashboard',
    //         'weddings',
    //         'temples',
    //         'templates',
    //         'users',
    //         'roles',
    //         'permissions',
    //         'settings',
    //     ];

    //     $actions = [
    //         'view',
    //         'create',
    //         'edit',
    //         'delete',
    //         'manage',
    //     ];

    //     foreach ($modules as $module) {
    //         foreach ($actions as $action) {
    //             Permission::firstOrCreate([
    //                 'name' => "{$module}.{$action}",
    //                 'guard_name' => 'web',
    //             ]);
    //         }
    //     }

    //     $masterAdmin = Role::firstOrCreate([
    //         'name' => 'Master Admin',
    //         'guard_name' => 'web',
    //     ]);

    //     $admin = Role::firstOrCreate([
    //         'name' => 'Admin',
    //         'guard_name' => 'web',
    //     ]);

    //     $user = Role::firstOrCreate([
    //         'name' => 'User',
    //         'guard_name' => 'web',
    //     ]);

    //     // Assign every existing permission.
    //     $masterAdmin->syncPermissions(Permission::all());

    //     app(PermissionRegistrar::class)->forgetCachedPermissions();
    // }
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // Create Master Admin FIRST so it gets id 1 on a fresh database.
        // The second array is only used when the role is created.
        $masterAdmin = Role::firstOrCreate(
            ['name' => 'Master Admin', 'guard_name' => 'web'],
            ['status' => true, 'is_public' => false]
        );

        $admin = Role::firstOrCreate(
            ['name' => 'Admin', 'guard_name' => 'web'],
            ['status' => true, 'is_public' => true]
        );

        $user = Role::firstOrCreate(
            ['name' => 'User', 'guard_name' => 'web'],
            ['status' => true, 'is_public' => true]
        );

        $modules = ['dashboard', 'weddings', 'temples', 'templates', 'users', 'roles', 'permissions', 'settings'];
        $actions = ['view', 'create', 'edit', 'delete', 'manage'];

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                Permission::firstOrCreate([
                    'name' => "{$module}.{$action}",
                    'guard_name' => 'web',
                ]);
            }
        }

        // Master Admin gets everything (Gate::before also bypasses checks for it).
        $masterAdmin->syncPermissions(Permission::all());

        // Starter defaults only. Change them later from the Apply permission page.
        // Use givePermissionTo, not syncPermissions, so re-running the seeder
        // never removes permissions you changed in the UI.
        $admin->givePermissionTo(
            Permission::where('guard_name', 'web')
                ->where(function ($q) {
                    foreach (['dashboard', 'weddings', 'temples', 'templates'] as $m) {
                        $q->orWhere('name', 'like', "{$m}.%");
                    }
                })
                ->get()
        );

        $user->givePermissionTo('dashboard.view');

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}

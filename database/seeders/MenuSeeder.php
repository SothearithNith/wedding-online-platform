<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            [
                'name' => 'Dashboard',
                'slug' => 'dashboard',
                'icon' => 'bi-grid',
                'route_name' => 'dashboard',
                'permission_name' => 'dashboard.view',
                'sort_order' => 1,
            ],
            [
                'name' => 'Weddings',
                'slug' => 'weddings',
                'icon' => 'bi-heart',
                'route_name' => 'weddings.index',
                'permission_name' => 'weddings.view',
                'sort_order' => 2,
            ],
            [
                'name' => 'Temples',
                'slug' => 'temples',
                'icon' => 'bi-building',
                'route_name' => 'temples.index',
                'permission_name' => 'temples.view',
                'sort_order' => 3,
            ],
            [
                'name' => 'Templates',
                'slug' => 'templates',
                'icon' => 'bi-palette',
                'route_name' => 'templates.index',
                'permission_name' => 'templates.view',
                'sort_order' => 4,
            ],
            [
                'name' => 'Users',
                'slug' => 'users',
                'icon' => 'bi-people',
                'route_name' => 'users.index',
                'permission_name' => 'users.view',
                'sort_order' => 5,
            ],
            [
                'name' => 'Roles',
                'slug' => 'roles',
                'icon' => 'bi-shield-lock',
                'route_name' => 'roles.index',
                'permission_name' => 'roles.view',
                'sort_order' => 6,
            ],
            [
                'name' => 'Settings',
                'slug' => 'settings',
                'icon' => 'bi-gear',
                'route_name' => 'settings.index',
                'permission_name' => 'settings.view',
                'sort_order' => 7,
            ],
        ];

        foreach ($menus as $menu) {
            Menu::updateOrCreate(
                ['slug' => $menu['slug']],
                [...$menu, 'is_active' => true]
            );
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            [
                'name_en' => 'Dashboard',
                'name_km' => 'ផ្ទាំងគ្រប់គ្រង',
                'slug' => 'admin',
                'icon' => 'heroicon-o-squares-2x2',
                'route_name' => 'admin',
                'permission_name' => 'admin.view',
                'sort_order' => 1,
            ],
            [
                'name_en' => 'Weddings',
                'name_km' => 'ពិធីមង្គលការ',
                'slug' => 'weddings',
                'icon' => 'heroicon-o-heart',
                'route_name' => 'weddings.index',
                'permission_name' => 'weddings.view',
                'sort_order' => 2,
            ],
            [
                'name_en' => 'Temples',
                'name_km' => 'វត្តអារាម',
                'slug' => 'temples',
                'icon' => 'heroicon-o-building-library',
                'route_name' => 'temples.index',
                'permission_name' => 'temples.view',
                'sort_order' => 3,
            ],
            [
                'name_en' => 'Templates',
                'name_km' => 'គំរូអញ្ជើញ',
                'slug' => 'templates',
                'icon' => 'heroicon-o-swatch',
                'route_name' => 'templates.index',
                'permission_name' => 'templates.view',
                'sort_order' => 4,
            ],
            [
                // Parent group (no route of its own)
                'name_en' => 'Access control',
                'name_km' => 'ការគ្រប់គ្រងសិទ្ធិ',
                'slug' => 'access-control',
                'icon' => 'heroicon-o-shield-check',
                'route_name' => null,
                'permission_name' => null,
                'sort_order' => 5,
                'children' => [
                    [
                        'name_en' => 'Users',
                        'name_km' => 'អ្នកប្រើប្រាស់',
                        'slug' => 'users',
                        'icon' => null,
                        'route_name' => 'users.index',
                        'permission_name' => 'users.view',
                        'sort_order' => 1,
                    ],
                    [
                        'name_en' => 'Roles',
                        'name_km' => 'តួនាទី',
                        'slug' => 'roles',
                        'icon' => null,
                        'route_name' => 'roles.index',
                        'permission_name' => 'roles.view',
                        'sort_order' => 2,
                    ],
                ],
            ],
            [
                'name_en' => 'Settings',
                'name_km' => 'ការកំណត់',
                'slug' => 'settings',
                'icon' => 'heroicon-o-cog-6-tooth',
                'route_name' => 'settings.index',
                'permission_name' => 'settings.view',
                'sort_order' => 6,
            ],
        ];

        foreach ($menus as $item) {
            $children = $item['children'] ?? [];
            unset($item['children']);

            $parent = Menu::updateOrCreate(
                ['slug' => $item['slug']],
                [...$item, 'parent_id' => null, 'is_active' => true]
            );

            foreach ($children as $child) {
                Menu::updateOrCreate(
                    ['slug' => $child['slug']],
                    [...$child, 'parent_id' => $parent->id, 'is_active' => true]
                );
            }
        }
    }
}

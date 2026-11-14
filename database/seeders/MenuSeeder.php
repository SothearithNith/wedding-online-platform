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
                'name_en' => 'Templates',
                'name_km' => 'គំរូអញ្ជើញ',
                'slug' => 'templates',
                'icon' => 'heroicon-o-swatch',
                'route_name' => 'templates.index',
                'permission_name' => 'templates.view',
                'sort_order' => 2,
            ],
            [
                'name_en' => 'Weddings',
                'name_km' => 'ពិធីមង្គលការ',
                'slug' => 'weddings',
                'icon' => 'heroicon-o-heart',
                'route_name' => 'weddings.index',
                'permission_name' => 'weddings.view',
                'sort_order' => 3,
            ],
            [
                'name_en' => 'RSVP',
                'name_km' => 'ការឆ្លើយតប',
                'slug' => 'rsvp',
                'icon' => 'heroicon-o-envelope',
                'route_name' => 'rsvp.index',
                'permission_name' => 'rsvp.view',
                'sort_order' => 4,
            ],
            [
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
                        'route_name' => 'access-control.users.index',
                        'permission_name' => 'users.view',
                        'sort_order' => 1,
                    ],
                    [
                        'name_en' => 'Roles',
                        'name_km' => 'តួនាទី',
                        'slug' => 'roles',
                        'icon' => null,
                        'route_name' => 'access-control.roles.index',
                        'permission_name' => 'roles.view',
                        'sort_order' => 2,
                    ],
                    [
                        'name_en' => 'Features',
                        'name_km' => 'លក្ខណៈពិសេស',
                        'slug' => 'features',
                        'icon' => null,
                        'route_name' => 'access-control.features.index',
                        'permission_name' => 'features.view',
                        'sort_order' => 3,
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

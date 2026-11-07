<?php

namespace Database\Seeders;

use App\Models\Template;
use Illuminate\Database\Seeder;

class TemplateSeeder extends Seeder
{
    public function run(): void
    {
        Template::updateOrCreate(['key' => 'gold'], [
            'name' => 'Gold Classic',
            'preview_url' => '/previews/gold.jpg',
            'schema' => [
                'sections' => ['intro', 'count', 'story', 'schedule', 'gallery', 'dressCode', 'gift', 'rsvp', 'thanks'],
                'extra' => [],
            ],
        ]);

        // Example of a future template with template-specific fields (stored in invitations.extra)
        Template::updateOrCreate(['key' => 'rose'], [
            'name' => 'Rose Garden',
            'preview_url' => '/previews/rose.jpg',
            'schema' => [
                'sections' => ['intro', 'story', 'schedule', 'gallery', 'rsvp', 'thanks'],
                'extra' => [
                    ['key' => 'introVideo', 'type' => 'url',   'label' => 'Intro video URL'],
                    ['key' => 'accent',     'type' => 'color', 'label' => 'Accent color'],
                ],
            ],
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\InvitationBackground;
use Illuminate\Database\Seeder;

class InvitationBackgroundSeeder extends Seeder
{
    public function run(): void
    {
        // files live in storage/app/public/backgrounds/gold/*.jpg  (php artisan storage:link)
        $rows = [
            ['all',      'Gold Royal',      'backgrounds/gold/all.jpg',      true],
            ['card',     'Hero Angkor',     'backgrounds/gold/card.jpg',     true],
            ['intro',    'Couple Garden',   'backgrounds/gold/intro.jpg',    true],
            ['story',    'Story Lotus',     'backgrounds/gold/story.jpg',    true],
            ['gallery',  'Gallery Kbach',   'backgrounds/gold/gallery.jpg',  true],
            ['rsvp',     'RSVP Silk',       'backgrounds/gold/rsvp.jpg',     true],
            ['gift',     'Gift Gold',       'backgrounds/gold/gift.jpg',     true],
            ['apology',  'Apology Petals',  'backgrounds/gold/apology.jpg',  true],
            ['thanks',   'Thanks Sunset',   'backgrounds/gold/thanks.jpg',   true],
        ];
        foreach ($rows as $i => [$section, $name, $path, $default]) {
            InvitationBackground::updateOrCreate(
                ['template' => 'gold', 'section' => $section, 'path' => $path],
                ['name' => $name, 'is_default' => $default, 'sort' => $i]
            );
        }
    }
}

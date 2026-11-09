<?php

namespace Database\Seeders;

use App\Models\Template;
use App\Support\TemplateRegistry;
use Illuminate\Database\Seeder;

class TemplateSeeder extends Seeder
{
    public function run(): void
    {
        Template::updateOrCreate(['key' => 'gold'], [
            'name'        => 'Gold',
            'description' => 'Khmer wedding invitation in gold and burgundy.',
            'schema'      => ['sections' => [
                'cover',
                'intro',
                'couple',
                'countdown',
                'story',
                'schedule',
                'gallery',
                'dressCode',
                'gift',
                'rsvp',
                'thanks',
            ]],
            'sample_data' => TemplateRegistry::sample(),
            'is_active'   => true,
            'is_public'   => true,
            'sort'        => 1,
        ]);

        Template::updateOrCreate(['key' => 'lotus'], [
            'name'        => 'Lotus',
            'description' => 'Elegant Khmer wedding invitation inspired by lotus flowers.',
            'schema'      => ['sections' => [
                'cover',
                'intro',
                'couple',
                'countdown',
                'story',
                'schedule',
                'gallery',
                'dressCode',
                'gift',
                'rsvp',
                'thanks',
            ]],
            'sample_data' => TemplateRegistry::sample(),
            'is_active'   => true,
            'is_public'   => true,
            'sort'        => 2,
        ]);
    }
}

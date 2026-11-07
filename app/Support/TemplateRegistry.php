<?php

namespace App\Support;

use Illuminate\Support\Arr;

class TemplateRegistry
{
    /** public/assets/images/default/ */
    public const DEFAULT_DIR = 'assets/images/default';

    /** data path => default file name */
    public const DEFAULT_IMAGES = [
        'cover'           => 'cover.jpg',
        'groom.photo'     => 'groom.jpg',
        'bride.photo'     => 'bride.jpg',
        'gift.qr'         => 'qr.jpg',
        'backgrounds.all' => 'background.jpg',
    ];

    public const DEFAULT_GALLERY = ['gallery-1.jpg', 'gallery-2.jpg', 'gallery-3.jpg', 'gallery-4.jpg'];

    public static function viewName(string $key): string
    {
        return "invitation-templates.{$key}.{$key}";
    }

    // Falls back to the "gold" view so a template created in admin always renders
    public static function viewFor(string $key): string
    {
        $view = self::viewName($key);

        return view()->exists($view) ? $view : self::viewName('gold');
    }

    // Loads database/data/sample-invitation.json and unwraps {templateId,label,data}
    public static function sample(): array
    {
        $path = database_path('data/sample-invitation.json');

        $json = is_file($path)
            ? (json_decode(file_get_contents($path), true) ?? [])
            : [];

        return $json['data'] ?? $json;
    }

    /** URL of a file in public/assets/images/default */
    public static function defaultUrl(string $file): string
    {
        return asset(self::DEFAULT_DIR . '/' . $file);
    }

    /** Default image URL for a data path such as "groom.photo", or null */
    public static function defaultFor(string $path): ?string
    {
        $file = self::DEFAULT_IMAGES[$path] ?? null;

        return $file ? self::defaultUrl($file) : null;
    }

    /**
     * Fills empty image fields with the default images.
     * Gallery defaults are only added when $gallery is true (admin preview).
     */
    public static function withDefaults(array $data, bool $gallery = false): array
    {
        foreach (self::DEFAULT_IMAGES as $path => $file) {
            // Only show the gift QR default when a gift section exists
            if (str_starts_with($path, 'gift.') && empty($data['gift'])) {
                continue;
            }

            if (blank(Arr::get($data, $path))) {
                Arr::set($data, $path, self::defaultUrl($file));
            }
        }

        if ($gallery && empty($data['gallery'])) {
            $data['gallery'] = array_map(fn($f) => self::defaultUrl($f), self::DEFAULT_GALLERY);
        }

        return $data;
    }
}

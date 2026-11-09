<?php

namespace App\Support;

use Illuminate\Support\Facades\View;

class TemplateRegistry
{
    public const DEFAULT_DIR = 'assets/images/default';

    public const DEFAULT_IMAGES = [
        'cover' => 'cover.jpg',
        'groom.photo' => 'groom.jpg',
        'bride.photo' => 'bride.jpg',
        'gift.qr' => 'qr.jpg',
        'backgrounds.all' => 'background.jpg',
    ];

    public const DEFAULT_GALLERY = [
        'gallery-1.jpg',
        'gallery-2.jpg',
        'gallery-3.jpg',
        'gallery-4.jpg',
    ];

    public static function viewName(string $key): string
    {
        return "invitation-templates.{$key}.{$key}";
    }

    public static function viewFor(?string $key): string
    {
        $key = is_string($key) && preg_match('/^[a-z0-9_-]+$/i', $key)
            ? $key
            : 'gold';

        $view = self::viewName($key);

        return View::exists($view) ? $view : self::viewName('gold');
    }

    public static function sample(): array
    {
        $path = database_path('data/sample-invitation.json');

        if (! is_file($path)) {
            return [];
        }

        $json = json_decode(file_get_contents($path), true);

        if (! is_array($json)) {
            return [];
        }

        return is_array($json['data'] ?? null) ? $json['data'] : $json;
    }

    public static function defaultUrl(string $file): string
    {
        return asset(self::DEFAULT_DIR . '/' . ltrim($file, '/'));
    }

    public static function defaultFor(string $path): ?string
    {
        $file = self::DEFAULT_IMAGES[$path] ?? null;

        return $file ? self::defaultUrl($file) : null;
    }

    public static function withDefaults(array $data, bool $gallery = false): array
    {
        foreach (self::DEFAULT_IMAGES as $path => $file) {
            if (str_starts_with($path, 'gift.') && empty($data['gift'])) {
                continue;
            }

            if (blank(data_get($data, $path))) {
                data_set($data, $path, self::defaultUrl($file));
            }
        }

        if ($gallery && empty($data['gallery'])) {
            $data['gallery'] = array_map(
                fn (string $file) => self::defaultUrl($file),
                self::DEFAULT_GALLERY
            );
        }

        return $data;
    }
}

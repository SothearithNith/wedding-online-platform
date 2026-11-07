<?php

if (! function_exists('trans_json')) {
    function trans_json(string $key): string
    {
        static $translations = null;

        if ($translations === null) {
            $path = base_path('lang.json');

            $translations = file_exists($path)
                ? json_decode(file_get_contents($path), true) ?? []
                : [];
        }

        $locale = app()->getLocale();

        if ($locale === 'km') {
            return $translations[$key] ?? $key;
        }

        return $key;
    }
}
 
<?php

namespace App\Support;

class TemplateRegistry
{
    /**
     * resources/views/invitation-templates/{key}/{key}.blade.php
     */
    public static function viewName(string $key): string
    {
        return "invitation-templates.{$key}.{$key}";
    }

    /**
     * Same as viewName(), but falls back to the "gold" view so a template
     * created in the admin panel always renders something.
     */
    public static function viewFor(string $key): string
    {
        $view = self::viewName($key);

        return view()->exists($view) ? $view : self::viewName('gold');
    }

    /**
     * Loads database/data/sample-invitation.json and unwraps
     * {templateId, label, data} into just the inner "data" array,
     * which is what the Blade views expect.
     */
    public static function sample(): array
    {
        $path = database_path('data/sample-invitation.json');

        $json = is_file($path)
            ? (json_decode(file_get_contents($path), true) ?? [])
            : [];

        return $json['data'] ?? $json;
    }
}

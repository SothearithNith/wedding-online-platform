<?php

namespace App\Support;

use App\Models\InvitationBackground;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class InvitationBackgrounds
{
    /**
     * Default background per section for a template: ['all' => path, 'story' => path, ...]
     * Used by gold.blade.php as a fallback when the invitation has not picked its own.
     */
    public static function library(string $template, string $eventType = 'wedding'): array
    {
        return Cache::remember("inv-bg:$template:$eventType", 300, function () use ($template, $eventType) {
            try {
                if (! Schema::hasTable('invitation_backgrounds')) {
                    return [];
                }
                return InvitationBackground::active()
                    ->where('template', $template)
                    ->where('event_type', $eventType)
                    ->where('is_default', true)
                    ->orderBy('sort')
                    ->get()
                    ->unique('section')
                    ->pluck('path', 'section')
                    ->all();
            } catch (\Throwable $e) {
                return [];
            }
        });
    }

    /** Every choice the customer can pick from, grouped by section (for the editor UI). */
    public static function options(string $template, string $eventType = 'wedding'): array
    {
        return InvitationBackground::active()
            ->where('template', $template)
            ->where('event_type', $eventType)
            ->orderBy('sort')
            ->get(['id', 'section', 'name', 'path'])
            ->groupBy('section')
            ->map->values()
            ->all();
    }

    public static function forget(string $template, string $eventType = 'wedding'): void
    {
        Cache::forget("inv-bg:$template:$eventType");
    }
}

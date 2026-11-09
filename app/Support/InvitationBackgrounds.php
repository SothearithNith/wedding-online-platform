<?php

namespace App\Support;

use App\Models\InvitationBackground;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Throwable;

class InvitationBackgrounds
{
    private static function cacheKey(string $template, string $eventType): string
    {
        return "inv-bg:{$template}:{$eventType}";
    }

    public static function library(
        string $template,
        string $eventType = 'wedding'
    ): array {
        return Cache::remember(
            self::cacheKey($template, $eventType),
            300,
            function () use ($template, $eventType) {
                try {
                    if (! Schema::hasTable('invitation_backgrounds')) {
                        return [];
                    }

                    return InvitationBackground::query()
                        ->active()
                        ->where('template', $template)
                        ->where('event_type', $eventType)
                        ->where('is_default', true)
                        ->orderBy('sort')
                        ->get()
                        ->unique('section')
                        ->pluck('path', 'section')
                        ->all();
                } catch (Throwable $e) {
                    report($e);

                    return [];
                }
            }
        );
    }

    public static function options(
        string $template,
        string $eventType = 'wedding'
    ): array {
        try {
            if (! Schema::hasTable('invitation_backgrounds')) {
                return [];
            }

            return InvitationBackground::query()
                ->active()
                ->where('template', $template)
                ->where('event_type', $eventType)
                ->orderBy('sort')
                ->get(['id', 'section', 'name', 'path'])
                ->groupBy('section')
                ->map(fn ($items) => $items->values())
                ->all();
        } catch (Throwable $e) {
            report($e);

            return [];
        }
    }

    public static function forget(
        string $template,
        string $eventType = 'wedding'
    ): void {
        Cache::forget(self::cacheKey($template, $eventType));
    }
}

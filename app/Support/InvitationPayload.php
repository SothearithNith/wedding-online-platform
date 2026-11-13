<?php

namespace App\Support;

use App\Models\Invitation;

class InvitationPayload
{
    public static function from(Invitation $invitation): array
    {
        $invitation->loadMissing([
            'template',
            'people',
            'events',
            'stories',
            'gallery',
        ]);

        $person = fn (string $role) => $invitation->people
            ->firstWhere('role', $role);

        $groom = $person('groom');
        $bride = $person('bride');

        return [
            'templateId' => $invitation->template?->key ?? 'gold',
            'label' => $invitation->label,

            'data' => [
                'groom' => [
                    'name' => $groom?->name,
                    'dad' => $groom?->dad,
                    'mom'=>$groom?->mom,
                    'photo' => $groom?->photo,
                ],
                'bride' => [
                    'name' => $bride?->name,
                    'dad' => $bride?->dad,
                    'mom' => $bride?->mom,
                    'photo' => $bride?->photo,
                ],

                'date' => $invitation->event_date?->toIso8601String(),
                'dateText' => $invitation->date_text,
                'intro' => $invitation->intro,
                'quote' => $invitation->quote,
                'cover' => $invitation->cover,

                'events' => $invitation->events
                    ->map(fn ($event) => [
                        'time' => $event->time,
                        'title' => $event->title,
                        'place' => $event->place,
                        'mapUrl' => $event->map_url,
                    ])
                    ->values()
                    ->all(),

                'gallery' => $invitation->gallery
                    ->pluck('url')
                    ->values()
                    ->all(),

                'story' => $invitation->stories
                    ->map(fn ($story) => [
                        'year' => $story->year,
                        'title' => $story->title,
                        'text' => $story->text,
                    ])
                    ->values()
                    ->all(),

                'dressCode' => $invitation->dress_code ?? [],
                'gift' => $invitation->gift ?? [],
                'apology' => $invitation->apology,
                'thanks' => $invitation->thanks,
                'music' => $invitation->music,
                'backgrounds' => $invitation->backgrounds ?? [],
            ],
        ];
    }
}

<?php

namespace App\Support;

use App\Models\Invitation;

class InvitationPayload
{
    public static function from(Invitation $inv): array
    {
        $inv->loadMissing(['template', 'people', 'events', 'stories', 'gallery']);

        $person = fn(string $role) => optional($inv->people->firstWhere('role', $role));

        return [
            'templateId' => $inv->template?->key,
            'label'      => $inv->label,
            'data' => [
                'groom' => [
                    'name'    => $person('groom')->name,
                    'parents' => $person('groom')->parents,
                    'photo'   => $person('groom')->photo,
                ],
                'bride' => [
                    'name'    => $person('bride')->name,
                    'parents' => $person('bride')->parents,
                    'photo'   => $person('bride')->photo,
                ],
                'date'      => $inv->event_date?->toIso8601String(),
                'dateText'  => $inv->date_text,
                'intro'     => $inv->intro,
                'quote'     => $inv->quote,
                'cover'     => $inv->cover,
                'events'    => $inv->events->map(fn($e) => [
                    'time'   => $e->time,
                    'title'  => $e->title,
                    'place'  => $e->place,
                    'mapUrl' => $e->map_url,
                ])->all(),
                'gallery'   => $inv->gallery->pluck('url')->all(),
                'story'     => $inv->stories->map(fn($s) => [
                    'year'  => $s->year,
                    'title' => $s->title,
                    'text'  => $s->text,
                ])->all(),
                'dressCode'   => $inv->dress_code ?? [],
                'gift'        => $inv->gift ?? [],
                'apology'     => $inv->apology,
                'thanks'      => $inv->thanks,
                'music'       => $inv->music,
                'backgrounds' => $inv->backgrounds ?? [],
            ],
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Invitation extends Model
{
    public const RELATIONS = ['template', 'people', 'events', 'stories', 'gallery'];
    public const RESERVED = [
        'login',
        'logout',
        'register',
        'password',
        'forgot-password',
        'reset-password',
        'verify-email',
        'email',
        'confirm-password',
        'dashboard',
        'admin',
        'api',
        'templates',
        'weddings',
        'profile',
        'settings',
        'storage',
        'livewire',
        'up',
        'build',
        'assets',
        'vendor',
        'wedding',
        'i',
    ];

    private const STRING_COLUMNS = [
        'intro' => 'intro',
        'quote' => 'quote',
        'apology' => 'apology',
        'thanks' => 'thanks',
        'cover' => 'cover',
        'music' => 'music',
        'date_text' => 'dateText',
    ];

    private const ARRAY_COLUMNS = ['dress_code' => 'dressCode', 'gift' => 'gift', 'backgrounds' => 'backgrounds'];
    private const PERSON_FIELDS = ['name', 'parents', 'photo'];
    private const LIST_ALIASES = [
        'events'  => ['events', 'schedule'],
        'stories' => ['story', 'stories', 'timeline'],
    ];

    protected $fillable = [
        'user_id',
        'template_id',
        'label',
        'slug',
        'event_date',
        'date_text',
        'intro',
        'quote',
        'apology',
        'thanks',
        'cover',
        'music',
        'dress_code',
        'gift',
        'backgrounds',
        'extra',
        'status',
        'published_at',
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'published_at' => 'datetime',
        'dress_code'=> 'array',
        'gift' => 'array',
        'backgrounds'  => 'array',
        'extra' => 'array',
    ];

    protected static function booted(): void
    {
        // Deleting an invitation also deletes its children and the files the user uploaded for it.
        static::deleting(function (Invitation $invitation) {
            $invitation->deleteFiles();

            $invitation->people()->delete();
            $invitation->events()->delete();
            $invitation->stories()->delete();
            $invitation->gallery()->delete();
            $invitation->rsvps()->delete();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    public function people(): HasMany
    {
        return $this->hasMany(InvitationPerson::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(InvitationEvent::class)->orderBy('sort');
    }

    public function stories(): HasMany
    {
        return $this->hasMany(InvitationStory::class)->orderBy('sort');
    }

    public function gallery(): HasMany
    {
        return $this->hasMany(InvitationGallery::class)->orderBy('sort');
    }

    public function rsvps(): HasMany
    {
        return $this->hasMany(Rsvp::class);
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function person(string $role): ?InvitationPerson
    {
        return $this->people->firstWhere('role', $role);
    }

    public function getGroomNameAttribute(): string
    {
        return (string) $this->person('groom')?->name;
    }

    public function getBrideNameAttribute(): string
    {
        return (string) $this->person('bride')?->name;
    }

    public function getTemplateKeyAttribute(): string
    {
        return (string) $this->template?->key;
    }

    public function getTitleAttribute(): string
    {
        return $this->label ?: trim($this->groom_name . ' & ' . $this->bride_name, ' &') ?: (string) $this->slug;
    }

    public function getUrlAttribute(): string
    {
        return route('wedding.show', ['slug' => $this->slug]);
    }

    /** "Sok Dara" -> "sok-dara", "សុខ ដារ៉ា" -> "សុខ-ដារ៉ា". May return ''. */
    public static function makeSlug(string $text): string
    {
        $s = mb_strtolower(trim($text));
        $s = preg_replace('/[\x{200B}-\x{200D}\x{FEFF}]/u', '', $s);   // zero-width chars (common in Khmer text)
        $s = preg_replace('/[^\p{L}\p{M}\p{N}]+/u', '-', $s);           // anything else -> "-"
        $s = trim((string) $s, '-');

        return mb_substr($s, 0, 120);
    }

    /** Same as makeSlug but guaranteed to be free: sok-dara, sok-dara-2, sok-dara-3 ... */
    public static function uniqueSlug(string $text, ?int $ignoreId = null): string
    {
        $base = self::makeSlug($text) ?: 'wedding';
        $slug = $base;
        $n  = 2;

        while (
            in_array($slug, self::RESERVED, true)
            || self::where('slug', $slug)
            ->when($ignoreId, fn($q) => $q->whereKeyNot($ignoreId))
            ->exists()
        ) {
            $slug = $base . '-' . $n++;
        }

        return $slug;
    }

    /** Which key does this template use for a list? ('events' or 'schedule', ...) */
    public static function listKey(string $logical, ?array $shape = null): string
    {
        $aliases = self::LIST_ALIASES[$logical];

        foreach ($aliases as $alias) {
            if (is_array($shape) && array_key_exists($alias, $shape)) {
                return $alias;
            }
        }

        return $aliases[0];
    }

    public function toTemplateData(bool $aliases = false): array
    {
        $this->loadMissing(['template', 'people', 'events', 'stories', 'gallery']);

        $shape = (array) ($this->template?->sample_data ?? []);
        $data  = (array) ($this->extra ?? []);

        foreach (self::STRING_COLUMNS as $col => $key) {
            $v = $this->{$col};

            if ($v !== null && $v !== '') {
                $data[$key] = $v;
            } elseif (! isset($data[$key]) || ! is_string($data[$key])) {
                $data[$key] = '';
            }
        }

        foreach (self::ARRAY_COLUMNS as $col => $key) {
            $v = (array) ($this->{$col} ?? []);

            if ($v) {
                $data[$key] = $v;
            } elseif (! isset($data[$key]) || ! is_array($data[$key])) {
                $data[$key] = [];
            }
        }

        // the raw date text typed in the form wins over the parsed column (keeps the +07:00 offset)
        if (empty($data['date']) && $this->event_date) {
            $data['date'] = $this->event_date->format('Y-m-d\TH:i:sP');
        }

        foreach (['groom', 'bride'] as $role) {
            $person = $this->person($role);

            $data[$role] = array_merge((array) ($data[$role] ?? []), [
                'name' => (string) $person?->name,
                'parents' => (string) $person?->parents,
                'photo' => (string) $person?->photo,
            ]);
        }

        $data[self::listKey('events', $shape + $data)] = $this->events->map(fn($e) => [
            'time'   => (string) $e->time,
            'title'  => (string) $e->title,
            'place'  => (string) $e->place,
            'mapUrl' => (string) $e->map_url,
        ] + ($aliases ? ['map_url' => (string) $e->map_url] : []))->values()->all();

        $data[self::listKey('stories', $shape + $data)] = $this->stories
            ->map(fn($s) => $s->only(['year', 'title', 'text']))
            ->values()->all();

        $data['gallery'] = $this->gallery->pluck('url')->values()->all();

        if ($aliases) {
            foreach (self::STRING_COLUMNS as $col => $key) {
                $data[$col] = $data[$key];
            }
            foreach (self::ARRAY_COLUMNS as $col => $key) {
                $data[$col] = $data[$key];
            }
            $data['event_date'] = $data['date'] ?? null;
        }

        return $data;
    }


    public function saveFromData(array $data): void
    {
        $extra = $data;
        $attrs = [];

        foreach (self::STRING_COLUMNS as $col => $key) {
            $v = $data[$key] ?? $data[$col] ?? '';
            unset($extra[$key], $extra[$col]);

            if (is_string($v)) {
                $attrs[$col] = $v;
            } else {
                $extra[$key] = $v;            // template stores something unusual here: keep it in extra
                $attrs[$col] = '';
            }
        }

        foreach (self::ARRAY_COLUMNS as $col => $key) {
            $v = $data[$key] ?? $data[$col] ?? [];
            unset($extra[$key], $extra[$col]);
            $attrs[$col] = is_array($v) ? $v : [];
        }

        // date: parsed into event_date; the raw text stays in extra['date'] so the template gets it back exactly
        $raw = $data['date'] ?? $data['event_date'] ?? '';
        unset($extra['event_date'], $extra['date']);
        $attrs['event_date'] = null;

        if (is_string($raw) && $raw !== '') {
            $extra['date'] = $raw;

            try {
                $attrs['event_date'] = \Illuminate\Support\Carbon::parse($raw);
            } catch (\Throwable) {
                // not a date: leave the column empty
            }
        }

        // people: known fields -> table, other keys (bio, nickname ...) stay in extra
        $people = [];
        foreach (['groom', 'bride'] as $role) {
            $p = (array) ($data[$role] ?? []);
            $people[$role] = Arr::only($p, self::PERSON_FIELDS);

            $rest = Arr::except($p, self::PERSON_FIELDS);
            if ($rest) {
                $extra[$role] = $rest;
            } else {
                unset($extra[$role]);
            }
        }

        // lists
        $events = $stories = [];

        foreach (self::LIST_ALIASES['events'] as $alias) {
            if (isset($data[$alias]) && is_array($data[$alias])) {
                $events = array_values($data[$alias]);
            }
            unset($extra[$alias]);
        }
        foreach (self::LIST_ALIASES['stories'] as $alias) {
            if (isset($data[$alias]) && is_array($data[$alias])) {
                $stories = array_values($data[$alias]);
            }
            unset($extra[$alias]);
        }

        $gallery = array_values(array_filter((array) ($data['gallery'] ?? []), fn($u) => is_string($u) && trim($u) !== ''));
        unset($extra['gallery']);

        $attrs['extra'] = $extra;

        DB::transaction(function () use ($attrs, $people, $events, $stories, $gallery) {
            $this->fill($attrs)->save();

            foreach ($people as $role => $fields) {
                $row = $this->people()->firstOrNew(['role' => $role]);
                $row->fill([
                    'name'    => (string) ($fields['name'] ?? ''),
                    'parents' => (string) ($fields['parents'] ?? ''),
                    'photo'   => (string) ($fields['photo'] ?? ''),
                ])->save();
            }

            $this->events()->delete();
            foreach ($events as $i => $e) {
                $e = (array) $e;
                $this->events()->create([
                    'time' => (string) ($e['time'] ?? ''),
                    'title'=> (string) ($e['title'] ?? ''),
                    'place' => (string) ($e['place'] ?? ''),
                    'map_url' => (string) ($e['mapUrl'] ?? $e['map_url'] ?? ''),
                    'sort'=> $i,
                ]);
            }

            $this->stories()->delete();
            foreach ($stories as $i => $s) {
                $s = (array) $s;
                $this->stories()->create([
                    'year' => (string) ($s['year'] ?? ''),
                    'title' => (string) ($s['title'] ?? ''),
                    'text' => (string) ($s['text'] ?? ''),
                    'sort' => $i,
                ]);
            }

            $this->gallery()->delete();
            foreach ($gallery as $i => $url) {
                $this->gallery()->create(['url' => $url, 'sort' => $i]);
            }
        });

        $this->unsetRelation('people')->unsetRelation('events')
            ->unsetRelation('stories')->unsetRelation('gallery');
    }
 
    public static function ownedPath(mixed $url, int $userId): ?string
    {
        if (! is_string($url)) {
            return null;
        }

        $pos = strpos($url, '/storage/weddings/');
        if ($pos === false) {
            return null;
        }

        $path = rawurldecode(substr($url, $pos + strlen('/storage/')));

        if (str_contains($path, '..') || ! str_starts_with($path, "weddings/{$userId}/")) {
            return null;
        }

        return $path;
    }

    /** Every string value inside an array. */
    public static function flattenStrings(array $data): array
    {
        $out = [];
        array_walk_recursive($data, function ($v) use (&$out) {
            if (is_string($v)) {
                $out[] = $v;
            }
        });

        return $out;
    }

    /** Delete one uploaded file and its row in the `media` table. */
    public static function forgetFile(string $path): void
    {
        Storage::disk('public')->delete($path);

        try {
            Media::where('disk', 'public')->where('path', $path)->delete();
        } catch (\Throwable $e) {
            report($e); // never block a delete because of the media log
        }
    }

    /** Delete every uploaded file referenced by this invitation. */
    public function deleteFiles(): void
    {
        foreach (self::flattenStrings($this->toTemplateData()) as $value) {
            if ($path = self::ownedPath($value, (int) $this->user_id)) {
                self::forgetFile($path);
            }
        }
    }
}

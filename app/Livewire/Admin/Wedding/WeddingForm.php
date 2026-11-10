<?php

namespace App\Livewire\Admin\Wedding;

use App\Models\Invitation;
use App\Models\InvitationBackground;
use App\Models\Media;
use App\Models\Template;
use App\Support\TemplateRegistry;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
#[Title('Wedding')]
class WeddingForm extends Component
{
    use WithFileUploads;

    private const MAX_GALLERY = 12;

    // Fixed slots: groom_photo -> groom.photo, cover -> cover, music -> music ...
    private const BASE_LABELS = [
        'cover'       => 'Cover',
        'groom_photo' => 'Groom photo',
        'bride_photo' => 'Bride photo',
        'gift_qr'     => 'Gift QR',
        'music'       => 'Music (mp3 / m4a)',
    ];

    // Section background slots. Add every key your templates read with $bg('...').
    // Template sample_data and the library can add more automatically.
    private const DEFAULT_SECTIONS = ['card', 'all', 'intro', 'count', 'story', 'schedule', 'gallery', 'rsvp', 'thanks'];

    private const SKELETON = [
        'groom'       => ['name' => '', 'parents' => '', 'photo' => ''],
        'bride'       => ['name' => '', 'parents' => '', 'photo' => ''],
        'date'        => '',
        'dateText'    => '',
        'cover'       => '',
        'gallery'     => [],
        'backgrounds' => [],
        'intro'       => '',
        'quote'       => '',
        'apology'     => '',
        'thanks'      => '',
        'dressCode'   => ['title' => '', 'note' => '', 'colors' => []],
        'gift'        => ['bank' => '', 'accountName' => '', 'accountNumber' => '', 'qr' => ''],
        'music'       => '',
    ];

    public ?Invitation $wedding = null;

    public string $template_key = '';
    public string $label = '';
    public string $groom_name = '';
    public string $bride_name = '';
    public string $slug = '';

    public string $dateLocal = '';
    public bool $slugTouched = false;
    public bool $is_published = false;

    public array $data = [];

    /** @var array<string, mixed> temporary uploads keyed by slot */
    public array $uploads = [];

    /** @var string[] uploaded files that were replaced/removed; deleted when the form is saved */
    public array $trash = [];

    public ?string $savedUrl = null;
    public bool $savedIsNew = false;

    public function mount(?Invitation $wedding = null): void
    {
        if ($wedding && $wedding->exists) {
            abort_unless($wedding->user_id === auth()->id(), 403);

            $this->wedding      = $wedding;
            $this->template_key = $wedding->template_key;
            $this->label        = (string) $wedding->label;
            $this->slug         = $wedding->slug;
            $this->slugTouched  = true;
            $this->is_published = $wedding->isPublished();
            $this->groom_name   = $wedding->groom_name;
            $this->bride_name   = $wedding->bride_name;
            $this->data         = $this->normalize($wedding->toTemplateData());
            $this->dateLocal    = substr((string) ($this->data['date'] ?? ''), 0, 16);

            return;
        }

        $wanted = (string) request()->query('template', '');
        $key    = $this->templates->firstWhere('key', $wanted)?->key ?? $this->templates->first()?->key;

        if ($key) {
            $this->selectTemplate($key);
        }
    }

    #[Computed]
    public function templates()
    {
        return Template::where('is_active', true)
            ->orderBy('sort')
            ->orderBy('name')
            ->get(['id', 'key', 'name', 'description', 'preview_url']);
    }

    protected function rules(): array
    {
        return [
            'template_key' => ['required', Rule::exists('templates', 'key')->where('is_active', true)],
            'label'        => ['required', 'string', 'max:80'],
            'groom_name'   => ['required', 'string', 'max:120'],
            'bride_name'   => ['required', 'string', 'max:120'],
            'slug'         => [
                'required',
                'string',
                'max:150',
                Rule::unique('invitations', 'slug')->ignore($this->wedding?->id),
                Rule::notIn(Invitation::RESERVED),
            ],
            'is_published' => ['boolean'],
            'data'         => ['array'],
        ];
    }

    /* ---------------------------------------------------------------
     |  Template + names + slug
     | ------------------------------------------------------------- */

    private function selectTemplate(string $key): void
    {
        $template = Template::where('key', $key)->where('is_active', true)->first();

        if (! $template) {
            return;
        }

        $this->template_key = $template->key;
        $this->data         = $this->normalize($template->sample_data ?? []);
        $this->dateLocal    = substr((string) ($this->data['date'] ?? ''), 0, 16);

        $this->groom_name = '';
        $this->bride_name = '';
        $this->syncNames();

        if (! $this->slugTouched) {
            $this->slug = '';
        }
    }

    private function normalize(array $data): array
    {
        $out = array_replace_recursive(self::SKELETON, $data);

        foreach (['events', 'stories'] as $logical) {
            $aliases = $logical === 'events' ? ['events', 'schedule'] : ['story', 'stories', 'timeline'];

            if (! array_intersect($aliases, array_keys($out))) {
                $out[$aliases[0]] = [];
            }
        }

        // backgrounds must always be an array so Arr::set() works
        if (! is_array($out['backgrounds'] ?? null)) {
            $out['backgrounds'] = [];
        }

        return $out;
    }

    private function eventsKey(): string
    {
        return Invitation::listKey('events', $this->data);
    }

    private function storyKey(): string
    {
        return Invitation::listKey('stories', $this->data);
    }

    public function addEvent(): void
    {
        $k = $this->eventsKey();

        if (count($this->data[$k] ?? []) < 12) {
            $this->data[$k][] = ['time' => '09:00', 'title' => '', 'place' => '', 'mapUrl' => ''];
        }
    }

    public function addStory(): void
    {
        $k = $this->storyKey();

        if (count($this->data[$k] ?? []) < 10) {
            $this->data[$k][] = ['year' => '', 'title' => '', 'text' => ''];
        }
    }

    public function addColor(): void
    {
        if (count($this->data['dressCode']['colors'] ?? []) < 8) {
            $this->data['dressCode']['colors'][] = '#e6b84a';
        }
    }

    /** While creating: another template reloads its sample data. While editing: only the template changes. */
    public function updatedTemplateKey(string $value): void
    {
        if (! $this->wedding) {
            $this->selectTemplate($value);
        }
    }

    public function updatedGroomName(): void
    {
        $this->syncNames();

        if (! $this->slugTouched) {
            $this->slug = Invitation::makeSlug($this->groom_name);
        }
    }

    public function updatedBrideName(): void
    {
        $this->syncNames();
    }

    public function updatedSlug(string $value): void
    {
        $this->slugTouched = true;
        $this->slug        = Invitation::makeSlug($value);
    }

    private function syncNames(): void
    {
        data_set($this->data, 'groom.name', $this->groom_name);
        data_set($this->data, 'bride.name', $this->bride_name);
    }

    /* ---------------------------------------------------------------
     |  Background library
     | ------------------------------------------------------------- */

    #[Computed]
    public function library(): array
    {
        if ($this->template_key === '') {
            return [];
        }

        try {
            return InvitationBackground::active()
                ->where(function ($q) {
                    $q->where('template', $this->template_key)
                        ->orWhereNull('template')
                        ->orWhere('template', '');
                })
                ->orderBy('sort')
                ->orderBy('id')
                ->get()
                ->groupBy('section')
                ->map(fn($rows) => $rows->map(fn($b) => [
                    'id'         => $b->id,
                    'name'       => (string) $b->name,
                    'url'        => $this->backgroundUrl($b->path),
                    'is_default' => (bool) $b->is_default,
                ])->filter(fn($b) => $b['url'])->values()->all())
                ->filter()
                ->all();
        } catch (\Throwable $e) {
            report($e);

            return [];
        }
    }

    private function backgroundUrl(?string $path): ?string
    {
        $path = trim((string) $path);

        if ($path === '') {
            return null;
        }

        if (preg_match('#^(https?:)?//#i', $path) || str_starts_with($path, '/')) {
            return $path;
        }

        $path = ltrim($path, '/');

        if (str_starts_with($path, 'storage/')) {
            $path = substr($path, strlen('storage/'));
        }

        return Storage::disk('public')->exists($path)
            ? Storage::disk('public')->url($path)
            : asset($path);
    }

    /** @return string[] */
    private function backgroundSections(): array
    {
        $fromData = array_keys((array) ($this->data['backgrounds'] ?? []));
        $fromLib  = array_keys($this->library);

        return collect([...$fromData, ...$fromLib, ...self::DEFAULT_SECTIONS])
            ->map(fn($s) => trim((string) $s))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    private function slotLabels(): array
    {
        $labels = self::BASE_LABELS;

        foreach ($this->backgroundSections() as $section) {
            $labels['backgrounds_' . $section] = match ($section) {
                'all'   => 'Background: shared',
                'card'  => 'Background: Card (first screen)',
                default => 'Background: ' . Str::headline($section),
            };
        }

        return $labels;
    }

    public function pickBackground(string $slot, int $id): void
    {
        if (! str_starts_with($slot, 'backgrounds_') || ! array_key_exists($slot, $this->slotLabels())) {
            return;
        }

        $row = collect($this->library)->flatten(1)->firstWhere('id', $id);

        if (! $row || empty($row['url'])) {
            return;
        }

        $this->putSlot($slot, $row['url']);
    }

    #[Computed]
    public function ownImages(): array
    {
        $d    = $this->data;
        $rows = [
            ['Cover',       Arr::get($d, 'cover')],
            ['Groom photo', Arr::get($d, 'groom.photo')],
            ['Bride photo', Arr::get($d, 'bride.photo')],
        ];

        foreach (array_values((array) ($d['gallery'] ?? [])) as $i => $url) {
            $rows[] = ['Gallery ' . ($i + 1), $url];
        }

        foreach ((array) ($d['backgrounds'] ?? []) as $section => $url) {
            $rows[] = ['Background: ' . ($section === 'all' ? 'shared' : Str::headline((string) $section)), $url];
        }

        $seen = [];
        $out  = [];

        foreach ($rows as [$label, $url]) {
            if (! is_string($url) || trim($url) === '' || isset($seen[$url])) {
                continue;
            }

            $seen[$url] = true;
            $out[]      = ['label' => $label, 'url' => $url];
        }

        return $out;
    }

    public function pickImage(string $slot, string $url): void
    {
        if (! str_starts_with($slot, 'backgrounds_') || ! array_key_exists($slot, $this->slotLabels())) {
            return;
        }

        if (! in_array($url, array_column($this->ownImages, 'url'), true)) {
            return;
        }

        $this->putSlot($slot, $url);

        unset($this->ownImages); // clear the cached list so the render sees the change
    }

    /* ---------------------------------------------------------------
     |  Image / music slots
     | ------------------------------------------------------------- */

    private function slotPath(string $slot): string
    {
        // Only the first underscore becomes a dot:
        // groom_photo -> groom.photo, backgrounds_card -> backgrounds.card
        return preg_replace('/_/', '.', $slot, 1);
    }

    /** Set a slot value (the old file goes to the trash and is deleted on save). */
    private function putSlot(string $slot, string $url): void
    {
        $path = $this->slotPath($slot);

        $this->trash[] = (string) Arr::get($this->data, $path, '');

        // make sure the parent is an array (backgrounds / gift / groom ...)
        $parent = Str::before($path, '.');

        if ($parent !== $path && ! is_array($this->data[$parent] ?? null)) {
            $this->data[$parent] = [];
        }

        Arr::set($this->data, $path, $url);
    }

    /**
     * Every upload card. Named buildSlots (NOT "slots") on purpose, and it is a
     * plain private method, so it can never clash with anything in Livewire.
     */
    private function buildSlots(): array
    {
        $d   = $this->data;
        $out = [];

        foreach ($this->slotLabels() as $slot => $label) {
            $path    = $this->slotPath($slot);
            $saved   = Arr::get($d, $path);
            $section = str_starts_with($slot, 'backgrounds_') ? substr($slot, strlen('backgrounds_')) : null;

            $default = null;

            if ($slot !== 'music') {
                try {
                    $default = TemplateRegistry::defaultFor($path);
                } catch (\Throwable $e) {
                    report($e);
                }
            }

            $hasSaved = is_string($saved) && $saved !== '';

            $out[] = [
                'slot'          => $slot,
                'label'         => $label,
                'saved'         => $hasSaved,
                'url'           => $hasSaved ? $saved : $default,
                'gallery_index' => null,
                'is_music'      => $slot === 'music',
                'section'       => $section,
            ];
        }

        $gallery = array_values((array) ($d['gallery'] ?? []));

        foreach ($gallery as $i => $url) {
            $out[] = [
                'slot'          => "gallery_$i",
                'label'         => 'Gallery ' . ($i + 1),
                'saved'         => true,
                'url'           => $url,
                'gallery_index' => $i,
                'is_music'      => false,
                'section'       => null,
            ];
        }

        if (count($gallery) < self::MAX_GALLERY) {
            $out[] = [
                'slot'          => 'gallery_new',
                'label'         => 'Add gallery image',
                'saved'         => false,
                'url'           => null,
                'gallery_index' => null,
                'is_music'      => false,
                'section'       => null,
            ];
        }

        return $out;
    }

    /** The upload cards grouped for the Photos tab (plain arrays, no Blade filtering). */
    #[Computed]
    public function photoGroups(): array
    {
        $slots = collect($this->buildSlots());

        $groups = [
            [
                'key'   => 'main',
                'title' => __('Main photos'),
                'slots' => $slots->filter(fn($s) => in_array($s['slot'], ['cover', 'groom_photo', 'bride_photo', 'gift_qr'], true))->values()->all(),
            ],
            [
                'key'   => 'backgrounds',
                'title' => __('Section backgrounds'),
                'slots' => $slots->filter(fn($s) => str_starts_with($s['slot'], 'backgrounds_'))->values()->all(),
            ],
            [
                'key'   => 'gallery',
                'title' => __('Gallery'),
                'slots' => $slots->filter(fn($s) => str_starts_with($s['slot'], 'gallery_'))->values()->all(),
            ],
            [
                'key'   => 'music',
                'title' => __('Music'),
                'slots' => $slots->filter(fn($s) => $s['slot'] === 'music')->values()->all(),
            ],
        ];

        return array_values(array_filter($groups, fn($g) => ! empty($g['slots'])));
    }

    /** Runs when a file is chosen in any slot (cover, backgrounds_card, gallery_new, music ...). */
    public function updatedUploads($file, string $slot): void
    {
        $isGallery = $slot === 'gallery_new';

        if (! $isGallery && ! array_key_exists($slot, $this->slotLabels())) {
            unset($this->uploads[$slot]);
            $this->dispatch('swal', type: 'error', title: __('Unknown upload slot.'), text: $slot);

            return;
        }

        if ($isGallery && count($this->data['gallery'] ?? []) >= self::MAX_GALLERY) {
            unset($this->uploads[$slot]);
            $this->dispatch('swal', type: 'error', title: __('Maximum 12 gallery photos.'));

            return;
        }

        try {
            $this->validateOnly("uploads.$slot", [
                "uploads.$slot" => $slot === 'music'
                    ? ['file', 'mimes:mp3,m4a,mpeg', 'max:15360']
                    : ['image', 'max:8192'],
            ]);

            $stored = $file->store('weddings/' . auth()->id(), 'public');
            $url    = Storage::disk('public')->url($stored);

            $this->recordMedia($file, $stored, $slot === 'music');

            if ($isGallery) {
                $this->data['gallery'][] = $url;
            } else {
                $this->putSlot($slot, $url);
            }
        } catch (ValidationException $e) {
            $this->dispatch('swal', type: 'error', title: __('Upload failed'), text: collect($e->errors())->flatten()->first() ?? '');
        } catch (\Throwable $e) {
            report($e);
            $this->dispatch('swal', type: 'error', title: __('Upload failed'), text: $e->getMessage());
        } finally {
            unset($this->uploads[$slot]);
        }
    }

    private function recordMedia($file, string $storedPath, bool $isMusic): void
    {
        try {
            Media::create([
                'user_id'       => auth()->id(),
                'source'        => 'upload',
                'disk'          => 'public',
                'path'          => $storedPath,
                'resource_type' => $isMusic ? 'audio' : 'image',
                'mime'          => $file->getMimeType(),
                'size'          => $file->getSize(),
                'name'          => $file->getClientOriginalName(),
            ]);
        } catch (\Throwable $e) {
            report($e);
        }
    }

    /** Paste an image URL / public path instead of uploading. */
    public function setSlotUrl(string $slot, string $url): void
    {
        $url = trim($url);

        if ($url !== '' && ! preg_match('#^(https?://|/)#i', $url)) {
            $this->dispatch('swal', type: 'error', title: __('Use a link starting with http(s):// or /'));

            return;
        }

        if (preg_match('/^gallery_(\d+)$/', $slot, $m)) {
            $g = array_values($this->data['gallery'] ?? []);

            if (isset($g[(int) $m[1]])) {
                $this->trash[] = (string) $g[(int) $m[1]];
                $g[(int) $m[1]] = $url;
                $this->data['gallery'] = array_values(array_filter($g, fn($u) => $u !== ''));
            }

            return;
        }

        if (! array_key_exists($slot, $this->slotLabels())) {
            return;
        }

        if ($url === '') {
            $this->clearSlot($slot);

            return;
        }

        $this->putSlot($slot, $url);
    }

    public function clearSlot(string $slot): void
    {
        if (! array_key_exists($slot, $this->slotLabels())) {
            return;
        }

        $path = $this->slotPath($slot);
        $this->trash[] = (string) Arr::get($this->data, $path, '');
        Arr::forget($this->data, $path);
    }

    public function removeGallery(int $i): void
    {
        $g = array_values($this->data['gallery'] ?? []);

        if (isset($g[$i])) {
            $this->trash[] = (string) $g[$i];
        }

        array_splice($g, $i, 1);
        $this->data['gallery'] = $g;
    }

    /* ---------------------------------------------------------------
     |  Repeaters
     | ------------------------------------------------------------- */

    public function addItem(string $path): void
    {
        $list = Arr::get($this->data, $path, []);
        $list = is_array($list) ? array_values($list) : [];

        $first  = $list[0] ?? '';
        $list[] = is_array($first) ? $this->blank($first) : '';

        Arr::set($this->data, $path, $list);
    }

    public function removeItem(string $path, int $i): void
    {
        $list = Arr::get($this->data, $path, []);
        $list = is_array($list) ? array_values($list) : [];

        array_splice($list, $i, 1);
        Arr::set($this->data, $path, $list);
    }

    private function blank(array $a): array
    {
        return array_map(
            fn($v) => is_array($v) ? $this->blank($v) : (is_bool($v) ? false : ''),
            $a
        );
    }

    /* ---------------------------------------------------------------
     |  Save
     | ------------------------------------------------------------- */

    public function save()
    {
        if (! $this->slugTouched || $this->slug === '') {
            $this->slug = Invitation::uniqueSlug($this->groom_name, $this->wedding?->id);
        }

        try {
            $valid = $this->validate();
        } catch (ValidationException $e) {
            $this->dispatch(
                'swal',
                type: 'error',
                title: __('Please check the form'),
                text: collect($e->errors())->flatten()->first() ?? ''
            );

            throw $e;
        }

        $data = $this->data;
        data_set($data, 'date', $this->dateLocal !== '' ? $this->dateLocal . ':00+07:00' : '');
        data_set($data, 'groom.name', $valid['groom_name']);
        data_set($data, 'bride.name', $valid['bride_name']);
        $data['gallery'] = array_values(array_filter((array) ($data['gallery'] ?? [])));

        $publish = (bool) $valid['is_published'];
        $isNew   = ! $this->wedding;

        $templateId = Template::where('key', $valid['template_key'])->value('id');

        $payload = [
            'template_id'  => $templateId,
            'label'        => $valid['label'],
            'slug'         => $valid['slug'],
            'status'       => $publish ? 'published' : 'draft',
            'published_at' => $publish ? ($this->wedding?->published_at ?? now()) : null,
        ];

        DB::transaction(function () use ($payload, $data, $isNew) {
            if ($isNew) {
                $this->wedding = Invitation::create($payload + ['user_id' => auth()->id()]);
            } else {
                $this->wedding->update($payload);
            }

            $this->wedding->saveFromData($data);
        });

        $this->purgeTrash($data);

        $this->wedding->refresh();
        $this->savedIsNew = $isNew;
        $this->savedUrl   = $this->wedding->url;

        $this->dispatch('swal', type: 'success', title: $isNew ? __('Wedding created.') : __('Wedding updated.'));
        $this->dispatch('scroll-top');
    }

    private function purgeTrash(array $savedData): void
    {
        $stillUsed = Invitation::flattenStrings($savedData);

        foreach (array_unique($this->trash) as $url) {
            if ($url === '' || in_array($url, $stillUsed, true)) {
                continue;
            }

            if ($path = Invitation::ownedPath($url, (int) auth()->id())) {
                Invitation::forgetFile($path);
            }
        }

        $this->trash = [];
    }

    public function render()
    {
        return view('livewire.admin.wedding.wedding-form');
    }
}

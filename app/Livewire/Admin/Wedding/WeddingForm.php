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
use RuntimeException;

#[Layout('layouts.app')]
#[Title('Wedding')]
class WeddingForm extends Component
{
    use WithFileUploads;
    private const MAX_GALLERY = 12;
    private const BASE_LABELS = [
        'cover' => 'Cover',
        'groom_photo' => 'Groom photo',
        'bride_photo' => 'Bride photo',
        'gift_qr' => 'Gift QR',
        'music' => 'Music (mp3 / m4a)',
    ];

    private const DEFAULT_SECTIONS = ['card', 'all', 'intro', 'story', 'schedule', 'gallery', 'rsvp', 'thanks'];
    private const MUSIC_EXTENSIONS = ['mp3', 'm4a'];
    private const MAX_UPLOAD_KB = 20480;

    private const SKELETON = [
        'groom' => ['name' => '', 'dad' => '', 'mom' => '', 'photo' => ''],
        'bride' => ['name' => '', 'dad' => '', 'mom' => '', 'photo' => ''],
        'date' => '',
        'dateText'  => '',
        'cover' => '',
        'gallery' => [],
        'backgrounds' => [],
        'intro' => '',
        'quote' => '',
        'apology' => '',
        'thanks' => '',
        'dressCode' => ['title' => '', 'note' => '', 'colors' => []],
        'gift' => ['bank' => '', 'accountName' => '', 'accountNumber' => '', 'qr' => ''],
        'music' => '',
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
            $user = auth()->user();

            abort_unless(
                $user->hasRole('Master Admin')
                    || $user->can('weddings.manage')
                    || $wedding->user_id === $user->id,
                403
            );
            $this->wedding = $wedding;
            $this->template_key = $wedding->template_key;
            $this->label = (string) $wedding->label;
            $this->slug = $wedding->slug;
            $this->slugTouched = true;
            $this->is_published = $wedding->isPublished();
            $this->groom_name = $wedding->groom_name;
            $this->bride_name = $wedding->bride_name;
            $this->data = $this->normalize($wedding->toTemplateData());
            $this->dateLocal = substr((string) ($this->data['date'] ?? ''), 0, 16);

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
            'label' => ['required', 'string', 'max:80'],
            'groom_name' => ['required', 'string', 'max:120'],
            'bride_name' => ['required', 'string', 'max:120'],
            'slug' => [
                'required',
                'string',
                'max:150',
                Rule::unique('invitations', 'slug')->ignore($this->wedding?->id),
                Rule::notIn(Invitation::RESERVED),
            ],
            'is_published' => ['boolean'],
            'data' => ['array'],
        ];
    }

    private function selectTemplate(string $key): void
    {
        $template = Template::where('key', $key)->where('is_active', true)->first();

        if (! $template) {
            return;
        }
        $this->template_key = $template->key;
        $this->data = $this->normalize($template->sample_data ?? []);
        $this->dateLocal = substr((string) ($this->data['date'] ?? ''), 0, 16);
        $this->groom_name = '';
        $this->bride_name = '';
        $this->syncNames();
        if (! $this->slugTouched) {
            $this->slug = '';
        }
        $this->refreshViews();
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

        if (! is_array($out['backgrounds'] ?? null)) {
            $out['backgrounds'] = [];
        }

        if (! is_array($out['gallery'] ?? null)) {
            $out['gallery'] = [];
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
        } else {
            $this->refreshViews();
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

    private function refreshViews(): void
    {
        unset($this->photoGroups, $this->ownImages);
    }

    private static function iniBytes(string|false $value): int
    {
        $value = trim((string) $value);

        if ($value === '') {
            return 0;
        }

        $n    = (int) $value;
        $unit = strtolower(substr($value, -1));

        return match ($unit) {
            'g' => $n * 1024 ** 3,
            'm' => $n * 1024 ** 2,
            'k' => $n * 1024,
            default => $n,
        };
    }

    /** Smallest of upload_max_filesize / post_max_size in bytes (0 = unknown / unlimited). */
    #[Computed]
    public function uploadLimit(): int
    {
        $limits = array_filter([
            self::iniBytes(ini_get('upload_max_filesize')),
            self::iniBytes(ini_get('post_max_size')),
        ], fn($v) => $v > 0);

        return $limits ? min($limits) : 0;
    }

    private function uploadDisk(): string
    {
        return (string) (config('wedding.upload_disk') ?: env('WEDDING_UPLOAD_DISK') ?: 'public');
    }

    /** Name of the disk new uploads go to (shown on the Photos tab, handy for checking R2 is active). */
    #[Computed]
    public function storageDisk(): string
    {
        return $this->uploadDisk();
    }

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
                    'id' => $b->id,
                    'name' => (string) $b->name,
                    'url' => $this->backgroundUrl($b->path),
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

    private function backgroundSections(): array
    {
        $fromData = array_keys((array) ($this->data['backgrounds'] ?? []));
        $fromLib  = array_keys($this->library);

        return collect([...self::DEFAULT_SECTIONS, ...$fromLib, ...$fromData])
            ->map(fn($s) => trim((string) $s))
            ->filter()
            ->reject(fn($s) => $s === 'count')   // keep "countdown" only
            ->unique()
            ->values()
            ->all();
    }

    private function slotLabels(): array
    {
        $labels = self::BASE_LABELS;

        foreach ($this->backgroundSections() as $section) {
            $labels['backgrounds_' . $section] = match ($section) {
                'all' => 'Background: shared',
                'card' => 'Background: Card (first screen)',
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
        $d = $this->data;
        $rows = [
            ['Cover', Arr::get($d, 'cover')],
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
            $out[] = ['label' => $label, 'url' => $url];
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
    }



    private function slotPath(string $slot): string
    {
        return preg_replace('/_/', '.', $slot, 1);
    }

    /** Set a slot value (the old file goes to the trash and is deleted on save). */
    private function putSlot(string $slot, string $url): void
    {
        $path = $this->slotPath($slot);
        $this->trash[] = (string) Arr::get($this->data, $path, '');
        $parent = Str::before($path, '.');
        if ($parent !== $path && ! is_array($this->data[$parent] ?? null)) {
            $this->data[$parent] = [];
        }

        Arr::set($this->data, $path, $url);

        $this->refreshViews();
    }

    /** Every upload card. Plain private method so it can never clash with Livewire. */
    private function buildSlots(): array
    {
        $d = $this->data;
        $out = [];

        foreach ($this->slotLabels() as $slot => $label) {
            $path = $this->slotPath($slot);
            $saved = Arr::get($d, $path);
            $section = str_starts_with($slot, 'backgrounds_') ? substr($slot, strlen('backgrounds_')) : null;

            $default = null;

            try {
                $default = TemplateRegistry::defaultFor($path);
            } catch (\Throwable $e) {
                report($e);
            }

            $hasSaved = is_string($saved) && $saved !== '';

            $out[] = [
                'slot' => $slot,
                'label' => $label,
                'saved' => $hasSaved,
                'url' => $hasSaved ? $saved : $default,
                'gallery_index' => null,
                'is_music' => $slot === 'music',
                'section' => $section,
            ];
        }

        $gallery = array_values((array) ($d['gallery'] ?? []));

        foreach ($gallery as $i => $url) {
            $out[] = [
                'slot' => "gallery_$i",
                'label' => 'Gallery ' . ($i + 1),
                'saved' => true,
                'url' => $url,
                'gallery_index' => $i,
                'is_music' => false,
                'section' => null,
            ];
        }

        if (count($gallery) < self::MAX_GALLERY) {
            $out[] = [
                'slot' => 'gallery_new',
                'label' => 'Add gallery image',
                'saved' => false,
                'url' => null,
                'gallery_index' => null,
                'is_music' => false,
                'section' => null,
            ];
        }

        return $out;
    }

    #[Computed]
    public function photoGroups(): array
    {
        $slots = collect($this->buildSlots());

        $groups = [
            [
                'key' => 'main',
                'title' => __('Main photos'),
                'slots' => $slots->filter(fn($s) => in_array($s['slot'], ['cover', 'groom_photo', 'bride_photo', 'gift_qr'], true))->values()->all(),
            ],
            [
                'key' => 'backgrounds',
                'title' => __('Section backgrounds'),
                'slots' => $slots->filter(fn($s) => str_starts_with($s['slot'], 'backgrounds_'))->values()->all(),
            ],
            [
                'key' => 'gallery',
                'title' => __('Gallery'),
                'slots' => $slots->filter(fn($s) => str_starts_with($s['slot'], 'gallery_'))->values()->all(),
            ],
            [
                'key' => 'music',
                'title' => __('Music'),
                'slots' => $slots->filter(fn($s) => $s['slot'] === 'music')->values()->all(),
            ],
        ];
        return array_values(array_filter($groups, fn($g) => ! empty($g['slots'])));
    }


    private function publicUrl(string $stored, string $disk): string
    {
        $url = Storage::disk($disk)->url($stored);
        $host = parse_url($url, PHP_URL_HOST);
        $appHost = parse_url((string) config('app.url'), PHP_URL_HOST);

        if ($host !== null && $host === $appHost) {
            return parse_url($url, PHP_URL_PATH) ?: $url;
        }

        return $url;
    }

    public function updatedUploads($file, string $slot): void
    {
        $isGallery = $slot === 'gallery_new';
        $isMusic = $slot === 'music';

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
                "uploads.$slot" => $isMusic ? ['file', 'max:' . self::MAX_UPLOAD_KB] : ['image', 'max:' . self::MAX_UPLOAD_KB],
            ]);
            if ($isMusic) {
                $ext = strtolower((string) $file->getClientOriginalExtension());

                if (! in_array($ext, self::MUSIC_EXTENSIONS, true)) {
                    throw ValidationException::withMessages([
                        "uploads.$slot" => __('Music must be an .mp3 or .m4a file.'),
                    ]);
                }
            }
            $name = $file->getClientOriginalName();
            $mime = $file->getMimeType();
            $size = $file->getSize();

            $disk = $this->uploadDisk();

            if (! config("filesystems.disks.$disk")) {
                throw new RuntimeException("Disk [$disk] is not defined in config/filesystems.php (check WEDDING_UPLOAD_DISK and run php artisan config:clear).");
            }

            $stored = $file->store('weddings/' . auth()->id(), ['disk' => $disk]);

            if (! $stored) {
                throw new RuntimeException('The file could not be saved to the "' . $disk . '" disk.');
            }

            $url = $this->publicUrl($stored, $disk);

            $this->recordUpload($stored, $name, $mime, $size, $isMusic, $disk);

            if ($isGallery) {
                $this->data['gallery'][] = $url;
                $this->refreshViews();
            } else {
                $this->putSlot($slot, $url);
            }
        } catch (ValidationException $e) {
            $this->dispatch('swal', type: 'error', title: __('Upload failed'), text: collect($e->errors())->flatten()->first() ?? '');
        } catch (\Throwable $e) {
            report($e);
            $reason = $e->getPrevious()?->getMessage() ?: $e->getMessage();

            $this->dispatch('swal', type: 'error', title: __('Upload failed'), text: Str::limit(trim(preg_replace('/\s+/', ' ', $reason)), 280));
        } finally {
            unset($this->uploads[$slot]);
        }
    }

    private function recordUpload(string $storedPath, ?string $name, ?string $mime, ?int $size, bool $isMusic, string $disk): void
    {
        try {
            Media::fromUpload((int) auth()->id(), $storedPath, $name, $mime, $size, $isMusic, $disk);
        } catch (\Throwable $e) {
            report($e);
        }
    }

    /** Media row for a pasted link (source = external). Never breaks the form. */
    private function recordExternal(string $url, bool $isMusic): void
    {
        try {
            Media::fromExternal((int) auth()->id(), $url, $isMusic);
        } catch (\Throwable $e) {
            report($e);
        }
    }

    private function isValidLink(string $url): bool
    {
        return (bool) preg_match('#^(https?://|/)#i', $url);
    }

    /** Paste an image / music link instead of uploading. */
    public function setSlotUrl(string $slot, string $url): void
    {
        $url = trim($url);

        if ($url !== '' && ! $this->isValidLink($url)) {
            $this->dispatch('swal', type: 'error', title: __('Use a link starting with http(s):// or /'));

            return;
        }

        if (preg_match('/^gallery_(\d+)$/', $slot, $m)) {
            $g = array_values($this->data['gallery'] ?? []);

            if (isset($g[(int) $m[1]])) {
                $this->trash[] = (string) $g[(int) $m[1]];
                $g[(int) $m[1]] = $url;
                $this->data['gallery'] = array_values(array_filter($g, fn($u) => $u !== ''));

                if ($url !== '') {
                    $this->recordExternal($url, false);
                }

                $this->refreshViews();
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

        $this->recordExternal($url, $slot === 'music');
        $this->putSlot($slot, $url);
    }

    /** Add a new gallery photo from a link. */
    public function addGalleryUrl(string $url): void
    {
        $url = trim($url);

        if ($url === '') {
            return;
        }

        if (! $this->isValidLink($url)) {
            $this->dispatch('swal', type: 'error', title: __('Use a link starting with http(s):// or /'));

            return;
        }

        if (count($this->data['gallery'] ?? []) >= self::MAX_GALLERY) {
            $this->dispatch('swal', type: 'error', title: __('Maximum 12 gallery photos.'));

            return;
        }

        $this->recordExternal($url, false);
        $this->data['gallery'][] = $url;

        $this->refreshViews();
    }

    public function clearSlot(string $slot): void
    {
        if (! array_key_exists($slot, $this->slotLabels())) {
            return;
        }

        $path = $this->slotPath($slot);
        $this->trash[] = (string) Arr::get($this->data, $path, '');
        Arr::forget($this->data, $path);

        $this->refreshViews();
    }

    public function removeGallery(int $i): void
    {
        $g = array_values($this->data['gallery'] ?? []);

        if (isset($g[$i])) {
            $this->trash[] = (string) $g[$i];
        }

        array_splice($g, $i, 1);
        $this->data['gallery'] = $g;

        $this->refreshViews();
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

            // jump to the Basics tab when the problem is there
            if (array_intersect(array_keys($e->errors()), ['template_key', 'groom_name', 'bride_name', 'slug', 'label'])) {
                $this->dispatch('goto-tab', tab: 'basics');
            }

            throw $e;
        }

        $data = $this->data;
        data_set($data, 'date', $this->dateLocal !== '' ? $this->dateLocal . ':00+07:00' : '');
        data_set($data, 'groom.name', $valid['groom_name']);
        data_set($data, 'bride.name', $valid['bride_name']);
        $data['gallery'] = array_values(array_filter((array) ($data['gallery'] ?? [])));

        $publish = (bool) $valid['is_published'];
        $isNew = ! $this->wedding;

        $templateId = Template::where('key', $valid['template_key'])->value('id');

        $payload = [
            'template_id' => $templateId,
            'label' => $valid['label'],
            'slug' => $valid['slug'],
            'status' => $publish ? 'published' : 'draft',
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
        $this->savedUrl = $this->wedding->url;

        $this->dispatch('swal', type: 'success', title: $isNew ? __('Wedding created.') : __('Wedding updated.'));
        $this->dispatch('scroll-top');
    }

    private function purgeTrash(array $savedData): void
    {
        $stillUsed = Invitation::flattenStrings($savedData);

        $disk = $this->uploadDisk();

        foreach (array_unique($this->trash) as $url) {
            if ($url === '' || in_array($url, $stillUsed, true)) {
                continue;
            }

            // 1) file on the upload disk (R2): delete it and its media row
            if ($path = $this->ownedDiskPath($url, $disk)) {
                try {
                    Media::where('user_id', auth()->id())
                        ->where('disk', $disk)
                        ->where('path', $path)
                        ->get()
                        ->each->delete(); // the model also removes the file

                    Storage::disk($disk)->delete($path); // in case there was no media row
                } catch (\Throwable $e) {
                    report($e);
                }

                continue;
            }

            // 2) older files on the local public disk (original behaviour)
            if ($path = Invitation::ownedPath($url, (int) auth()->id())) {
                Invitation::forgetFile($path);
            }
        }

        $this->trash = [];
    }

    private function ownedDiskPath(string $url, string $disk): ?string
    {
        $base = rtrim((string) config("filesystems.disks.$disk.url"), '/');

        if ($base === '' || ! str_starts_with($url, $base . '/')) {
            return null;
        }

        $path   = ltrim(substr($url, strlen($base)), '/');
        $prefix = 'weddings/' . auth()->id() . '/';

        return str_starts_with($path, $prefix) && ! str_contains($path, '..') ? $path : null;
    }

    public function render()
    {
        return view('livewire.admin.wedding.wedding-form');
    }
}

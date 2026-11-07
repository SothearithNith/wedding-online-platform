<?php

namespace App\Livewire\Admin\Templates;

use App\Models\Template;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
#[Title('Template')]
class TemplateForm extends Component
{
    use WithFileUploads;

    // Every image the gold view can show. The slot name maps to a path in sample_data:
    // groom_photo -> groom.photo, backgrounds_intro -> backgrounds.intro, ...
    private const SLOT_LABELS = [
        'cover'                => 'Cover',
        'groom_photo'          => 'Groom photo',
        'bride_photo'          => 'Bride photo',
        'gift_qr'              => 'Gift QR',
        'backgrounds_all'      => 'Background: shared',
        'backgrounds_intro'    => 'Background: intro',
        'backgrounds_count'    => 'Background: countdown',
        'backgrounds_story'    => 'Background: story',
        'backgrounds_schedule' => 'Background: schedule',
        'backgrounds_gallery'  => 'Background: gallery',
        'backgrounds_rsvp'     => 'Background: RSVP',
        'backgrounds_thanks'   => 'Background: thanks',
    ];

    public ?Template $template = null;

    public string $key = '';
    public string $name = '';
    public string $description = '';
    public bool $is_active = true;
    public bool $is_public = true;
    public int $sort = 0;

    public string $schema = '{}';
    public string $sample_data = '{}';

    public $preview = null;

    /** @var array<string, mixed> temporary uploads keyed by slot */
    public array $imageUploads = [];

    public function mount(?Template $template = null): void
    {
        if ($template && $template->exists) {
            $this->template    = $template;
            $this->key         = $template->key;
            $this->name        = $template->name;
            $this->description = (string) $template->description;
            $this->is_active   = $template->is_active;
            $this->is_public   = $template->is_public;
            $this->sort        = (int) $template->sort;
            $this->schema      = $this->encode($template->schema ?? new \stdClass);
            $this->sample_data = $this->encode($template->sample_data ?? new \stdClass);
        }
    }

    protected function rules(): array
    {
        return [
            'key'         => [
                'required',
                'string',
                'max:100',
                'alpha_dash',
                Rule::unique('templates', 'key')->ignore($this->template?->id),
            ],
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active'   => ['boolean'],
            'is_public'   => ['boolean'],
            'sort'        => ['integer', 'min:0'],
            'schema'      => ['nullable', 'json'],
            'sample_data' => ['nullable', 'json'],
            'preview'     => ['nullable', 'image', 'max:4096'],
        ];
    }

    /* ---------------------------------------------------------------
     |  Sample-image slots
     | ------------------------------------------------------------- */

    private function slotPath(string $slot): string
    {
        // Only the first underscore becomes a dot: gallery_2 -> gallery.2
        return preg_replace('/_/', '.', $slot, 1);
    }

    private function decoded(): ?array
    {
        $d = json_decode($this->sample_data ?: '{}', true);

        return is_array($d) ? $d : null;
    }

    private function encode(mixed $d): string
    {
        return json_encode($d, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    #[Computed]
    public function slots(): array
    {
        $d   = $this->decoded() ?? [];
        $out = [];

        foreach (self::SLOT_LABELS as $slot => $label) {
            $out[] = [
                'slot'      => $slot,
                'label'     => $label,
                'url'       => Arr::get($d, $this->slotPath($slot)),
                'removable' => false,
            ];
        }

        foreach (array_values($d['gallery'] ?? []) as $i => $url) {
            $out[] = [
                'slot'      => "gallery_$i",
                'label'     => 'Gallery ' . ($i + 1),
                'url'       => $url,
                'removable' => true,
            ];
        }

        $out[] = [
            'slot'      => 'gallery_new',
            'label'     => 'Add gallery image',
            'url'       => null,
            'removable' => false,
        ];

        return $out;
    }

    public function updatedImageUploads($file, string $slot): void
    {
        $this->validateOnly("imageUploads.$slot", [
            "imageUploads.$slot" => ['image', 'max:4096'],
        ]);

        $data = $this->decoded();

        if ($data === null) {
            $this->addError('sample_data', 'Fix the sample JSON before uploading images.');
            unset($this->imageUploads[$slot]);
            return;
        }

        $url = Storage::disk('public')->url($file->store('templates/samples', 'public'));

        if ($slot === 'gallery_new') {
            $data['gallery'][] = $url;
        } else {
            Arr::set($data, $this->slotPath($slot), $url);
        }

        $this->sample_data = $this->encode($data);
        unset($this->imageUploads[$slot]);
    }

    public function removeGallery(int $i): void
    {
        $data = $this->decoded();

        if ($data === null) {
            return;
        }

        $g = array_values($data['gallery'] ?? []);
        array_splice($g, $i, 1);
        $data['gallery'] = $g;

        $this->sample_data = $this->encode($data);
    }

    /* ---------------------------------------------------------------
     |  Save
     | ------------------------------------------------------------- */

    public function save()
    {
        $data = $this->validate();

        // Accept a pasted full file ({templateId,label,data}) as well as the bare data
        $sample = json_decode($data['sample_data'] ?: '{}', true) ?: [];
        if (isset($sample['data']) && is_array($sample['data'])) {
            $sample = $sample['data'];
        }

        $payload = [
            'key'         => $data['key'],
            'name'        => $data['name'],
            'description' => $data['description'] ?: null,
            'is_active'   => $data['is_active'],
            'is_public'   => $data['is_public'],
            'sort'        => $data['sort'],
            'schema'      => json_decode($data['schema'] ?: '{}', true),
            'sample_data' => $sample,
        ];

        if ($this->preview) {
            $old = $this->template?->preview_url;
            if ($old && ! str_starts_with($old, 'http')) {
                Storage::disk('public')->delete($old);
            }
            $payload['preview_url'] = $this->preview->store('templates', 'public');
        }

        if ($this->template) {
            $this->template->update($payload);
            session()->flash('success', __('Template updated.'));
        } else {
            Template::create($payload);
            session()->flash('success', __('Template created.'));
        }

        return $this->redirectRoute('templates.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.templates.template-form');
    }
}

<?php

namespace App\Livewire\Admin\UserManagement\Feature;

use App\Models\Menu;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Spatie\Permission\Models\Permission;

#[Layout('layouts.app')]
#[Title('Features')]
class FeatureList extends Component
{
    private const FALLBACK_ICON = 'heroicon-o-squares-2x2';

    #[Url(except: '')]
    public string $search = '';

    #[Url(except: 'all')]
    public string $status = 'all';

    // Form state
    public bool $showForm = false;

    public ?int $editingId = null;

    public string $parent_id = '';

    public string $name_en = '';

    public string $name_km = '';

    public string $slug = '';

    public bool $slugTouched = false;

    public string $icon = '';

    public string $route_name = '';

    public string $permission_name = '';

    public bool $is_active = true;

    /* ---------------------------------------------------------------- icons */

    /** Returns the icon if it exists, otherwise a fallback, so a bad name never crashes the page. */
    public function icon(?string $name): string
    {
        $name = trim((string) $name);

        return $name !== '' && $this->iconExists($name) ? $name : self::FALLBACK_ICON;
    }

    private function iconExists(string $name): bool
    {
        static $cache = [];

        return $cache[$name] ??= (function () use ($name) {
            try {
                app(\BladeUI\Icons\Factory::class)->svg($name);

                return true;
            } catch (\Throwable) {
                return false;
            }
        })();
    }

    /* ---------------------------------------------------------- validation */

    protected function rules(): array
    {
        return [
            'parent_id' => [
                'nullable',
                Rule::exists('menus', 'id')->whereNull('parent_id')->whereNot('id', $this->editingId ?? 0),
            ],
            'name_en' => ['required', 'string', 'min:2', 'max:100'],
            'name_km' => ['nullable', 'string', 'max:100'],
            'slug' => [
                'required',
                'string',
                'max:100',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('menus', 'slug')->ignore($this->editingId),
            ],
            'icon' => [
                'nullable',
                'string',
                'max:100',
                'regex:/^heroicon-[a-z]-[a-z0-9-]+$/',
                function ($attribute, $value, $fail) {
                    if ($value !== '' && ! $this->iconExists($value)) {
                        $fail(__('This icon does not exist.'));
                    }
                },
            ],
            'route_name' => [
                'nullable',
                'string',
                'max:150',
                function ($attribute, $value, $fail) {
                    if ($value !== '' && ! Route::has($value)) {
                        $fail(__('This route name is not registered.'));
                    }
                },
            ],
            'permission_name' => ['nullable', 'string', 'max:100', 'regex:/^[a-z0-9_-]+(\.[a-z0-9_-]+)?$/'],
            'is_active' => ['boolean'],
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'parent_id' => __('parent menu'),
            'name_en' => __('English name'),
            'name_km' => __('Khmer name'),
            'route_name' => __('route name'),
            'permission_name' => __('permission name'),
        ];
    }

    protected function messages(): array
    {
        return [
            'slug.regex' => __('Use lowercase letters, numbers and dashes only.'),
            'icon.regex' => __('Use a Heroicon name like heroicon-o-users.'),
            'permission_name.regex' => __('Use a format like users.view (lowercase, one dot).'),
        ];
    }

    public function updatedNameEn(): void
    {
        if (! $this->slugTouched && ! $this->editingId) {
            $this->slug = Str::slug($this->name_en);
        }
    }

    public function updatedSlug(): void
    {
        $this->slugTouched = true;
        $this->slug = Str::slug($this->slug);
    }

    /* ------------------------------------------------------------- actions */

    public function openCreate(?int $parentId = null): void
    {
        $this->resetForm();
        $this->parent_id = $parentId ? (string) $parentId : '';
        $this->showForm = true;
    }

    public function openEdit(int $id): void
    {
        $menu = Menu::findOrFail($id);

        $this->resetForm();
        $this->editingId = $menu->id;
        $this->parent_id = (string) ($menu->parent_id ?? '');
        $this->name_en = (string) $menu->name_en;
        $this->name_km = (string) $menu->name_km;
        $this->slug = (string) $menu->slug;
        $this->slugTouched = true;
        $this->icon = (string) $menu->icon;
        $this->route_name = (string) $menu->route_name;
        $this->permission_name = (string) $menu->permission_name;
        $this->is_active = (bool) $menu->is_active;
        $this->showForm = true;
    }

    public function closeForm(): void
    {
        $this->showForm = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->reset([
            'editingId',
            'parent_id',
            'name_en',
            'name_km',
            'slug',
            'slugTouched',
            'icon',
            'route_name',
            'permission_name',
        ]);
        $this->is_active = true;
        $this->resetValidation();
    }

    public function save(): void
    {
        $this->name_en = trim($this->name_en);
        $this->permission_name = mb_strtolower(trim($this->permission_name));
        $data = $this->validate();

        $parentId = $this->parent_id !== '' ? (int) $this->parent_id : null;

        // Only two levels are supported: a menu that has children cannot become a child.
        if ($parentId && $this->editingId && Menu::where('parent_id', $this->editingId)->exists()) {
            $this->addError('parent_id', __('This menu has sub-menus, so it cannot be placed under another menu.'));

            return;
        }

        $payload = [
            'parent_id' => $parentId,
            'name_en' => $data['name_en'],
            'name_km' => $data['name_km'] ?: null,
            'slug' => $data['slug'],
            'icon' => $data['icon'] ?: null,
            'route_name' => $data['route_name'] ?: null,
            'permission_name' => $data['permission_name'] ?: null,
            'is_active' => $data['is_active'],
        ];

        if ($this->editingId) {
            $menu = Menu::findOrFail($this->editingId);

            // Moving to another parent puts it last in the new group.
            if ($menu->parent_id !== $parentId) {
                $payload['sort_order'] = (int) Menu::where('parent_id', $parentId)->max('sort_order') + 1;
            }

            $menu->update($payload);
            $message = __('":name" has been updated.', ['name' => $menu->name_en]);
        } else {
            $payload['sort_order'] = (int) Menu::where('parent_id', $parentId)->max('sort_order') + 1;
            $menu = Menu::create($payload);
            $message = __('":name" has been created.', ['name' => $menu->name_en]);
        }

        $this->closeForm();
        $this->dispatch('swal', icon: 'success', title: $message);
    }

    public function toggleActive(int $id): void
    {
        $menu = Menu::findOrFail($id);
        $menu->update(['is_active' => ! $menu->is_active]);

        $this->dispatch(
            'swal',
            icon: 'success',
            title: $menu->is_active
                ? __('":name" is now active.', ['name' => $menu->name_en])
                : __('":name" is now hidden.', ['name' => $menu->name_en]),
        );
    }

    public function move(int $id, string $direction): void
    {
        if (! in_array($direction, ['up', 'down'], true)) {
            return;
        }

        $menu = Menu::findOrFail($id);

        DB::transaction(function () use ($menu, $direction) {
            $siblings = Menu::where('parent_id', $menu->parent_id)
                ->orderBy('sort_order')->orderBy('id')
                ->lockForUpdate()->get()->values()->all();

            $i = array_search($menu->id, array_map(fn($m) => $m->id, $siblings), true);
            $j = $direction === 'up' ? $i - 1 : $i + 1;

            if ($i === false || ! isset($siblings[$j])) {
                return;
            }

            [$siblings[$i], $siblings[$j]] = [$siblings[$j], $siblings[$i]];

            foreach ($siblings as $position => $item) {
                if ($item->sort_order !== $position + 1) {
                    $item->update(['sort_order' => $position + 1]);
                }
            }
        });
    }

    public function delete(int $id): void
    {
        $menu = Menu::findOrFail($id);

        if ($menu->allChildren()->exists()) {
            $this->dispatch('swal', icon: 'error', title: __('Delete or move its sub-menus first.'));

            return;
        }

        $menu->delete();
        $this->dispatch('swal', icon: 'success', title: __('":name" has been deleted.', ['name' => $menu->name_en]));
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'status']);
    }

    /* ------------------------------------------------------------- queries */

    #[Computed]
    public function total(): int
    {
        return Menu::count();
    }

    #[Computed]
    public function activeCount(): int
    {
        return Menu::where('is_active', true)->count();
    }

    #[Computed]
    public function parentCount(): int
    {
        return Menu::whereNull('parent_id')->count();
    }

    /** Parent menus the form can pick from (never itself). */
    #[Computed]
    public function parents()
    {
        return Menu::whereNull('parent_id')
            ->when($this->editingId, fn($q) => $q->whereNot('id', $this->editingId))
            ->orderBy('sort_order')->orderBy('id')
            ->get(['id', 'name_en', 'name_km']);
    }

    /** Suggestions for the permission field, built from permissions that already exist. */
    #[Computed]
    public function permissionSuggestions(): array
    {
        return Permission::pluck('name')
            ->map(fn($n) => Str::before($n, '.') . '.view')
            ->unique()->sort()->values()->all();
    }

    private function tree(): array
    {
        $term = mb_strtolower(trim($this->search));

        $match = function (Menu $m) use ($term) {
            $hay = mb_strtolower(implode(' ', [$m->name_en, $m->name_km, $m->slug, $m->route_name, $m->permission_name]));

            return ($term === '' || str_contains($hay, $term))
                && ($this->status === 'all' || $m->is_active === ($this->status === 'active'));
        };

        $rows = [];

        foreach (Menu::whereNull('parent_id')->with('allChildren')->orderBy('sort_order')->orderBy('id')->get() as $root) {
            $kids = $root->allChildren->filter($match)->values();

            if ($match($root) || $kids->isNotEmpty()) {
                $rows[] = [
                    'menu' => $root,
                    'kids' => ($term === '' && $this->status === 'all') ? $root->allChildren : $kids,
                    'total_kids' => $root->allChildren->count(),
                ];
            }
        }

        return $rows;
    }

    public function render()
    {
        return view('livewire.admin.user-management.feature.feature-list', [
            'tree' => $this->tree(),
            'filtering' => trim($this->search) !== '' || $this->status !== 'all',
        ]);
    }
}

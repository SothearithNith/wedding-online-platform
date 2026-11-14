<?php

namespace App\Livewire\Admin\UserManagement\Roles;

use App\Models\Menu;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

#[Layout('layouts.app')]
#[Title('Roles-Permissions')]
class ApplyPermission extends Component
{
    private const PROTECTED_IDS = [1];

    public Role $role;

    public string $search = '';

    /** Permission names currently ticked. */
    public array $selected = [];

    /** Permission names stored in the database, used to detect changes. */
    public array $original = [];

    public function mount(Role $role): void
    {
        abort_if(in_array($role->id, self::PROTECTED_IDS, true), 404);

        $this->role = $role;
        $this->original = $role->permissions()->pluck('name')->all();
        $this->selected = $this->original;
    }

    #[Computed]
    public function menus()
    {
        return Menu::rootTree()->get();
    }

    /** Every permission name that a menu can grant. */
    #[Computed]
    public function allNames(): array
    {
        return $this->menus
            ->flatMap(fn($menu) => collect([$menu->permission_name])
                ->merge($menu->children->pluck('permission_name')))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    /** Menus after applying the search box. A group stays visible if any child matches. */
    #[Computed]
    public function filteredMenus()
    {
        $term = mb_strtolower(trim($this->search));

        if ($term === '') {
            return $this->menus->map(fn($m) => ['menu' => $m, 'children' => $m->children]);
        }

        $matches = fn($m) => str_contains(mb_strtolower($m->name_en . ' ' . $m->name_km . ' ' . $m->permission_name), $term);

        return $this->menus
            ->map(function ($menu) use ($matches) {
                $children = $matches($menu) ? $menu->children : $menu->children->filter($matches);

                return ['menu' => $menu, 'children' => $children->values()];
            })
            ->filter(fn($row) => $row['children']->isNotEmpty() || $matches($row['menu']))
            ->values();
    }

    #[Computed]
    public function selectedCount(): int
    {
        return count(array_intersect($this->selected, $this->allNames));
    }

    #[Computed]
    public function hasChanges(): bool
    {
        $a = $this->selected;
        $b = $this->original;
        sort($a);
        sort($b);

        return $a !== $b;
    }

    public function toggleGroup(array $names): void
    {
        $names = array_values(array_intersect($names, $this->allNames));

        if ($names === []) {
            return;
        }

        $allOn = count(array_intersect($names, $this->selected)) === count($names);

        $this->selected = $allOn
            ? array_values(array_diff($this->selected, $names))
            : array_values(array_unique(array_merge($this->selected, $names)));
    }

    public function toggleAll(): void
    {
        $this->toggleGroup($this->allNames);
    }

    public function save(): void
    {
        $menuNames = $this->allNames;
        $chosen = array_values(array_intersect($this->selected, $menuNames));

        // Make sure each menu permission exists for this role's guard.
        foreach ($menuNames as $name) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => $this->role->guard_name]);
        }

        // Keep permissions that are not linked to any menu, so they are not wiped.
        $keep = $this->role->permissions()->pluck('name')->diff($menuNames)->all();

        $this->role->syncPermissions(array_merge($keep, $chosen));

        $this->selected = array_values(array_unique(array_merge($keep, $chosen)));
        $this->original = $this->selected;

        $this->dispatch('swal', icon: 'success', title: __('Permissions applied to ":name".', ['name' => $this->role->name]));
    }

    public function render()
    {
        return view('livewire.admin.user-management.roles.apply-permission');
    }
}

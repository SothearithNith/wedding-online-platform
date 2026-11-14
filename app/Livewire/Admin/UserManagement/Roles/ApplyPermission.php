<?php

namespace App\Livewire\Admin\UserManagement\Roles;

use App\Models\Menu;
use Illuminate\Support\Str;
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

    /** Must match the actions in RolePermissionSeeder. */
    public const ACTIONS = ['view', 'create', 'edit', 'delete', 'manage'];

    public Role $role;

    public string $search = '';

    /** Permission names currently ticked. */
    public array $selected = [];

    /** Permission names stored in the database, used to detect changes. */
    public array $original = [];

    public function mount(Role $role): void
    {
        abort_if(! $role->exists || in_array($role->id, self::PROTECTED_IDS, true), 404);

        $this->role = $role;
        $this->original = $role->permissions()->pluck('name')->all();
        $this->selected = $this->original;
    }

    private function moduleOf(?string $permissionName): ?string
    {
        $module = trim((string) Str::before((string) $permissionName, '.'));

        return $module !== '' ? $module : null;
    }

    #[Computed]
    public function menus()
    {
        return Menu::rootTree()->get();
    }

    /**
     * Groups for the view: each has a menu and rows. A row is one module with its actions.
     * A module that appears in several menus is shown only once.
     */
    #[Computed]
    public function tree(): array
    {
        $seen = [];
        $term = mb_strtolower(trim($this->search));
        $groups = [];

        foreach ($this->menus as $menu) {
            $rows = [];

            foreach (collect([$menu])->merge($menu->children) as $item) {
                $module = $this->moduleOf($item->permission_name);

                if (! $module || isset($seen[$module])) {
                    continue;
                }

                $seen[$module] = true;
                $rows[] = [
                    'label' => $item->name,
                    'module' => $module,
                    'child' => $item->id !== $menu->id,
                    'haystack' => mb_strtolower($item->name_en . ' ' . $item->name_km . ' ' . $module),
                ];
            }

            if ($term !== '') {
                $rows = array_values(array_filter($rows, fn($r) => str_contains($r['haystack'], $term)));
            }

            if ($rows !== []) {
                $groups[] = ['menu' => $menu, 'rows' => $rows];
            }
        }

        return $groups;
    }

    /** Every module that a menu can grant, unfiltered by search. */
    #[Computed]
    public function modules(): array
    {
        return $this->menus
            ->flatMap(fn($m) => collect([$m])->merge($m->children))
            ->map(fn($m) => $this->moduleOf($m->permission_name))
            ->filter()->unique()->values()->all();
    }

    /** Every permission name this page is allowed to grant. */
    #[Computed]
    public function allNames(): array
    {
        return collect($this->modules)
            ->flatMap(fn($module) => array_map(fn($a) => "{$module}.{$a}", self::ACTIONS))
            ->all();
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

    /** Toggle one permission. Create/edit/delete/manage also need view. */
    public function toggle(string $name): void
    {
        if (! in_array($name, $this->allNames, true)) {
            return;
        }

        [$module, $action] = explode('.', $name, 2);
        $on = in_array($name, $this->selected, true);

        if ($on) {
            $remove = $action === 'view'
                ? array_map(fn($a) => "{$module}.{$a}", self::ACTIONS)
                : [$name];

            $this->selected = array_values(array_diff($this->selected, $remove));

            return;
        }

        $add = $action === 'view' ? [$name] : ["{$module}.view", $name];

        $this->selected = array_values(array_unique(array_merge($this->selected, $add)));
    }

    /** All actions of one module on, or all off. */
    public function toggleModule(string $module): void
    {
        if (! in_array($module, $this->modules, true)) {
            return;
        }

        $names = array_map(fn($a) => "{$module}.{$a}", self::ACTIONS);
        $allOn = count(array_intersect($names, $this->selected)) === count($names);

        $this->selected = $allOn
            ? array_values(array_diff($this->selected, $names))
            : array_values(array_unique(array_merge($this->selected, $names)));
    }

    /** One action (for example "view") across every module. */
    public function toggleColumn(string $action): void
    {
        if (! in_array($action, self::ACTIONS, true)) {
            return;
        }

        $names = array_map(fn($m) => "{$m}.{$action}", $this->modules);
        $allOn = $names !== [] && count(array_intersect($names, $this->selected)) === count($names);

        if ($allOn) {
            $remove = $action === 'view' ? $this->allNames : $names;
            $this->selected = array_values(array_diff($this->selected, $remove));

            return;
        }

        $add = $action === 'view'
            ? $names
            : array_merge($names, array_map(fn($m) => "{$m}.view", $this->modules));

        $this->selected = array_values(array_unique(array_merge($this->selected, $add)));
    }

    public function toggleAll(): void
    {
        $this->selected = $this->selectedCount === count($this->allNames)
            ? array_values(array_diff($this->selected, $this->allNames))
            : array_values(array_unique(array_merge($this->selected, $this->allNames)));
    }

    public function save(): void
    {
        $names = $this->allNames;
        $chosen = array_values(array_intersect($this->selected, $names));

        // Make sure each permission exists for this role's guard.
        foreach ($names as $name) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => $this->role->guard_name]);
        }

        // Keep permissions that no menu can grant, so they are not wiped.
        $keep = $this->role->permissions()->pluck('name')->diff($names)->all();

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

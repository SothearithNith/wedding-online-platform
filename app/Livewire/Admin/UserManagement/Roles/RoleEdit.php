<?php

namespace App\Livewire\Admin\UserManagement\Roles;

use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Spatie\Permission\Models\Role;

#[Layout('layouts.app')]
#[Title('Roles-Edit')]
class RoleEdit extends Component
{
    private const PROTECTED_IDS = [1];

    public Role $role;

    public string $name = '';

    public bool $is_public = true;

    public bool $status = true;

    public function mount(Role $role): void
    {
        abort_if(! $role->exists || in_array($role->id, self::PROTECTED_IDS, true), 404);

        $this->role = $role;
        $this->name = (string) $role->name;
        $this->is_public = (bool) $role->is_public;
        $this->status = (bool) $role->status;
    }

    protected function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:2',
                'max:50',
                'regex:/^[\pL\pN\s\-_]+$/u',
                Rule::unique('roles', 'name')
                    ->where('guard_name', $this->role->guard_name)
                    ->ignore($this->role->id),
            ],
            'is_public' => ['boolean'],
            'status' => ['boolean'],
        ];
    }

    protected function validationAttributes(): array
    {
        return ['name' => __('role name')];
    }

    public function updatedName(): void
    {
        $this->validateOnly('name');
    }

    #[Computed]
    public function hasChanges(): bool
    {
        return trim($this->name) !== $this->role->name
            || $this->is_public !== (bool) $this->role->is_public
            || $this->status !== (bool) $this->role->status;
    }

    #[Computed]
    public function permissionsCount(): int
    {
        return $this->role->permissions()->count();
    }

    public function save(): void
    {
        $this->name = trim($this->name);
        $this->validate();

        $this->role->update([
            'name' => $this->name,
            'is_public' => $this->is_public,
            'status' => $this->status,
        ]);

        $this->dispatch('swal', icon: 'success', title: __('Role updated successfully.'));
    }

    public function render()
    {
        return view('livewire.admin.user-management.roles.role-edit');
    }
}

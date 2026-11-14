<?php

namespace App\Livewire\Admin\UserManagement\Roles;

use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Spatie\Permission\Models\Role;

#[Layout('layouts.app')]
#[Title('Roles-Create')]
class RoleCreate extends Component
{
    public string $name = '';

    public string $guard_name = '';

    public bool $is_public = true;

    public bool $status = true;

    public function mount(): void
    {
        $this->guard_name = config('auth.defaults.guard', 'web');
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
                Rule::unique('roles', 'name')->where('guard_name', $this->guard_name),
            ],
            'guard_name' => ['required', Rule::in(array_keys(config('auth.guards', [])))],
            'is_public' => ['boolean'],
            'status' => ['boolean'],
        ];
    }

    protected function validationAttributes(): array
    {
        return ['name' => __('role name'), 'guard_name' => __('guard')];
    }

    public function updated(string $property): void
    {
        if (in_array($property, ['name', 'guard_name'], true)) {
            $this->validateOnly('name');
        }
    }

    public function save(): void
    {
        $this->name = trim($this->name);
        $this->validate();

        $role = Role::create([
            'name' => $this->name,
            'guard_name' => $this->guard_name,
            'is_public' => $this->is_public,
            'status' => $this->status,
        ]);

        $this->dispatch('role-created', id: $role->id, name: $role->name);
    }

    public function render()
    {
        return view('livewire.admin.user-management.roles.role-create', [
            'guards' => array_keys(config('auth.guards', [])),
        ]);
    }
}

<?php

namespace App\Livewire\Admin\UserManagement\Users;

use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

trait UserFormFields
{
    public string $name = '';

    public string $username = '';

    public string $email = '';

    public ?int $role_id = null;

    public string $password = '';

    public string $password_confirmation = '';

    protected function userRules(?int $ignoreId = null, bool $passwordRequired = true): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'username' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'regex:/^[a-z0-9._-]+$/i',
                Rule::unique('users', 'username')->ignore($ignoreId),
            ],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')->ignore($ignoreId)],
            'role_id' => [
                'required',
                Rule::exists('roles', 'id')->where('status', true)->whereNot('id', 1),
            ],
            'password' => [$passwordRequired ? 'required' : 'nullable', 'string', 'min:6', 'confirmed'],
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'name' => __('name'),
            'username' => __('username'),
            'role_id' => __('role'),
            'password' => __('password'),
        ];
    }

    public function updatedUsername(): void
    {
        $this->username = mb_strtolower(trim($this->username));
        $this->validateOnly('username');
    }

    public function availableRoles()
    {
        return Role::where('status', true)->whereNot('id', 1)->orderBy('name')->get(['id', 'name']);
    }
}

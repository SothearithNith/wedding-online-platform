<?php

namespace App\Livewire\Admin\UserManagement\Users;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Users-Edit')]
class UserEdit extends Component
{
    use UserFormFields;

    public User $user;

    public function mount(User $user): void
    {
        abort_if(! $user->exists || $user->role_id === 1, 404);

        $this->user = $user;
        $this->name = (string) $user->name;
        $this->username = (string) $user->username;
        $this->email = (string) $user->email;
        $this->role_id = $user->role_id;
    }

    protected function rules(): array
    {
        return $this->userRules($this->user->id, passwordRequired: false);
    }

    public function save(): void
    {
        $this->username = mb_strtolower(trim($this->username));
        $data = $this->validate();

        $payload = [
            'name' => trim($data['name']),
            'username' => $data['username'],
            'email' => $data['email'] ?: null,
            'role_id' => $data['role_id'],
        ];

        if (! empty($data['password'])) {
            $payload['password'] = $data['password']; // hashed by the model cast
        }

        $this->user->update($payload);
        $this->reset(['password', 'password_confirmation']);

        $this->dispatch('user-saved', name: $this->user->name);
    }

    public function render()
    {
        return view('livewire.admin.user-management.users.user-edit', [
            'roles' => $this->availableRoles(),
            'mode' => 'edit',
        ]);
    }
}

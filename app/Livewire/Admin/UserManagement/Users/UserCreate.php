<?php

namespace App\Livewire\Admin\UserManagement\Users;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Users-Create')]
class UserCreate extends Component
{
    use UserFormFields;

    protected function rules(): array
    {
        return $this->userRules();
    }

    public function save(): void
    {
        $this->username = mb_strtolower(trim($this->username));
        $data = $this->validate();

        $user = User::create([
            'name' => trim($data['name']),
            'username' => $data['username'],
            'email' => $data['email'] ?: null,
            'role_id' => $data['role_id'],
            'password' => $data['password'], // hashed by the model cast
        ]);

        $this->dispatch('user-saved', name: $user->name);
    }

    public function render()
    {
        return view('livewire.admin.user-management.users.user-create', [
            'roles' => $this->availableRoles(),
            'mode' => 'create',
        ]);
    }
}

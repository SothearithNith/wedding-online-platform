<?php

namespace App\Livewire\Admin\UserManagement\Users;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Users')]
class UserList extends Component
{
    public function render()
    {
        return view('livewire.admin.user-management.users.user-list');
    }
}

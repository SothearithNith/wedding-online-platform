<?php

namespace App\Livewire\Admin\UserManagement\Users;

use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

#[Layout('layouts.app')]
#[Title('Users')]
class UserList extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public string $search = '';

    #[Url(except: 'all')]
    public string $role = 'all';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingRole(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'role']);
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id() || $user->role_id === 1) {
            $this->dispatch('swal', icon: 'error', title: __('This user cannot be deleted.'));

            return;
        }

        $user->delete();
        $this->dispatch('swal', icon: 'success', title: __('":name" has been deleted.', ['name' => $user->name]));
    }

    #[Computed]
    public function total(): int
    {
        return User::count();
    }

    #[Computed]
    public function roles()
    {
        return Role::whereNot('id', 1)->orderBy('name')->get(['id', 'name']);
    }

    public function render()
    {
        $users = User::query()
            ->with('role:id,name,status')
            ->when($this->search !== '', fn($q) => $q->where(fn($q) => $q
                ->where('name', 'like', "%{$this->search}%")
                ->orWhere('username', 'like', "%{$this->search}%")
                ->orWhere('email', 'like', "%{$this->search}%")))
            ->when($this->role !== 'all', fn($q) => $q->where('role_id', (int) $this->role))
            ->latest('id')
            ->paginate(10);

        return view('livewire.admin.user-management.users.user-list', compact('users'));
    }
}

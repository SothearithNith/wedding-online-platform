<?php

namespace App\Livewire\Admin\UserManagement\Roles;

use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

#[Layout('layouts.app')]
#[Title('Roles')]
class RoleList extends Component
{
    use WithPagination;

    private const PROTECTED_IDS = [1];

    #[Url(except: '')]
    public string $search = '';

    #[Url(except: 'all')]
    public string $status = 'all';

    #[Url(except: 'all')]
    public string $visibility = 'all';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function updatingVisibility(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'status', 'visibility']);
        $this->resetPage();
    }

    public function toggleActive(int $id): void
    {
        $role = Role::whereNotIn('id', self::PROTECTED_IDS)->findOrFail($id);
        $role->update(['status' => ! $role->status]);

        $this->dispatch(
            'swal',
            icon: 'success',
            title: $role->status
                ? __('":name" has been activated.', ['name' => $role->name])
                : __('":name" has been deactivated.', ['name' => $role->name]),
        );
    }

    #[Computed]
    public function total(): int
    {
        return Role::whereNotIn('id', self::PROTECTED_IDS)->count();
    }

    #[Computed]
    public function activeCount(): int
    {
        return Role::whereNotIn('id', self::PROTECTED_IDS)->where('status', true)->count();
    }

    #[Computed]
    public function totalPermissions(): int
    {
        return max(Permission::count(), 1);
    }

    private function getDS()
    {
        return Role::query()
            ->whereNotIn('id', self::PROTECTED_IDS)
            ->with('permissions:id,name')
            ->withCount(['permissions', 'users'])
            ->when($this->search !== '', fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->when($this->status === 'active', fn($q) => $q->where('status', true))
            ->when($this->status === 'inactive', fn($q) => $q->where('status', false))
            ->when($this->visibility === 'public', fn($q) => $q->where('is_public', true))
            ->when($this->visibility === 'private', fn($q) => $q->where('is_public', false))
            ->latest('id')
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.admin.user-management.roles.role-list', [
            'roles' => $this->getDS(),
        ]);
    }
}

<?php

namespace App\Livewire\Admin\Templates;

use App\Models\Template;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Templates')]
class TemplateList extends Component
{
    use WithPagination;

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

    public function toggleActive(int $id): void
    {
        $template = Template::findOrFail($id);
        $template->update(['is_active' => ! $template->is_active]);
    }

    public function delete(int $id): void
    {
        $template = Template::findOrFail($id);

        if ($template->invitations()->exists()) {
            $this->dispatch(
                'swal',
                icon: 'error',
                title: 'Cannot delete',
                text: 'This template is used by invitations. Deactivate it instead.'
            );
            return;
        }

        if ($template->preview_url && ! str_starts_with($template->preview_url, 'http')) {
            Storage::disk('public')->delete($template->preview_url);
        }

        $template->delete();

        $this->dispatch('swal', icon: 'success', title: 'Deleted', text: 'Template deleted.');
    }

    public function render()
    {
        $templates = Template::query()
            ->withCount('invitations')
            ->when($this->search, fn($q) => $q->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('key', 'like', "%{$this->search}%")
                    ->orWhere('description', 'like', "%{$this->search}%");
            }))
            ->when($this->status === 'active', fn($q) => $q->where('is_active', true))
            ->when($this->status === 'inactive', fn($q) => $q->where('is_active', false))
            ->when($this->visibility === 'public', fn($q) => $q->where('is_public', true))
            ->when($this->visibility === 'private', fn($q) => $q->where('is_public', false))
            ->orderBy('sort')
            ->latest('id')
            ->paginate(12);
            
        return view('livewire.admin.templates.template-list', [
            'templates' => $templates,
            'total' => Template::count(),
            'activeCount' => Template::where('is_active', true)->count(),
            'publicCount' => Template::where('is_public', true)->count(),
        ]);
    }
}

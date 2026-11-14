<?php

namespace App\Livewire\Admin\Wedding;

use App\Models\Invitation;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Wedding')]
class ListWedding extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    /** all | published | draft */
    #[Url(as: 's')]
    public string $status = 'all';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function setStatus(string $status): void
    {
        $this->status = in_array($status, ['all', 'published', 'draft'], true) ? $status : 'all';
        $this->resetPage();
    }

    /** Only the logged-in user's own weddings. */
    private function mine(int $id): Invitation
    {
        return Invitation::where('user_id', auth()->id())->findOrFail($id);
    }

    public function togglePublish(int $id): void
    {
        $wedding = $this->mine($id);

        if ($wedding->isPublished()) {
            $wedding->update(['status' => 'draft']);
            $this->dispatch('swal', type: 'info', title: __('Wedding unpublished.'));
        } else {
            $wedding->update(['status' => 'published', 'published_at' => $wedding->published_at ?? now()]);
            $this->dispatch('swal', type: 'success', title: __('Wedding published.'));
        }
    }

    public function delete(int $id): void
    {
        // the model's "deleting" hook also removes its children and the uploaded pictures/music
        $this->mine($id)->delete();

        $this->dispatch('swal', type: 'success', title: __('Wedding deleted.'));
    }

    public function render()
    {
        $term = trim($this->search);

        $perStatus = Invitation::where('user_id', auth()->id())
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $counts = [
            'all'=> (int) $perStatus->sum(),
            'published' => (int) ($perStatus['published'] ?? 0),
            'draft' => (int) ($perStatus['draft'] ?? 0),
        ];

        $weddings = Invitation::with(['template', 'people'])
            ->where('user_id', auth()->id())
            ->when($this->status !== 'all', fn ($q) => $q->where('status', $this->status))
            ->when($term !== '', function ($q) use ($term) {
                $like = '%' . $term . '%';

                $q->where(function ($q) use ($like) {
                    $q->where('slug', 'like', $like)
                        ->orWhere('label', 'like', $like)
                        ->orWhereHas('people', fn ($p) => $p->where('name', 'like', $like));
                });
            })
            ->latest('updated_at')
            ->paginate(9);

        return view('livewire.admin.wedding.list-wedding', compact('weddings', 'counts'));
    }
}

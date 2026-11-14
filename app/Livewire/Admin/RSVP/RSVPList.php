<?php

namespace App\Livewire\Admin\RSVP;

use App\Models\Invitation;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('RSVP')]
class RSVPList extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        $term = trim($this->search);

        $perStatus = Invitation::where('user_id', auth()->id())
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $counts = [
            'all'       => (int) $perStatus->sum(),
            'published' => (int) ($perStatus['published'] ?? 0),
            'draft'     => (int) ($perStatus['draft'] ?? 0),
        ];

        $weddings = Invitation::with(['template', 'people'])
            ->where('user_id', auth()->id())
            ->where('status', 'published')
            ->when($term !== '', fn($q) => $q->where('label', 'like', '%' . $term . '%'))
            ->latest('updated_at')
            ->paginate(9);

        return view('livewire.admin.r-s-v-p.r-s-v-p-list', compact('weddings', 'counts'));
    }
}

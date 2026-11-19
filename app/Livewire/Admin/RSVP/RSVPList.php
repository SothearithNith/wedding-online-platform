<?php

namespace App\Livewire\Admin\RSVP;

use App\Models\Invitation;
use App\Models\Rsvp;
use Illuminate\Support\Facades\Auth;
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
    public $isAdmin = true;
    public $pushId = 0;

    public function mount()
    {
        $this->isAdmin = Auth::user()->role_id != 3;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function pushInvitationId($id)
    {
        $this->pushId = (int) $id;
    }

    public function closeDetail()
    {
        $this->pushId = 0;
    }

    public function render()
    {
        $term = trim($this->search);

        $perStatus = Invitation::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $counts = [
            'all' => (int) $perStatus->sum(),
            'published' => (int) ($perStatus['published'] ?? 0),
            'draft' => (int) ($perStatus['draft'] ?? 0),
        ];

        $weddings = Invitation::with(['template', 'people'])
            ->where('status', 'published')
            ->when($term !== '', fn($q) => $q->where('label', 'like', '%' . $term . '%'))
            ->latest('updated_at')
            ->paginate(9);

        $selected = null;

        if ($this->isAdmin) {
            if ($this->pushId) {
                $selected = Invitation::find($this->pushId);
            }
        } else {
            $selected = Invitation::where('user_id', Auth::id())->first();
        }

        $rsvp = $selected
            ? Rsvp::where('invitation_id', $selected->id)->latest()->get()
            : collect();

        return view('livewire.admin.r-s-v-p.r-s-v-p-list', compact(
            'weddings',
            'counts',
            'rsvp',
            'selected'
        ));
    }
}

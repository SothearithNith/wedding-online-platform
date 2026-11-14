<?php

namespace App\Livewire\Admin\RSVP;

use App\Models\Invitation;
use App\Models\Rsvp;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('RSVP-Details')]
class RSVPDetails extends Component
{
    public $rsvp = [];
    public $invitation = [];

    public function mount($rsvp)
    {
        $this->invitation = Invitation::with(['template', 'people'])
            ->where('user_id', auth()->id())
            ->findOrFail($rsvp);

        $this->rsvp = Rsvp::where('invitation_id', $this->invitation->id)->get();
        dd($this->rsvp);
    }
    public function render()
    {
        return view('livewire.admin.r-s-v-p.r-s-v-p-details');
    }
}

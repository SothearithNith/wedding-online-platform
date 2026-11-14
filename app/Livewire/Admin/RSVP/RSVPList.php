<?php

namespace App\Livewire\Admin\RSVP;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('RSVP')]
class RSVPList extends Component
{
    public function render()
    {
        return view('livewire.admin.r-s-v-p.r-s-v-p-list');
    }
}

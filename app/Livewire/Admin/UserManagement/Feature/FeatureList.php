<?php

namespace App\Livewire\Admin\UserManagement\Feature;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Features')]
class FeatureList extends Component
{
    public function render()
    {
        return view('livewire.admin.user-management.feature.feature-list');
    }
}

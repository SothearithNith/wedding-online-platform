<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.pub')]
#[Title('Dashboard')]
class Dashboard extends Component
{
    // app/Livewire/Dashboard.php
    public function render()
    {
        $templates = [
            ['name' => 'Classic Rose',   'category' => 'Classic', 'image' => null, 'url' => '#'],
            ['name' => 'Golden Khmer',   'category' => 'Khmer',   'image' => null, 'url' => '#'],
            ['name' => 'Modern Minimal', 'category' => 'Modern',  'image' => null, 'url' => '#'],
            ['name' => 'Floral Dream',   'category' => 'Floral',  'image' => null, 'url' => '#'],
        ];

        return view('livewire.dashboard', compact('templates'));
    }
}

<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.guest')]
#[Title('Admin Login')]
class Login extends Component
{
    public string $username = '';

    public string $password = '';

    public bool $remember = false;

    public function mount()
    {
        if (Auth::check()) {
            return $this->redirectRoute('admin');
        }
    }

    public function login()
    {
        if (Auth::check()) {
            return $this->redirectRoute('admin');
        }

        $validated = $this->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($validated, $this->remember)) {
            $this->reset('password');

            $this->dispatch(
                'swal',
                icon: 'error',
                title: 'Login Failed',
                text: 'Invalid username or password.'
            );

            return;
        }

        session()->regenerate();

        return $this->redirectRoute('admin', navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}

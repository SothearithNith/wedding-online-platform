<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.guest')]
#[Title('Admin Login')]
class Login extends Component
{
    public string $email = '';

    public string $password = '';

    public bool $remember = false;

    public function mount(): void
    {
        if (Auth::check()) {
            $this->redirectRoute('admin');
        }
    }

    public function login(): void
    {
        $validated = $this->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $authenticated = Auth::attempt([
            'email' => $validated['email'],
            'password' => $validated['password'],
        ], $this->remember);

        if (! $authenticated) {
            $this->reset('password');

            $this->dispatch(
                'swal',
                icon: 'error',
                title: 'Login Failed',
                text: 'Invalid email address or password.'
            );

            return;
        }

        // Regenerate the session after successful authentication.
        request()->session()->regenerate();

        // Redirect to the administrator dashboard.
        $this->redirectRoute('admin');
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}

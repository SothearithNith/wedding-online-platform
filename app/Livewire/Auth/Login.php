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

    public function mount()
    { 
        if (Auth::check()) {
            return redirect()->route('admin');
        }
    }

    public function login()
    {
        $validated = $this->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt([
            'email' => $validated['email'],
            'password' => $validated['password'],
        ], $this->remember)) {

            $this->dispatch(
                'swal',
                icon: 'error',
                title: 'Login Failed',
                text: 'Invalid email address or password.'
            );

            return;
        }

        request()->session()->regenerate();

        $this->dispatch(
            'swal',
            icon: 'success',
            title: 'Welcome Back!',
            text: 'You have successfully logged in.'
        );

        return $this->redirectRoute('admin', navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}

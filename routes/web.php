<?php

use App\Livewire\Admin\AdminDashboard;
use App\Livewire\Auth\Login;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
});

// Authenticated Admin Routes
Route::middleware('auth')->group(function () {

    Route::get('/admin', AdminDashboard::class)
        ->name('admin');

    // Language Switcher
    Route::get('/language/{locale}', function (string $locale) {

        abort_unless(in_array($locale, ['km', 'en']), 404);

        session(['locale' => $locale]);

        return back();
    })->name('language.switch');

    // Logout
    Route::post('/logout', function () {

        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login')
            ->with('logout_success', true);
    })->name('logout');
});

// Root Redirect
Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('admin')
        : redirect()->route('login');
});

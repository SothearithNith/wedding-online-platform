<?php

use App\Livewire\Admin\AdminDashboard;
use App\Livewire\Auth\Login;
use App\Livewire\Dashboard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Language switcher (works with wire:navigate, follows the redirect)
Route::get('/lang/{locale}', function (string $locale) {
    abort_unless(in_array($locale, ['en', 'km']), 404);

    session(['locale' => $locale]);

    return redirect(url()->previous(route('admin')));
})->name('lang.switch');

// Public
Route::get('/', Dashboard::class)->name('dashboard');

// Guest
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
});

// Admin
Route::middleware('auth')->group(function () {
    Route::get('/admin', AdminDashboard::class)->name('admin');

    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login')->with('logout_success', true);
    })->name('logout');
});

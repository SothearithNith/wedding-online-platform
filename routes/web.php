<?php

use App\Livewire\Admin\AdminDashboard;
use App\Livewire\Auth\Login;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::post('/logout', AdminDashboard::class)->name('logout');
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
});

// Authenticated admin routes
Route::middleware('auth')->group(function () {
    Route::get('/admin', AdminDashboard::class)->name('admin');
});
Route::post('/logout', function () {
    Auth::logout();

    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect()->route('login');
})->name('logout');


<?php

use App\Livewire\Admin\AdminDashboard;
use App\Livewire\Auth\Login;
use App\Livewire\Dashboard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Public Dashboard

// Language Switcher
Route::get('/lang/{locale}', function (string $locale) {
    abort_unless(in_array($locale, ['en', 'km']), 404);
    session(['locale' => $locale]);
    return redirect()->back();
})->name('lang.switch');
Route::get('/', Dashboard::class)
    ->name('dashboard');

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)
        ->name('login');
});

// Authenticated Admin Routes
Route::middleware('auth')->group(function () {

    // Admin Dashboard
    Route::get('/admin', AdminDashboard::class)
        ->name('admin');
    // Logout
    Route::post('/logout', function () {

        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login')
            ->with('logout_success', true);
    })->name('logout');
});

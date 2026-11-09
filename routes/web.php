
<?php

use App\Livewire\Admin\AdminDashboard;
use App\Livewire\Admin\Templates\TemplateForm;
use App\Livewire\Admin\Templates\TemplateList;
use App\Livewire\Auth\Login;
use App\Livewire\Dashboard;
use App\Models\Invitation;
use App\Models\Template;
use App\Support\InvitationPayload;
use App\Support\TemplateRegistry;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', Dashboard::class)->name('dashboard');
Route::get('/i/{invitation}', function (Invitation $invitation) {
    abort_unless($invitation->isPublished(), 404);
    $template = $invitation->template;
    abort_unless($template && $template->is_active, 404);
    return view(TemplateRegistry::viewFor($template->key), [
        'data'    => InvitationPayload::from($invitation)['data'],
        'rsvpUrl' => null,
    ]);
})->name('invitation.show');

Route::middleware('guest')->group(function () {

    Route::get('/login', Login::class)->name('login');
});

Route::get('/lang/{locale}', function (string $locale) {
    abort_unless(in_array($locale, ['en', 'km']), 404);
    session(['locale' => $locale]);
    return redirect()->to(url()->previous(route('dashboard')));
})->name('lang.switch');

Route::middleware('auth')->group(function () {
    Route::get('/admin', AdminDashboard::class)
        ->name('admin');
    Route::prefix('admin/templates')->name('templates.')->group(function () {
        Route::get('/', TemplateList::class)
            ->name('index');
        Route::get('/create', TemplateForm::class)
            ->name('create');
        Route::get('/{template}/edit', TemplateForm::class)
            ->name('edit');
        Route::get('/{template}/preview', function (Template $template) {
            return view(TemplateRegistry::viewFor($template->key), [
                'data' => TemplateRegistry::withDefaults(
                    $template->sample_data ?? [],
                    true
                ),
                'rsvpUrl' => null,
            ]);
        })->name('preview');
    });
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()
            ->route('login')
            ->with('logout_success', true);
    })->name('logout');
});

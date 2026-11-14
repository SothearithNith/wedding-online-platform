
<?php

use App\Http\Controllers\PublicWeddingController;
use App\Http\Controllers\RsvpController;
use App\Livewire\Admin\AdminDashboard;
use App\Livewire\Admin\Templates\TemplateForm;
use App\Livewire\Admin\Templates\TemplateList;
use App\Livewire\Admin\Wedding\ListWedding;
use App\Livewire\Admin\Wedding\WeddingForm;
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
        'data' => InvitationPayload::from($invitation)['data'],
        'rsvpUrl' => null,
    ]);
})->name('invitation.show');

Route::get('/login', Login::class)->name('login');
Route::get('/lang/{locale}', function (string $locale) {
    abort_unless(in_array($locale, ['en', 'km'], true), 404);
    session(['locale' => $locale]);
    return redirect()->back(fallback: route('dashboard'));
})->name('lang.switch');

Route::get('/admin/templates/{template}/preview', function (Template $template) {
    return view(TemplateRegistry::viewFor($template->key), [
        'data' => TemplateRegistry::withDefaults(
            $template->sample_data ?? [],
            true
        ),
        'rsvpUrl' => null,
    ]);
})->name('admin.templates.preview');

Route::middleware(['auth'])
    ->prefix('admin')
    ->name('')
    ->group(function () {
        Route::get('/', AdminDashboard::class)->name('admin');

        Route::prefix('weddings')->name('weddings.')->group(function () {
            Route::get('/', ListWedding::class)->name('index');
            Route::get('/create', WeddingForm::class)->name('create');
            Route::get('/{wedding}/edit', WeddingForm::class)->name('edit');
        });

        Route::prefix('templates')->name('templates.')->group(function () {
            Route::get('/', TemplateList::class)->name('index');
            Route::get('/create', TemplateForm::class)->name('create');
            Route::get('/{template}/edit', TemplateForm::class)->name('edit');
        });

        Route::post('/logout', function () {
            Auth::logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();

            return redirect()->route('login')->with('logout_success', true);
        })->name('logout');
    });

Route::post('/rsvp/{invitation}', [RsvpController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('invitations.rsvp');

Route::get('/{slug}', [PublicWeddingController::class, 'show'])
    ->where('slug', '^(?!admin|login|livewire|lang|up|i$)[A-Za-z0-9\-_]+$')
    ->name('wedding.show');

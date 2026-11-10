
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

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Public homepage
Route::get('/', Dashboard::class)
    ->name('dashboard');

// Public invitation using invitation ID
Route::get('/i/{invitation}', function (Invitation $invitation) {

    abort_unless($invitation->isPublished(), 404);

    $template = $invitation->template;

    abort_unless($template && $template->is_active, 404);

    return view(TemplateRegistry::viewFor($template->key), [
        'data' => InvitationPayload::from($invitation)['data'],
        'rsvpUrl' => null,
    ]);
})->name('invitation.show');


/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    Route::get('/login', Login::class)
        ->name('login');
});


/*
|--------------------------------------------------------------------------
| Language Routes
|--------------------------------------------------------------------------
*/

Route::get('/lang/{locale}', function (string $locale) {

    abort_unless(in_array($locale, ['en', 'km'], true), 404);

    session(['locale' => $locale]);

    return redirect()->back(route('dashboard'));
})->name('lang.switch');


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])
    ->prefix('admin')
    ->name('')
    ->group(function () {

        // Admin dashboard
        Route::get('/', AdminDashboard::class)
            ->name('admin');


        /*
        |--------------------------------------------------------------------------
        | Wedding Management
        |--------------------------------------------------------------------------
        */

        Route::prefix('weddings')
            ->name('weddings.')
            ->group(function () {

                Route::get('/', ListWedding::class)
                    ->name('index');

                Route::get('/create', WeddingForm::class)
                    ->name('create');

                Route::get('/{wedding}/edit', WeddingForm::class)
                    ->name('edit');
            });


        /*
        |--------------------------------------------------------------------------
        | Template Management
        |--------------------------------------------------------------------------
        */

        Route::prefix('templates')
            ->name('templates.')
            ->group(function () {

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


        /*
        |--------------------------------------------------------------------------
        | Logout
        |--------------------------------------------------------------------------
        */

        Route::post('/logout', function () {

            Auth::logout();

            request()->session()->invalidate();

            request()->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->with('logout_success', true);
        })->name('logout');
    });

Route::get('/{slug}', [PublicWeddingController::class, 'show'])->name('wedding.show');
Route::post('/rsvp/{invitation}', [RsvpController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('invitations.rsvp');

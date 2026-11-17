
<?php

use App\Http\Controllers\PublicWeddingController;
use App\Http\Controllers\RsvpController;
use App\Livewire\Admin\AdminDashboard;
use App\Livewire\Admin\RSVP\RSVPDetails;
use App\Livewire\Admin\RSVP\RSVPList;
use App\Livewire\Admin\Templates\TemplateForm;
use App\Livewire\Admin\Templates\TemplateList;
use App\Livewire\Admin\UserManagement\Feature\FeatureList;
use App\Livewire\Admin\UserManagement\Roles\ApplyPermission;
use App\Livewire\Admin\UserManagement\Roles\RoleCreate;
use App\Livewire\Admin\UserManagement\Roles\RoleEdit;
use App\Livewire\Admin\UserManagement\Roles\RoleList;
use App\Livewire\Admin\UserManagement\Users\UserCreate;
use App\Livewire\Admin\UserManagement\Users\UserEdit;
use App\Livewire\Admin\UserManagement\Users\UserList;
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

// Public Dashboard
Route::get('/', Dashboard::class)->name('dashboard');

// Public Invitation
Route::get('/i/{invitation}', function (Invitation $invitation) {
    abort_unless($invitation->isPublished(), 404);

    $template = $invitation->template;
    abort_unless($template && $template->is_active, 404);

    return view(TemplateRegistry::viewFor($template->key), [
        'data' => InvitationPayload::from($invitation)['data'],
        'rsvpUrl' => null,
    ]);
})->name('invitation.show');

// Login
Route::get('/login', Login::class)->name('login');

// Language
Route::get('/lang/{locale}', function (string $locale) {
    abort_unless(in_array($locale, ['en', 'km'], true), 404);

    session(['locale' => $locale]);

    return redirect()->back(fallback: route('dashboard'));
})->name('lang.switch');

// Template Preview
Route::get('/admin/templates/{template}/preview', function (Template $template) {
    return view(TemplateRegistry::viewFor($template->key), [
        'data' => TemplateRegistry::withDefaults(
            $template->sample_data ?? [],
            true
        ),
        'rsvpUrl' => null,
    ]);
})->name('admin.templates.preview');

// Admin Routes
Route::middleware(['auth'])
    ->prefix('admin')
    ->name('')
    ->group(function () {

        Route::get('/', AdminDashboard::class)->name('admin');

        // Wedding Management
        Route::prefix('weddings')->name('weddings.')->group(function () {
            Route::get('/', ListWedding::class)
                ->middleware('prevent.permission:weddings,view')
                ->name('index');

            Route::get('/create', WeddingForm::class)
                ->middleware('prevent.permission:weddings,create')
                ->name('create');

            Route::get('/{wedding}/edit', WeddingForm::class)
                ->middleware('prevent.permission:weddings,edit')
                ->name('edit');
        });

        // Template Management
        Route::prefix('templates')->name('templates.')->group(function () {
            Route::get('/', TemplateList::class)
                ->middleware('prevent.permission:templates,view')
                ->name('index');

            Route::get('/create', TemplateForm::class)
                ->middleware('prevent.permission:templates,create')
                ->name('create');

            Route::get('/{template}/edit', TemplateForm::class)
                ->middleware('prevent.permission:templates,edit')
                ->name('edit');
        });

        // Access Control
        Route::prefix('access-control')->name('access-control.')->group(function () {

            // User Management
            Route::get('/users', UserList::class)
                ->middleware('prevent.permission:users,view')
                ->name('users.index');

            Route::get('/users/create', UserCreate::class)
                ->middleware('prevent.permission:users,create')
                ->name('users.create');

            Route::get('/users/{user}/edit', UserEdit::class)
                ->middleware('prevent.permission:users,edit')
                ->name('users.edit');

            // Role Management
            Route::get('/roles', RoleList::class)
                ->middleware('prevent.permission:roles,view')
                ->name('roles.index');

            Route::get('/roles/create', RoleCreate::class)
                ->middleware('prevent.permission:roles,create')
                ->name('roles.create');

            Route::get('/roles/{role}/edit', RoleEdit::class)
                ->middleware('prevent.permission:roles,edit')
                ->name('roles.edit');

            Route::get('/roles/{role}/permissions', ApplyPermission::class)
                ->middleware('prevent.permission:roles,manage')
                ->name('roles.permissions');

            // Feature Management
            Route::get('/features', FeatureList::class)
                ->middleware('prevent.permission:features,view')
                ->name('features.index');
        });

        // RSVP Management
        Route::prefix('rsvp')->name('rsvp.')->group(function () {
            Route::get('/', RSVPList::class)
                ->middleware('prevent.permission:rsvp,view')
                ->name('index');

            Route::get('/{rsvp}/detail', RSVPDetails::class)
                ->middleware('prevent.permission:rsvp,view')
                ->name('detail');
        });

        // Logout
        Route::post('/logout', function () {
            Auth::logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();

            return redirect()->route('login')->with('logout_success', true);
        })->name('logout');
    });

// Public RSVP Submission
Route::post('/rsvp/{invitation}', [RsvpController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('invitations.rsvp');

// Public Wedding Slug
Route::get('/{slug}', [PublicWeddingController::class, 'show'])
    ->where('slug', '^(?!admin|login|livewire|lang|up|i$)[A-Za-z0-9\-\_]+$')
    ->name('wedding.show');

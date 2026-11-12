<?php

namespace App\Providers;

use App\Livewire\Admin\RSVP\InvitationRsvp;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    // public function register(): void
    // {
    //     //
    // }

    // /**
    //  * Bootstrap any application services.
    //  */
    // public function boot(): void
    // {
    //     Schema::defaultStringLength(191);
    //     Gate::before(function ($user, string $ability) {
    //         return $user->hasRole('Master Admin') ? true : null;
    //         Livewire::component('invitation-rsvp', InvitationRsvp::class);
    //     });


    //     // apply css
    //     if (app()->environment('production')) {
    //         URL::forceScheme('https');
    //     }
    // }
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191);

        Gate::before(function ($user, string $ability) {
            return $user->hasRole('Master Admin') ? true : null;
        });

        // Livewire::component('invitation-rsvp', InvitationRsvp::class);

        if (app()->environment('production')) {
            URL::forceScheme('https');
        }
    }}

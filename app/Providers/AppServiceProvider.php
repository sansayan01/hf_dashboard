<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Register Observers for Google Sheets Sync
        \App\Models\User::observe(\App\Observers\GoogleSheetSyncObserver::class);
        \App\Models\Survey::observe(\App\Observers\GoogleSheetSyncObserver::class);
        \App\Models\Appointment::observe(\App\Observers\GoogleSheetSyncObserver::class);
    }
}


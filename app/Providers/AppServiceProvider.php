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

        // Register Custom Mail Transport for Quota
        \Illuminate\Support\Facades\Mail::extend('quota', function (array $config = []) {
            // Force the inner transport to be SMTP
            $smtpConfig = array_merge($config, ['transport' => 'smtp']);

            return new \App\Mail\Transport\QuotaTransport(
                \Illuminate\Support\Facades\Mail::createSymfonyTransport($smtpConfig),
                $config['daily_limit'] ?? 100
            );
        });

        // Register Observers for Google Sheets Sync
        \App\Models\User::observe(\App\Observers\GoogleSheetSyncObserver::class);
        \App\Models\User::observe(\App\Observers\UserHierarchyObserver::class);
        \App\Models\Survey::observe(\App\Observers\GoogleSheetSyncObserver::class);
        \App\Models\Appointment::observe(\App\Observers\GoogleSheetSyncObserver::class);
    }
}


<?php

namespace App\Providers;

use App\Models\MedicalRecord;
use App\Observers\MedicalRecordObserver;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Midtrans\Config;

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
        MedicalRecord::observe(MedicalRecordObserver::class);

        if (str_contains(request()->getHost(), 'ngrok-free.dev')) {
        URL::forceScheme('https');
    }
    }
}
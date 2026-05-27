<?php

namespace App\Providers;

use App\Models\MedicalRecord;
use App\Observers\MedicalRecordObserver;
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
        MedicalRecord::observe(MedicalRecordObserver::class);
    }
}

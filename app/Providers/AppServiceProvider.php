<?php

namespace App\Providers;

use App\Mail\Transport\BrevoTransport;
use App\Models\MedicalRecord;
use App\Observers\MedicalRecordObserver;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Midtrans\Config;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        MedicalRecord::observe(MedicalRecordObserver::class);

        // Force HTTPS di belakang proxy (ngrok / Railway)
        $host = request()->getHost();
        if (
            str_contains($host, 'ngrok-free.dev') ||
            str_contains($host, 'railway.app') ||
            str_contains($host, 'up.railway.app')
        ) {
            URL::forceScheme('https');
        }

        // Register Brevo HTTP API transport
        Mail::extend('brevo', function () {
            return new BrevoTransport(
                apiKey: config('services.brevo.api_key'),
            );
        });
    }
}
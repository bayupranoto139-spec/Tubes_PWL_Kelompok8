<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PaymentController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/patients', [PatientController::class, 'index']);

Route::get('/payments', [PaymentController::class, 'index']);

Route::post('/payments/pay/{id}', [PaymentController::class, 'pay']);
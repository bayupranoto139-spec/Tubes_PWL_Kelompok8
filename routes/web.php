<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PaymentController;

Route::resource('patients', PatientController::class);

Route::prefix('patient')->name('patient.')->group(function () {
    Route::view('dashboard', 'patients.dashboard')->name('dashboard');
    Route::view('appointment', 'patients.appointment')->name('appointment');
    Route::view('medical-records', 'patients.medical_record')->name('medical_records');
    Route::view('bills', 'patients.bills')->name('bills');
    Route::view('prescriptions', 'patients.prescriptions')->name('prescriptions');
    Route::view('profile', 'patients.profile')->name('profile');
});

Route::get('/', function () {
    return redirect()->route('patient.dashboard');
});

Route::get('/payments', [PaymentController::class, 'index']);
Route::post('/payments/pay/{id}', [PaymentController::class, 'pay']);
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MidtransController;

require __DIR__.'/auth.php';
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PaymentController;


Route::get('/patients', [PatientController::class, 'index']);
Route::get('/patients/create', [PatientController::class, 'create']);
Route::post('/patients/store', [PatientController::class, 'store']);


Route::get('/', function () {
    return view('welcome');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/payment/{bill}', [MidtransController::class, 'createPayment'])->name('payment.create');
    Route::get('/payment/success', [MidtransController::class, 'success'])->name('payment.success');
    Route::get('/payment/unfinish', [MidtransController::class, 'unfinish'])->name('payment.unfinish');
    Route::get('/payment/error', [MidtransController::class, 'error'])->name('payment.error');
});

Route::get('/payment-test/{id}', function ($id) {
    $bill = \App\Models\Bill::find($id) ?? \App\Models\Bill::factory()->create();
    return app(\App\Http\Controllers\MidtransController::class)->createPayment($bill);
});
Route::get('/patients', [PatientController::class, 'index']);

Route::get('/payments', [PaymentController::class, 'index']);

Route::post('/payments/pay/{id}', [PaymentController::class, 'pay']);

// Patient Panel Routes
use App\Http\Controllers\Patient\PatientPanelController;

Route::middleware(['auth'])->prefix('user/patient')->name('patient.')->group(function () {
    Route::get('/dashboard', [PatientPanelController::class, 'dashboard'])->name('dashboard');
    Route::get('/appointments', [PatientPanelController::class, 'appointments'])->name('appointments');
    Route::post('/appointments', [PatientPanelController::class, 'bookAppointment'])->name('appointments.store');
    Route::post('/appointments/{appointment}/cancel', [PatientPanelController::class, 'cancelAppointment'])->name('appointments.cancel');
    Route::get('/medical-records', [PatientPanelController::class, 'medicalRecords'])->name('medical-records');
    Route::get('/bills', [PatientPanelController::class, 'bills'])->name('bills');
    Route::get('/prescriptions', [PatientPanelController::class, 'prescriptions'])->name('prescriptions');
    Route::get('/profile', [PatientPanelController::class, 'profile'])->name('profile');
    Route::post('/profile', [PatientPanelController::class, 'updateProfile'])->name('profile.update');
});
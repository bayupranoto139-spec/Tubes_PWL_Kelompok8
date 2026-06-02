<?php

use App\Http\Controllers\MidtransController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';
use App\Http\Controllers\Patient\PatientPanelController;
use App\Http\Controllers\PaymentController;
use App\Models\Bill;

// NOTE: routes below are for the patient portal (PatientPanelController).

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
    $bill = Bill::find($id) ?? Bill::factory()->create();

    return app(MidtransController::class)->createPayment($bill);
});

Route::get('/payments', [PaymentController::class, 'index']);

Route::post('/payments/pay/{id}', [PaymentController::class, 'pay']);

// Patient Panel Routes
use App\Http\Controllers\Patient\PatientPanelController;
use App\Models\Bill;

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

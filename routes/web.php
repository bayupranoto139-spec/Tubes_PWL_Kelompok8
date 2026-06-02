<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\Patient\PatientPanelController;

require __DIR__.'/auth.php';

Route::resource('patients', PatientController::class);

// Route patient lama
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

// Payment lama
Route::get('/payments', [PaymentController::class, 'index']);
Route::post('/payments/pay/{id}', [PaymentController::class, 'pay']);

// Midtrans
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

// Patient Panel Routes
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
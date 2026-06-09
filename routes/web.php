<?php

use App\Http\Controllers\GuestDashboardController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\Patient\PatientPanelController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\WalkInController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';
require __DIR__.'/doctor.php';

// Patients routes
Route::get('/patients', [PatientController::class, 'index']);
Route::get('/patients/create', [PatientController::class, 'create']);
Route::post('/patients/store', [PatientController::class, 'store']);

// Public/Guest dashboard
Route::get('/', [GuestDashboardController::class, 'index'])->name('guest.dashboard');

// MIDTRANS: Static pages for payment results
Route::get('/payment/success', [MidtransController::class, 'success'])->name('payment.success');
Route::get('/payment/unfinish', [MidtransController::class, 'unfinish'])->name('payment.unfinish');
Route::get('/payment/error', [MidtransController::class, 'error'])->name('payment.error');

// Payment page — requires auth
Route::middleware(['auth'])->group(function () {
    Route::get('/payment/{bill}', [MidtransController::class, 'createPayment'])->name('payment.create');
});

// Payments list & manual pay
Route::get('/payments', [PaymentController::class, 'index']);
Route::post('/payments/pay/{id}', [PaymentController::class, 'pay']);

// Admin Walk-in queue registration (admin_rs & staff only)
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::post('/walk-in', [WalkInController::class, 'store'])->name('admin.walk-in.store');
});

// Patient Panel Routes
Route::middleware(['auth', \App\Http\Middleware\EnsurePatientEmailVerified::class])->prefix('user/patient')->name('patient.')->group(function () {
    Route::get('/dashboard', [PatientPanelController::class, 'dashboard'])->name('dashboard');
    Route::get('/appointments', [PatientPanelController::class, 'appointments'])->name('appointments');
    Route::post('/appointments', [PatientPanelController::class, 'bookAppointment'])->name('appointments.store');
    Route::post('/appointments/{appointment}/cancel', [PatientPanelController::class, 'cancelAppointment'])->name('appointments.cancel');
    Route::get('/medical-records', [PatientPanelController::class, 'medicalRecords'])->name('medical-records');
    Route::get('/bills', [PatientPanelController::class, 'bills'])->name('bills');
    Route::get('/prescriptions', [PatientPanelController::class, 'prescriptions'])->name('prescriptions');
    Route::get('/profile', [PatientPanelController::class, 'profile'])->name('profile');
    Route::post('/profile', [PatientPanelController::class, 'updateProfile'])->name('profile.update');
    Route::get('/hospitals', [PatientPanelController::class, 'hospitals'])->name('hospitals');
    Route::post('/hospitals/enroll', [PatientPanelController::class, 'enrollHospital'])->name('hospitals.enroll');
});
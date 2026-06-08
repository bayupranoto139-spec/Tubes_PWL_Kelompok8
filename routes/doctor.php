<?php

use App\Http\Controllers\Doctor\AppointmentCompleteController;
use App\Http\Controllers\Doctor\AppointmentController;
use App\Http\Controllers\Doctor\DashboardController;
use App\Http\Controllers\Doctor\MedicalRecordController;
use App\Http\Controllers\Doctor\PrescriptionController;
use App\Http\Controllers\Doctor\PrescriptionStoreController;
use App\Http\Controllers\Doctor\ProfileController;
use App\Http\Controllers\Doctor\ProfileUpdateController;
use App\Http\Controllers\Doctor\ScheduleController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('doctor')->name('doctor.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/today', [DashboardController::class, 'today'])->name('today');
    Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule');
    Route::get('/prescription', [PrescriptionController::class, 'index'])->name('prescription');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/update', [ProfileUpdateController::class, 'update'])->name('profile.update');

    Route::post('/prescription', [PrescriptionStoreController::class, 'store'])->name('prescription.store');

    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments');

    // Complete appointment → auto generate bill
    Route::post('/appointments/{appointment}/complete', [AppointmentCompleteController::class, 'complete'])->name('appointments.complete');

    // Medical Record
    Route::get('/medical-records/{appointment}/create', [MedicalRecordController::class, 'create'])->name('medical-records.create');
    Route::post('/medical-records/{appointment}', [MedicalRecordController::class, 'store'])->name('medical-records.store');
});
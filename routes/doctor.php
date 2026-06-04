<?php

use App\Http\Controllers\Doctor\AppointmentController;
use App\Http\Controllers\Doctor\DashboardController;
use App\Http\Controllers\Doctor\PrescriptionController;
use App\Http\Controllers\Doctor\ProfileController;
use App\Http\Controllers\Doctor\ScheduleController;
use Illuminate\Support\Facades\Route;

// Routes fitur Panel Dokter
Route::middleware(['auth'])->prefix('doctor')->name('doctor.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Jika method berikut memang tersedia di controller yang kamu punya, gunakan.
    // Kalau belum ada, hapus/ubah sesuai controller.
    Route::get('/today', [DashboardController::class, 'today'])->name('today');
    Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule');
    Route::get('/prescription', [PrescriptionController::class, 'index'])->name('prescription');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/update', [\App\Http\Controllers\Doctor\ProfileUpdateController::class, 'update'])->name('profile.update');

    Route::post('/prescription', [\App\Http\Controllers\Doctor\PrescriptionStoreController::class, 'store'])->name('prescription.store');

    // Jika memang ada halaman appointment doctor
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments');
});

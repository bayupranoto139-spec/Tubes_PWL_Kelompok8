<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuestDashboardController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\Doctor\DashboardController;
use App\Http\Controllers\Doctor\ScheduleController;
use App\Http\Controllers\Doctor\AppointmentController;
use App\Http\Controllers\Doctor\PrescriptionController;
use App\Http\Controllers\Doctor\ProfileController;

require __DIR__.'/auth.php';

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

// -------------------------------------------------------
// ROUTE BARU: Guest Dashboard (tidak butuh login)
// -------------------------------------------------------
Route::get('/dashboard', [GuestDashboardController::class, 'index'])->name('guest.dashboard');

// -------------------------------------------------------
// ROUTE BARU: Fitur-fitur yang butuh login
// Guest yang klik tombol aksi di dashboard akan diarahkan
// ke sini, lalu middleware akan redirect ke login.
// -------------------------------------------------------
Route::middleware([\App\Http\Middleware\RedirectGuestToLogin::class])->group(function () {
    // Contoh: jika kamu punya fitur/halaman lain di luar Filament
    // yang juga perlu dibatasi untuk guest, daftarkan di sini.
    // Route::get('/some-feature', ...)->name('feature.xyz');
});

// Grouping semua route khusus dokter
Route::prefix('doctor')->name('doctor.')->group(function () {
    
    // 1. Route Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // 2. Route Jadwal Hari Ini (Today)
    Route::get('/today', [AppointmentController::class, 'today'])->name('today');
    
    // 3. Route Semua Jadwal Praktik
    Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule');
    
    // 4. Route Resep Obat (Prescription)
    Route::get('/prescription', [PrescriptionController::class, 'index'])->name('prescription');
    
    // 5. Route Profil Dokter
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    
});
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuestDashboardController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\Doctor\DashboardController;
use App\Http\Controllers\Doctor\ScheduleController;
use App\Http\Controllers\Doctor\AppointmentController;
use App\Http\Controllers\Doctor\PrescriptionController;
use App\Http\Controllers\Doctor\ProfileController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\User\UserProfileController;
use App\Http\Controllers\User\DoctorScheduleController;
use App\Http\Controllers\User\DoctorAppointmentController;
use App\Http\Controllers\User\MedicalRecordController;


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
Route::prefix('dashboard')->name('dashboard.')->middleware(['auth', 'role:pasien,dokter'])->group(function () {

    // Dashboard utama
    Route::get('/', [UserDashboardController::class, 'index'])->name('index');

    // Profile
    Route::get('profile', [UserProfileController::class, 'index'])->name('profile');
    Route::put('profile', [UserProfileController::class, 'update'])->name('profile.update');

    // ── Doctor-only routes ──────────────────────────────────
    Route::get('today',         [DoctorAppointmentController::class, 'index'])->name('today');
    Route::get('schedule',      [DoctorScheduleController::class,    'index'])->name('schedule');
    Route::get('prescriptions', [PrescriptionController::class,      'index'])->name('prescriptions');

    // Examination (form & store)
    Route::get ('examination/{appointment}', [MedicalRecordController::class, 'create'])->name('examination');
    Route::post('examination/{appointment}', [MedicalRecordController::class, 'store']) ->name('examination.store');

});
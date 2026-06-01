<?php

// =========================================================
// TAMBAHKAN BARIS-BARIS INI KE FILE routes/web.php YANG ADA
// =========================================================

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuestDashboardController;
use App\Http\Controllers\MidtransController;

// -------------------------------------------------------
// Route yang sudah ada (JANGAN DIHAPUS)
// -------------------------------------------------------
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

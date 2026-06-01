<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MidtransController;

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
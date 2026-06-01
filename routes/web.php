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
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\MidtransController;

Route::get('/', [PaymentController::class, 'index']);
Route::post('/payment/process', [PaymentController::class, 'process']);

Route::post('/midtrans/callback', [MidtransController::class, 'callback']);

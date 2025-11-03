<?php

use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');



Route::prefix('/account')->group(function () {
    Route::post('/', [AccountController::class, 'store']);
    Route::get('/{account}', [AccountController::class, 'get']);
    Route::get('/{account}/payments', [PaymentController::class, 'getPaymentsByAccountId']);
});

Route::prefix('/payments')->group(function () {
    Route::post('/', [PaymentController::class, 'store']);
});


<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\SignalController;

Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/verify-email', 'verifyEmail');
    Route::post('/login', 'login');
});

Route::middleware('auth:sanctum')
    ->group(function () {

        Route::post('/profile-setup', [ProfileController::class, 'setup']);

        Route::apiResource('signals', SignalController::class);

    });

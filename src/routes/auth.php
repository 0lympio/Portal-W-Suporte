<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
                ->name('auth.login');

    Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('auth.store');
});

Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
            ->middleware('session')
            ->name('auth.logout');

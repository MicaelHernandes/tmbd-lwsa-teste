<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;

Route::middleware('guest')->prefix('auth')->group(function () {
    Route::post('/register', [RegisterController::class, 'register'])->name('auth.register');
});

<?php

use App\Http\Controllers\MovieController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::middleware('guest')->prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
});

Route::prefix('movies')->group(function () {
    Route::get('/', [MovieController::class, 'index'])->name('movies.index');
    Route::get('/search', [MovieController::class, 'search'])->name('movies.search');
});

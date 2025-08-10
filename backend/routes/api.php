<?php

use App\Http\Controllers\MovieController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FavoriteMovieController;

Route::middleware('guest')->prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::get('/me', [AuthController::class, 'me'])->name('auth.me')->middleware('auth:sanctum');
});

Route::prefix('movies')->group(function () {
    Route::get('/', [MovieController::class, 'index'])->name('movies.index');
    Route::get('/search', [MovieController::class, 'search'])->name('movies.search');
});


Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('favorite_movies')->group(function () {
        Route::get('/', [FavoriteMovieController::class, 'index'])->name('favorite_movies.index');
        Route::post('/', [FavoriteMovieController::class, 'store'])->name('favorite_movies.store');
        Route::delete('/', [FavoriteMovieController::class, 'destroy'])->name('favorite_movies.destroy');
    });
});

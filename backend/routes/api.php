<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
  $movie =  app(\App\Services\TMDB\MovieService::class)->getMovieByName('The Matrix');
  return response()->json($movie);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

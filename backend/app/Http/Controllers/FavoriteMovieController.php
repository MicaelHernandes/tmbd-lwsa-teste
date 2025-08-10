<?php

namespace App\Http\Controllers;

use App\Http\Requests\FavoriteMovieDeleteRequest;
use App\Http\Requests\FavoriteMovieStoreRequest;
use App\Services\FavoriteMovie\FavoriteMovieService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FavoriteMovieController extends Controller
{
    private readonly FavoriteMovieService $favoriteMovieService;

    public function __construct(FavoriteMovieService $favoriteMovieService)
    {
        $this->favoriteMovieService = $favoriteMovieService;
    }

    public function store(FavoriteMovieStoreRequest $request) : JsonResponse
    {
        try{
            $this->favoriteMovieService->addToFavorites($request->input('movie_id'));
            return response()->json(['message' => 'Filme adicionado aos favoritos com sucesso.'], 201);
        }catch (\Throwable $th){
            return response()->json([
                'message' => 'Erro ao adicionar filme aos favoritos.',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function destroy(FavoriteMovieDeleteRequest $request)
    {
        try{
            $this->favoriteMovieService->removeFromFavorites($request->input('movie_id'));
            return response()->noContent();
        }catch (\Throwable $th){
            return response()->json([
                'message' => 'Erro ao remover filme dos favoritos.',
                'error' => $th->getMessage(),
            ], 500);
        }
    }
}

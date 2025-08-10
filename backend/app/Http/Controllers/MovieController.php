<?php

namespace App\Http\Controllers;

use App\Http\Requests\MovieSearchRequest;
use App\Services\TMDB\MovieService;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    protected readonly MovieService $movieService;

    public function __construct(MovieService $movieService)
    {
        $this->movieService = $movieService;
    }

    public function search(MovieSearchRequest $request)
    {
        try{
            $page = $request->query('page', 1);
            $language = $request->query('language', 'pt-BR');
            $response = $this->movieService->getMovieByName($request->query('query'), $page, $language);
            return response()->json(['data' => $response]);
        }catch (\Throwable $th){
            return response()->json(['error' => 'Erro ao buscar filmes: '.$th->getMessage()], 500);
        }
    }
}

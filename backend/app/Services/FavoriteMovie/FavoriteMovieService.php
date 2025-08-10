<?php

namespace App\Services\FavoriteMovie;

use App\Models\UserFavoriteMovie;
use App\Services\TMDB\MovieService;
use Illuminate\Database\Eloquent\Model;

class FavoriteMovieService
{
    protected readonly Model $model;
    protected readonly MovieService $movieService;

    public function __construct(UserFavoriteMovie $model, MovieService $movieService)
    {
        $this->model = $model;
        $this->movieService = $movieService;
    }

    public function addToFavorites(int $movieId): void
    {
        $user = auth()->user();
        if (!$user) {
            throw new \Exception('Usuário não autenticado.');
        }

        $existingFavorite = $this->model->where('user_id', $user->id)
            ->where('movie_id', $movieId)
            ->first();

        if($existingFavorite) {
            throw new \Exception('Filme já está nos favoritos.');
        }

        $this->model->create([
            'user_id' => $user->id,
            'movie_id' => $movieId,
        ]);
    }

    public function removeFromFavorites(int $movieId): void
    {
        $user = auth()->user();
        if (!$user) {
            throw new \Exception('Usuário não autenticado.');
        }

        $favorite = $this->model->where('user_id', $user->id)
            ->where('movie_id', $movieId)
            ->first();

        if (!$favorite) {
            throw new \Exception('Filme não encontrado nos favoritos.');
        }

        $favorite->delete();
    }

    public function getAllFavorites(?string $genre = null): array
    {
        $user = auth()->user();
        if (!$user) {
            throw new \Exception('Usuário não autenticado.');
        }

        $favorites = $this->model->where('user_id', $user->id)->get();
        $moviesData = [];

        foreach ($favorites as $favorite) {
            $movie = $this->movieService->getMovieById($favorite->movie_id);

            if ($movie) {
                if ($genre) {
                    $hasGenre = false;
                    if (!empty($movie['genres'])) {
                        foreach ($movie['genres'] as $movieGenre) {
                            if (stripos($movieGenre, $genre) !== false) {
                                $hasGenre = true;
                                break;
                            }
                        }
                    }

                    if ($hasGenre) {
                        $moviesData[] = $movie;
                    }
                } else {
                    $moviesData[] = $movie;
                }
            }
        }

        return $moviesData;
    }
}

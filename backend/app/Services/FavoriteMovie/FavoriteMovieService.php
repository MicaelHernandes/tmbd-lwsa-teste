<?php

namespace App\Services\FavoriteMovie;

use App\Models\UserFavoriteMovie;
use Illuminate\Database\Eloquent\Model;

class FavoriteMovieService
{
    protected readonly Model $model;

    public function __construct(UserFavoriteMovie $model)
    {
        $this->model = $model;
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
}

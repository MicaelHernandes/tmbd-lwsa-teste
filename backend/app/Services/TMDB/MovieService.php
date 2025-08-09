<?php

namespace App\Services\TMDB;

class MovieService extends BaseService
{
    public function getMovieByName(string $name, int | string $page = 1, string $language = "pt-BR") : mixed
    {
        $response = $this->sendRequest('/search/movie', [
            'query' => $name,
            'language' => $language,
            'page' => $page,
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }
}

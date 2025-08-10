<?php

namespace App\Services\TMDB;

class MovieService extends BaseService
{
    private string $imageBaseUrl = 'https://image.tmdb.org/t/p/w500';

    private function formatMovieData(array $movie, array $genres): array
    {
        if (!empty($movie['poster_path'])) {
            $movie['poster_path'] = $this->imageBaseUrl . $movie['poster_path'];
        }
        if (!empty($movie['backdrop_path'])) {
            $movie['backdrop_path'] = $this->imageBaseUrl . $movie['backdrop_path'];
        }
        if (!empty($movie['genre_ids'])) {
            $movie['genres'] = [];
            foreach ($movie['genre_ids'] as $id) {
                $movie['genres'][$id] = $genres[$id] ?? $id;
            }
            unset($movie['genre_ids']);
        }
        return $movie;
    }

    private function getGenresMap(string $language = "pt-BR"): array
    {
        $genresData = $this->getAllGenres($language);
        $map = [];
        if (!empty($genresData['genres'])) {
            foreach ($genresData['genres'] as $genre) {
                $map[$genre['id']] = $genre['name'];
            }
        }
        return $map;
    }

    public function getAllMovies(int | string $page = 1, string $language = "pt-BR") : mixed
    {
        $response = $this->sendRequest('/movie/popular', [
            'language' => $language,
            'page' => $page,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $genres = $this->getGenresMap($language);

            if (!empty($data['results'])) {
                $data['results'] = array_map(function ($movie) use ($genres) {
                    return $this->formatMovieData($movie, $genres);
                }, $data['results']);
            }

            return $data;
        }

        return null;
    }

    public function getAllGenres(string $language = "pt-BR"): array
    {
        $response = $this->sendRequest('/genre/movie/list', [
            'language' => $language,
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        return [];
    }

    public function getMovieByName(string $query, int|string $page = 1, string $language = "pt-BR"): mixed
    {
        $response = $this->sendRequest('/search/movie', [
            'query' => $query,
            'language' => $language,
            'page' => $page,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $genres = $this->getGenresMap($language);

            if (!empty($data['results'])) {
                $data['results'] = array_map(function ($movie) use ($genres) {
                    return $this->formatMovieData($movie, $genres);
                }, $data['results']);
            }

            return $data;
        }

        return null;
    }
}

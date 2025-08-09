<?php

namespace App\Services\TMDB;

use Illuminate\Support\Facades\Http;

abstract class BaseService
{
    protected string $baseUrl;
    protected string $token;

    public function __construct()
    {
        $this->baseUrl = env('TMDB_BASE_URL');
        $this->token = env('TMDB_TOKEN');
    }

    /**
     * @param $endpoint
     * @param $data
     * @param $method
     * @return mixed
     */
    protected function sendRequest($endpoint, $data = [], $method = 'GET') : mixed
    {
        $request = Http::withHeaders([
            'Authorization' => 'Bearer '.$this->token,
            'Accept' => 'application/json',
        ])
            ->acceptJson()
            ->withoutVerifying();

        return $request->$method($this->baseUrl . $endpoint, $data);
    }
}

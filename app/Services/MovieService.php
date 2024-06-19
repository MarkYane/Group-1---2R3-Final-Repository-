<?php

namespace App\Services;

use GuzzleHttp\Client;

class MovieService
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getMovie($title)
    {
        $response = $this->client->get('http://www.omdbapi.com/', [
            'query' => [
                't' => $title,
                'apikey' => env('OMDB_API_KEY'),
            ]
        ]);

        return json_decode($response->getBody(), true);
    }
}

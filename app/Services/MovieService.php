<?php

namespace App\Services;

use GuzzleHttp\Client;

class MovieService
{
    protected $client;

    // Constructor to inject the Guzzle HTTP client
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    // Method to get movie details using a title
    public function getMovie($title)
    {
        // Make a GET request to the OMDB API with the movie title and API key
        $response = $this->client->get('http://www.omdbapi.com/', [
            'query' => [
                't' => $title, // Movie title
                'apikey' => env('OMDB_API_KEY'), // API key from environment variables
            ]
        ]);

        // Decode the JSON response and return it as an array
        return json_decode($response->getBody(), true);
    }
}

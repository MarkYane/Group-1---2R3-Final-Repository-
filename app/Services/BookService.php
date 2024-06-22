<?php

namespace App\Services;

use GuzzleHttp\Client;

class BookService
{
    protected $client;

    // Constructor to inject the Guzzle HTTP client
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    // Method to get book details using an ISBN
    public function getBook($isbn)
    {
        // Make a GET request to the Open Library API with the ISBN
        $response = $this->client->get("http://openlibrary.org/search.json?title={$isbn}");

        // Decode the JSON response and return it as an array
        return json_decode($response->getBody(), true);
    }
}

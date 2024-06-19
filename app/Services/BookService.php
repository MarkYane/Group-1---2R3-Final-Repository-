<?php

namespace App\Services;

use GuzzleHttp\Client;

class BookService
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getBook($isbn)
    {
        $response = $this->client->get("http://openlibrary.org/search.json?title={$isbn}");

        return json_decode($response->getBody(), true);
    }
}

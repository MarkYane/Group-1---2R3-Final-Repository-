<?php

namespace App\Services;

use GuzzleHttp\Client;

class MusicService
{
    protected $client;

    public function __construct()
    {
        // Create a Guzzle client instance with SSL certificate verification disabled
        $this->client = new Client([
            'verify' => false,
        ]);
    }

    public function getMusic($artist)
    {
        try {
            // Make a GET request to the iTunes API search endpoint
            $response = $this->client->get('https://itunes.apple.com/search', [
                'query' => [
                    'term' => $artist,
                    'entity' => 'album',
                ],
            ]);

            // Check if the response is successful
            if ($response->getStatusCode() === 200) {
                // Decode the JSON response and return the data
                return json_decode($response->getBody(), true);
            } else {
                return null; // Return null or handle the error accordingly
            }
        } catch (\Exception $e) {
            // Handle any exceptions
            return null; // Return null or handle the error accordingly
        }
    }
}

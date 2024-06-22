<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MovieService;
use App\Services\MusicService;
use App\Services\BookService;

class GatewayController extends Controller
{
    protected $movieService;
    protected $musicService;
    protected $bookService;

    // Constructor to inject the service dependencies
    public function __construct(MovieService $movieService, MusicService $musicService, BookService $bookService)
    {
        $this->middleware('jwt.auth'); // Ensure JWT middleware is applied to all methods in this controller
        $this->movieService = $movieService;
        $this->musicService = $musicService;
        $this->bookService = $bookService;
    }

    // Handles requests to get movies, music, or books
    public function handleRequest(Request $request)
    {
        // Check if JWT token is present and user is authenticated
        if (!$request->user()) {
            return response()->json(['error' => 'JWT token not provided'], 401);
        }

        // Get the action and title from the request
        $action = $request->input('action');
        $title = $request->input('title');

        // Determine which service to call based on the action
        switch ($action) {
            case 'getmovie':
                // Call the MovieService to get movie details
                return $this->movieService->getMovie($title);
            case 'getmusic':
                // Call the MusicService to get music details
                return $this->musicService->getMusic($title);
            case 'getbook':
                // Call the BookService to get book details
                return $this->bookService->getBook($title);
            default:
                // If the action is invalid, return an error response
                return response()->json(['error' => 'Invalid action'], 400);
        }
    }
}

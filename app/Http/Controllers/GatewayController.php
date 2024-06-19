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

    public function __construct(MovieService $movieService, MusicService $musicService, BookService $bookService)
    {
        $this->middleware('jwt.auth'); // Ensure JWT middleware is applied
        $this->movieService = $movieService;
        $this->musicService = $musicService;
        $this->bookService = $bookService;
    }

    public function handleRequest(Request $request)
    {
        // Check if JWT token is present
        if (!$request->user()) {
            return response()->json(['error' => 'JWT token not provided'], 401);
        }

        $action = $request->input('action');
        $title = $request->input('title');
        switch ($action) {
            case 'getmovie':
                return $this->movieService->getMovie($title);
            case 'getmusic':
                return $this->musicService->getMusic($title);
            case 'getbook':
                return $this->bookService->getBook($title);
            default:
                return response()->json(['error' => 'Invalid action'], 400);
        }
    }
}

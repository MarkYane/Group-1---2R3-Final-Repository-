<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class FavoriteController extends Controller
{
    public function addToFavorites(Request $request)
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'title' => 'required|string',
                'action' => 'required|in:getmovie,getmusic,getbook',
                'add_to_favorites' => 'required|in:yes,no',
            ], [
                'add_to_favorites.in' => 'The add_to_favorites field must be either yes or no.',
            ]);

            // Check if validation fails
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->first()], 400);
            }

            // Check if user wants to add to favorites
            if ($request->input('add_to_favorites') === 'no') {
                return response()->json(['message' => 'Not added to favorites.'], 200);
            }

            // Get the authenticated user
            $user = JWTAuth::parseToken()->authenticate();

            // Determine the type based on the action
            $type = '';
            switch ($request->input('action')) {
                case 'getmovie':
                    $type = 'Movie';
                    break;
                case 'getmusic':
                    $type = 'Music';
                    break;
                case 'getbook':
                    $type = 'Book';
                    break;
            }

            // Add to favorites
            $favorite = Favorite::create([
                'username' => $user->username,
                'title' => $request->input('title'),
                'type' => $type
            ]);

            return response()->json(['message' => 'Added to favorites.', 'favorite' => $favorite], 201);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token not provided or invalid'], 401);
        }
    }

    public function removeFavorite(Request $request)
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer|exists:favorites,id',
            ]);

            // Check if validation fails
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->first()], 400);
            }

            // Delete the favorite
            Favorite::destroy($request->input('id'));

            return response()->json(['message' => 'Favorite removed successfully'], 200);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token not provided or invalid'], 401);
        }
    }

    public function getAllFavorites(Request $request)
    {
        try {
            // Get the authenticated user
            $user = JWTAuth::parseToken()->authenticate();

            // Get all favorites for the authenticated user
            $favorites = Favorite::where('username', $user->username)->get();

            return response()->json(['favorites' => $favorites], 200);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token not provided or invalid'], 401);
        }
    }

    
}

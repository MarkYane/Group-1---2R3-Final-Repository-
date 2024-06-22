<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class FavoriteController extends Controller
{
    // Handles adding an item to the user's favorites
    public function addToFavorites(Request $request)
    {
        try {
            // Validate the request input
            $validator = Validator::make($request->all(), [
                'title' => 'required|string',
                'action' => 'required|in:getmovie,getmusic,getbook',
                'add_to_favorites' => 'required|in:yes,no',
            ], [
                'add_to_favorites.in' => 'The add_to_favorites field must be either yes or no.',
            ]);

            // If validation fails, return the first validation error
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->first()], 400);
            }

            // Check if the user doesn't want to add to favorites
            if ($request->input('add_to_favorites') === 'no') {
                return response()->json(['message' => 'Not added to favorites.'], 200);
            }

            // Authenticate and get the user from the token
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

            // Create a new favorite record
            $favorite = Favorite::create([
                'username' => $user->username,
                'title' => $request->input('title'),
                'type' => $type
            ]);

            // Return success message and the favorite record
            return response()->json(['message' => 'Added to favorites.', 'favorite' => $favorite], 201);
        } catch (JWTException $e) {
            // Handle JWT exceptions (e.g., token not provided or invalid)
            return response()->json(['error' => 'Token not provided or invalid'], 401);
        }
    }

    // Handles removing an item from the user's favorites
    public function removeFavorite(Request $request)
    {
        try {
            // Validate the request input
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer|exists:favorites,id',
            ]);

            // If validation fails, return the first validation error
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->first()], 400);
            }

            // Delete the favorite record by ID
            Favorite::destroy($request->input('id'));

            // Return success message
            return response()->json(['message' => 'Favorite removed successfully'], 200);
        } catch (JWTException $e) {
            // Handle JWT exceptions (e.g., token not provided or invalid)
            return response()->json(['error' => 'Token not provided or invalid'], 401);
        }
    }

    // Handles retrieving all favorite items for the authenticated user
    public function getAllFavorites(Request $request)
    {
        try {
            // Authenticate and get the user from the token
            $user = JWTAuth::parseToken()->authenticate();

            // Retrieve all favorite records for the authenticated user
            $favorites = Favorite::where('username', $user->username)->get();

            // Return the list of favorites
            return response()->json(['favorites' => $favorites], 200);
        } catch (JWTException $e) {
            // Handle JWT exceptions (e.g., token not provided or invalid)
            return response()->json(['error' => 'Token not provided or invalid'], 401);
        }
    }
}

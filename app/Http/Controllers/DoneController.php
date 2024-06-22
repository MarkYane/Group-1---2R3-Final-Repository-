<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Done;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;

class DoneController extends Controller
{
    // Handles marking an action as done or not done
    public function markAsDone(Request $request)
    {
        try {
            // Authenticate and get the user from the token
            $user = JWTAuth::parseToken()->authenticate();

            // Validate the request input
            $validator = Validator::make($request->all(), [
                'action' => 'required|in:getmovie,getmusic,getbook',
                'title' => 'required|string',
                'done' => 'required|in:yes,no',
            ]);

            // If validation fails, return the first validation error
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->first()], 400);
            }

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

            // Check if the user says "yes" or "no"
            $isDone = $request->input('done') === 'yes';

            // Create or update the record in the 'done' table
            $done = Done::updateOrCreate(
                [
                    'username' => $user->username, // Matching criteria
                    'title' => $request->input('title'),
                    'type' => $type,
                ],
                [
                    'done' => $isDone, // Values to update
                ]
            );

            // Return a success message and the updated 'done' record
            return response()->json(['message' => 'Status updated successfully.', 'done' => $done], 200);
        } catch (JWTException $e) {
            // Handle JWT exceptions (e.g., token not provided or invalid)
            return response()->json(['error' => 'Token not provided or invalid'], 401);
        }
    }

    // Handles retrieving all done titles for the authenticated user
    public function getDoneTitles(Request $request)
    {
        try {
            // Authenticate and get the user from the token
            $user = JWTAuth::parseToken()->authenticate();

            // Retrieve all records from the 'done' table for the authenticated user
            $dones = Done::where('username', $user->username)->get();

            // Return the list of done titles
            return response()->json(['dones' => $dones], 200);
        } catch (JWTException $e) {
            // Handle JWT exceptions (e.g., token not provided or invalid)
            return response()->json(['error' => 'Token not provided or invalid'], 401);
        }
    }
}

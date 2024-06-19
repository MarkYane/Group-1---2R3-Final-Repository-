<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Done;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;

class DoneController extends Controller
{
    public function markAsDone(Request $request)
    {
        try {
            // Get the authenticated user
            $user = JWTAuth::parseToken()->authenticate();

            // Validate the request
            $validator = Validator::make($request->all(), [
                'action' => 'required|in:getmovie,getmusic,getbook',
                'title' => 'required|string',
                'done' => 'required|in:yes,no',
            ]);

            // Check if validation fails
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

            // Check if the user says yes or no
            $isDone = $request->input('done') === 'yes';

            // Create or update the record
            $done = Done::updateOrCreate(
                [
                    'username' => $user->username,
                    'title' => $request->input('title'),
                    'type' => $type,
                ],
                [
                    'done' => $isDone,
                ]
            );

            return response()->json(['message' => 'Status updated successfully.', 'done' => $done], 200);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token not provided or invalid'], 401);
        }
    }

    public function getDoneTitles(Request $request)
    {
        try {
            // Get the authenticated user
            $user = JWTAuth::parseToken()->authenticate();

            // Get all done titles for the authenticated user
            $dones = Done::where('username', $user->username)->get();

            return response()->json(['dones' => $dones], 200);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token not provided or invalid'], 401);
        }
    }
}

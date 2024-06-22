<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Handles user login
    public function login(Request $request)
    {
        // Validate the request input
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        // If validation fails, return validation errors
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Extract the username and password from the request
        $credentials = $request->only('username', 'password');

        // Check if the user exists in the database
        $user = User::where('username', $credentials['username'])->first();

        // If the user doesn't exist, create a new user
        if (!$user) {
            $user = User::create([
                'username' => $credentials['username'],
                'password' => Hash::make($credentials['password']),
            ]);
        } else {
            // If user exists, verify the provided password
            if (!Hash::check($credentials['password'], $user->password)) {
                // If password doesn't match, return unauthorized error
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        }

        // Attempt to create a JWT token for the user
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                // If JWT creation fails, return unauthorized error
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            // If there's an error while creating the token, return an error message
            return response()->json(['error' => 'Could not create token'], 500);
        }

        // Return the generated JWT token and its details
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60 // Token expiration time in seconds
        ]);
    }

    // Handles forgot password functionality
    public function forgotPassword(Request $request)
    {
        // Validate the request input
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        // If validation fails, return validation errors
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Find the user by username
        $user = User::where('username', $request->input('username'))->first();

        // If the user is not found, return an error
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Verify the provided password matches the stored password
        if (!Hash::check($request->input('password'), $user->password)) {
            // If password doesn't match, return invalid credentials error
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        // Generate a JWT token for the user
        $token = JWTAuth::fromUser($user);

        // Return user details along with the generated token
        return response()->json([
            'user' => [
                'username' => $user->username,
                'password' => $user->password,
                'token' => $token,
            ]
        ]);
    }
}

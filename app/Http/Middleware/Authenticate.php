<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class Authenticate extends BaseMiddleware
{
    public function handle($request, Closure $next)
    {
        try {
            // Attempt to authenticate the user based on the JWT token in the request
            $user = JWTAuth::parseToken()->authenticate();
        } catch (TokenExpiredException $e) {
            // Token expired exception handler
            return response()->json(['error' => 'Token is Expired'], 401);
        } catch (TokenInvalidException $e) {
            // Token invalid exception handler
            return response()->json(['error' => 'Token is Invalid'], 401);
        } catch (Exception $e) {
            // Default exception handler for any other exceptions related to JWT
            return response()->json(['error' => 'Authorization Token not found'], 401);
        }

        // If authentication succeeds, proceed with the request
        return $next($request);
    }
}


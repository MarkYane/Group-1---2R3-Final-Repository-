<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Tymon\JWTAuth\Exceptions\UnauthorizedHttpException;

class JwtMiddleware extends BaseMiddleware
{
    // Handle an incoming request
    public function handle($request, Closure $next)
    {
        try {
            // Attempt to authenticate the user via JWT
            $this->authenticate($request);
        } catch (UnauthorizedHttpException $e) {
            // If authentication fails, return a 401 Unauthorized response with the error message
            return response()->json(['error' => $e->getMessage()], 401);
        }

        // If authentication succeeds, pass the request to the next middleware or controller
        return $next($request);
    }
}

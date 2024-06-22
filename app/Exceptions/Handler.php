<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;
use App\Exceptions\JWTTokenMissingException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Handle an unauthenticated user exception.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        // Check if the request expects JSON response or is an API request
        if ($request->expectsJson() || $request->is('api/*')) {
            // Handle JWTTokenMissingException separately
            if ($exception instanceof JWTTokenMissingException) {
                return response()->json(['error' => 'JWT token is missing.'], 401);
            }
            // For other unauthenticated scenarios, return a generic JSON response
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        // Delegate to the parent class handler for non-JSON/non-API requests
        return parent::unauthenticated($request, $exception);
    }

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        // Delegate exception reporting/logging to the parent class
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        // Render the exception into an HTTP response using the parent class
        return parent::render($request, $exception);
    }
}

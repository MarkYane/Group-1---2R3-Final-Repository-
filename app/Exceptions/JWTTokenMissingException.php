<?php

namespace App\Exceptions;

use Exception;

class JWTTokenMissingException extends Exception
{
    // Custom error message for the exception
    protected $message = 'JWT token is missing.';
}

<?php

namespace App\Exceptions;

use Exception;

class JWTTokenMissingException extends Exception
{
    protected $message = 'JWT token is missing.';
}

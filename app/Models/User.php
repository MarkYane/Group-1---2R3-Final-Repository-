<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Laravel\Lumen\Auth\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract, JWTSubject
{
    use Authenticatable, Authorizable, CanResetPassword;

    // Fillable attributes for mass assignment
    protected $fillable = [
        'username', 'password',
    ];

    // Hidden attributes (not shown in JSON responses)
    protected $hidden = [
        'password',
    ];

    // Method required by JWTSubject interface to get the identifier used in the JWT
    public function getJWTIdentifier()
    {
        return $this->getKey(); // Return the primary key of the user
    }

    // Method required by JWTSubject interface to add custom claims to the JWT payload
    public function getJWTCustomClaims()
    {
        return []; // No custom claims by default
    }
}

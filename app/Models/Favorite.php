<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    // Define the fillable attributes for mass assignment
    protected $fillable = ['username', 'title', 'type'];
}

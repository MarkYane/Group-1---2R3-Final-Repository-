<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Done extends Model
{
    protected $fillable = [
        'username', 'title', 'type', 'done'
    ];
}

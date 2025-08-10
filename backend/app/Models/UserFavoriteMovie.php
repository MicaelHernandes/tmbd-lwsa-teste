<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserFavoriteMovie extends Model
{
    protected $fillable = [
        'user_id',
        'movie_id',
    ];

    public $timestamps = true;
}

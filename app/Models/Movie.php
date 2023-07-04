<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'title',
        'genres'
    ];

    protected $casts = [
        'genres' => 'array',
    ];

    public function userRatings()
    {
        return $this->hasMany(UserRating::class, 'movie_id', 'id');
    }

    public function image()
    {
        return $this->hasOne(MovieImage::class, 'movie_id', 'id');
    }
}

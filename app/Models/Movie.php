<?php

namespace WatchTime\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Movie extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = 'movies';
    protected $fillable = [
        'name',
        'overview',
        'tag_line',

        'imdb',
        'tmdb',

        'budget',
        'revenue',

        'runtime',
        'status',
        'release_date',

        'popularity',
        'vote_count',
        'vote_average',

        'homepage',
        'poster_path',
        'backdrop_path',

        'details_loaded',
    ];

    protected $hidden = ['created_at', 'updated_at', 'activated', 'details_loaded'];

    public function genres() {
        return $this->hasMany(MovieGenre::class, 'movie_id', 'id');
    }

    public function videos() {
        return $this->hasMany(MovieVideo::class, 'movie_id', 'id');
    }

    public function cast() {
        return $this->hasMany(MovieCast::class, 'movie_id', 'id');
    }

    public function crew() {
        return $this->hasMany(MovieCrew::class, 'movie_id', 'id');
    }

}

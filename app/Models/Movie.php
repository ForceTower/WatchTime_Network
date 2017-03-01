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
        'tmdb',
        'overview',
        'release_date',
        'poster_path',
        'backdrop_path',
        'popularity',
        'vote_count',
        'vote_average',
        'tag_line',
        'runtime',
        'budged',
        'revenue',
        'homepage'
    ];

    protected $hidden = ['created_at', 'updated_at', 'activated', 'details_loaded'];

    public function genres() {
        return $this->hasMany(MovieGenre::class, 'movie_id', 'id');
    }

}

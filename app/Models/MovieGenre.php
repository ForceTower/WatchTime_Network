<?php

namespace WatchTime\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class MovieGenre extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = [];

    public function genre() {
        return $this->belongsTo(Genre::class, 'genre_id', 'id');
    }

    public function movie() {
        return $this->belongsTo(Movie::class, 'movie_id', 'id');
    }
}

<?php

namespace WatchTime\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class UserMovieWatchList extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = ['user_id', 'movie_id'];

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function movie() {
        return $this->belongsTo(Movie::class, 'movie_id', 'id');
    }

}

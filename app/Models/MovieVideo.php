<?php

namespace WatchTime\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class MovieVideo extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = ['movie_id', 'key', 'type', 'name'];

    public function movie() {
        return $this->belongsTo(Movie::class, 'movie_id', 'id');
    }

}

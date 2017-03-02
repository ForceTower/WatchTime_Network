<?php

namespace WatchTime\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class MovieCast extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = ['movie_id', 'person_id', 'character'];

    public function movie() {
        return $this->belongsTo(Movie::class, 'movie_id', 'id');
    }

    public function person() {
        return $this->belongsTo(Person::class, 'person_id', 'id');
    }

}

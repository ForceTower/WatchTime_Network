<?php

namespace WatchTime\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Genre extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = [];
    protected $hidden = ["created_at", "updated_at"];

    public function movies() {
        return $this->hasMany(MovieGenre::class, 'genre_id', 'id');
    }
}

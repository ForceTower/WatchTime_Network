<?php

namespace WatchTime\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Person extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = ['tmdb', 'name', 'profile_path'];

    public function movies() {
        return $this->hasMany(MovieCast::class, 'person_id', 'id');
    }

    public function jobs() {
        return $this->hasMany(MovieCrew::class, 'person_id', 'id');
    }
}

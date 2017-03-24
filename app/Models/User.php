<?php

namespace WatchTime\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements Transformable, AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use TransformableTrait, Authenticatable, Authorizable, CanResetPassword;

    protected $table = 'users';
    protected $fillable = ['name', 'email', 'password', 'facebook_id', 'google_id'];
    protected $hidden = ['password', 'remember_token'];

    public function cover() {
        return $this->hasOne(MovieImage::class, 'id', 'cover_picture');
    }

    public function moviesWatched() {
        return $this->hasMany(UserMoviesWatched::class, 'user_id', 'id');
    }

    public function moviesList() {
        return $this->hasMany(UserMovieWatchList::class, 'user_id', 'id');
    }


}
<?php

namespace WatchTime\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function boot()
    {
        //
    }

    public function register() {
        $this->app->bind(
            'WatchTime\Repositories\GenreRepository',
            'WatchTime\Repositories\GenreRepositoryEloquent'
        );

        $this->app->bind(
            'WatchTime\Repositories\MovieRepository',
            'WatchTime\Repositories\MovieRepositoryEloquent'
        );

        $this->app->bind(
            'WatchTime\Repositories\MovieGenreRepository',
            'WatchTime\Repositories\MovieGenreRepositoryEloquent'
        );

        $this->app->bind(
            'WatchTime\Repositories\UserRepository',
            'WatchTime\Repositories\UserRepositoryEloquent'
        );
    }
}
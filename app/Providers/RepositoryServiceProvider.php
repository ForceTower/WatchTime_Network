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

        $this->app->bind(
            'WatchTime\Repositories\MovieVideoRepository',
            'WatchTime\Repositories\MovieVideoRepositoryEloquent'
        );

        $this->app->bind(
            'WatchTime\Repositories\MovieCastRepository',
            'WatchTime\Repositories\MovieCastRepositoryEloquent'
        );

        $this->app->bind(
            'WatchTime\Repositories\PersonRepository',
            'WatchTime\Repositories\PersonRepositoryEloquent'
        );

        $this->app->bind(
            'WatchTime\Repositories\MovieCrewRepository',
            'WatchTime\Repositories\MovieCrewRepositoryEloquent'
        );

        $this->app->bind(
            'WatchTime\Repositories\MovieImageRepository',
            'WatchTime\Repositories\MovieImageRepositoryEloquent'
        );

        $this->app->bind(
            'WatchTime\Repositories\UserMoviesWatchedRepository',
            'WatchTime\Repositories\UserMoviesWatchedRepositoryEloquent'
        );
    }
}

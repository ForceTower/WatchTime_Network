<?php

namespace WatchTime\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use WatchTime\Repositories\UserMoviesWatchedRepository;
use WatchTime\Models\UserMoviesWatched;
use WatchTime\Validators\UserMoviesWatchedValidator;

/**
 * Class UserMoviesWatchedRepositoryEloquent
 * @package namespace WatchTime\Repositories;
 */
class UserMoviesWatchedRepositoryEloquent extends BaseRepository implements UserMoviesWatchedRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return UserMoviesWatched::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}

<?php

namespace WatchTime\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use WatchTime\Repositories\UserMovieWatchListRepository;
use WatchTime\Models\UserMovieWatchList;
use WatchTime\Validators\UserMovieWatchListValidator;

/**
 * Class UserMovieWatchListRepositoryEloquent
 * @package namespace WatchTime\Repositories;
 */
class UserMovieWatchListRepositoryEloquent extends BaseRepository implements UserMovieWatchListRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return UserMovieWatchList::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}

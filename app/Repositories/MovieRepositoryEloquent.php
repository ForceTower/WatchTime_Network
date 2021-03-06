<?php

namespace WatchTime\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use WatchTime\Repositories\MovieRepository;
use WatchTime\Models\Movie;
use WatchTime\Presenters\MoviePresenter;

/**
 * Class MovieRepositoryEloquent
 * @package namespace WatchTime\Repositories;
 */
class MovieRepositoryEloquent extends BaseRepository implements MovieRepository
{
    protected $skipPresenter = true;
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Movie::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function presenter()
    {
        return MoviePresenter::class;
    }
}

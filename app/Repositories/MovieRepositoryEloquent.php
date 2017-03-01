<?php

namespace WatchTime\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use WatchTime\Repositories\MovieRepository;
use WatchTime\Models\Movie;
use WatchTime\Validators\MovieValidator;

/**
 * Class MovieRepositoryEloquent
 * @package namespace WatchTime\Repositories;
 */
class MovieRepositoryEloquent extends BaseRepository implements MovieRepository
{
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
}

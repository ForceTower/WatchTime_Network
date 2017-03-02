<?php

namespace WatchTime\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use WatchTime\Repositories\MovieVideoRepository;
use WatchTime\Models\MovieVideo;
use WatchTime\Validators\MovieVideoValidator;

/**
 * Class MovieVideoRepositoryEloquent
 * @package namespace WatchTime\Repositories;
 */
class MovieVideoRepositoryEloquent extends BaseRepository implements MovieVideoRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return MovieVideo::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}

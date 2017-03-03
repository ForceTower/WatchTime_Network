<?php

namespace WatchTime\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use WatchTime\Repositories\MovieImageRepository;
use WatchTime\Models\MovieImage;
use WatchTime\Validators\MovieImageValidator;

/**
 * Class MovieImageRepositoryEloquent
 * @package namespace WatchTime\Repositories;
 */
class MovieImageRepositoryEloquent extends BaseRepository implements MovieImageRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return MovieImage::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}

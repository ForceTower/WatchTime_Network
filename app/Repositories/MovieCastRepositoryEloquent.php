<?php

namespace WatchTime\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use WatchTime\Repositories\MovieCastRepository;
use WatchTime\Models\MovieCast;
use WatchTime\Validators\MovieCastValidator;

/**
 * Class MovieCastRepositoryEloquent
 * @package namespace WatchTime\Repositories;
 */
class MovieCastRepositoryEloquent extends BaseRepository implements MovieCastRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return MovieCast::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}

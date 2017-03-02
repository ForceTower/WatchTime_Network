<?php

namespace WatchTime\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use WatchTime\Repositories\MovieCrewRepository;
use WatchTime\Models\MovieCrew;
use WatchTime\Validators\MovieCrewValidator;

/**
 * Class MovieCrewRepositoryEloquent
 * @package namespace WatchTime\Repositories;
 */
class MovieCrewRepositoryEloquent extends BaseRepository implements MovieCrewRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return MovieCrew::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}

<?php

namespace WatchTime\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use WatchTime\Repositories\MovieGenreRepository;
use WatchTime\Models\MovieGenre;
use WatchTime\Validators\MovieGenreValidator;

/**
 * Class MovieGenreRepositoryEloquent
 * @package namespace WatchTime\Repositories;
 */
class MovieGenreRepositoryEloquent extends BaseRepository implements MovieGenreRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return MovieGenre::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}

<?php

namespace WatchTime\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use WatchTime\Repositories\PersonRepository;
use WatchTime\Models\Person;
use WatchTime\Validators\PersonValidator;

/**
 * Class PersonRepositoryEloquent
 * @package namespace WatchTime\Repositories;
 */
class PersonRepositoryEloquent extends BaseRepository implements PersonRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Person::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}

<?php

namespace WatchTime\Transformers;

use League\Fractal\TransformerAbstract;
use WatchTime\Models\UserMoviesWatched;

/**
 * Class UserMoviesWatchedTransformer
 * @package namespace WatchTime\Transformers;
 */
class UserMoviesWatchedTransformer extends TransformerAbstract
{

    /**
     * Transform the \UserMoviesWatched entity
     * @param \UserMoviesWatched $model
     *
     * @return array
     */
    public function transform(UserMoviesWatched $model)
    {
        return [
            'id'         => (int) $model->id,
            'movie_id'   => (int) $model->movie_id,
            'movie_name' => $model->movie->name,
            'user_name'  => $model->user->name,
        ];
    }
}

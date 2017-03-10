<?php

namespace WatchTime\Transformers;

use League\Fractal\TransformerAbstract;
use WatchTime\Models\UserMovieWatchList;

/**
 * Class UserMovieWatchListTransformer
 * @package namespace WatchTime\Transformers;
 */
class UserMovieWatchListTransformer extends TransformerAbstract
{

    /**
     * Transform the \UserMovieWatchList entity
     * @param \UserMovieWatchList $model
     *
     * @return array
     */
    public function transform(UserMovieWatchList $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}

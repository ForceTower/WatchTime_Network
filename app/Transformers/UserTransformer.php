<?php

namespace WatchTime\Transformers;

use League\Fractal\TransformerAbstract;
use WatchTime\Models\User;
use WatchTime\Models\UserMoviesWatched;

/**
 * Class UserTransformer
 * @package namespace WatchTime\Transformers;
 */
class UserTransformer extends TransformerAbstract
{

    protected $defaultIncludes = ['movies'];
    /**
     * Transform the \User entity
     * @param \User $model
     *
     * @return array
     */
    public function transform(User $model)
    {
        if (!$model->cover) {
            return [
                'id' => (int)$model->id,
                'name' => $model->name,
                'email' => $model->email,
                'time_watched' => $model->time_watched,
                'cover' => null,
            ];
        } else {
            return [
                'id' => (int)$model->id,
                'name' => $model->name,
                'email' => $model->email,
                'time_watched' => $model->time_watched,
                'cover' => $model->cover->image_path,
            ];
        }
    }

    public function includeMovies(User $model) {
        return $this->collection($model->moviesWatched, new UserMoviesWatchedTransformer());
    }

}

<?php

namespace WatchTime\Transformers;

use League\Fractal\TransformerAbstract;
use WatchTime\Models\User;

/**
 * Class UserTransformer
 * @package namespace WatchTime\Transformers;
 */
class UserTransformer extends TransformerAbstract
{

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

}

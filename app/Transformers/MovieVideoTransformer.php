<?php

namespace WatchTime\Transformers;

use League\Fractal\TransformerAbstract;
use WatchTime\Models\MovieVideo;

/**
 * Class MovieVideoTransformer
 * @package namespace WatchTime\Transformers;
 */
class MovieVideoTransformer extends TransformerAbstract
{

    /**
     * Transform the \MovieVideo entity
     * @param \MovieVideo $model
     *
     * @return array
     */
    public function transform(MovieVideo $model)
    {
        return [
            'name' => $model->name,
            'key' => $model->key,
            'type' => $model->type,
        ];
    }
}

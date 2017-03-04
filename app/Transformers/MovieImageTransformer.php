<?php

namespace WatchTime\Transformers;

use League\Fractal\TransformerAbstract;
use WatchTime\Models\MovieImage;

/**
 * Class MovieImageTransformer
 * @package namespace WatchTime\Transformers;
 */
class MovieImageTransformer extends TransformerAbstract
{

    public function transform(MovieImage $model)
    {
        return [
            'id' => $model->id,
            'movie' => $model->movie->name,
            'image_path' => $model->image_path,
        ];
    }
}

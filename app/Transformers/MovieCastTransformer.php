<?php

namespace WatchTime\Transformers;

use League\Fractal\TransformerAbstract;
use WatchTime\Models\MovieCast;

/**
 * Class MovieCastTransformer
 * @package namespace WatchTime\Transformers;
 */
class MovieCastTransformer extends TransformerAbstract
{

    /**
     * Transform the \MovieCast entity
     * @param \MovieCast $model
     *
     * @return array
     */
    public function transform(MovieCast $model)
    {
        return [
            'tmdb' => $model->person->tmdb,
            'movie' => $model->movie->name,
            'person' => $model->person->name,
            'profile_path' => $model->person->profile_path,
            'character' => $model->character,
        ];
    }
}

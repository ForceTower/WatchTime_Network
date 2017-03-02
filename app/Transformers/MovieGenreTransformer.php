<?php

namespace WatchTime\Transformers;

use League\Fractal\TransformerAbstract;
use WatchTime\Models\MovieGenre;

/**
 * Class MovieGenreTransformer
 * @package namespace WatchTime\Transformers;
 */
class MovieGenreTransformer extends TransformerAbstract
{

    /**
     * Transform the \MovieGenre entity
     * @param \MovieGenre $model
     *
     * @return array
     */
    public function transform(MovieGenre $model)
    {
        return [
            'movie' => $model->movie->name,
            'genre' => $model->genre->name,
            'genre_id' => $model->genre->id,
        ];
    }
}

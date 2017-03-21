<?php

namespace WatchTime\Transformers;

use League\Fractal\TransformerAbstract;
use WatchTime\Models\UserMovieWatchList;

/**
 * Class UserMovieWatchListTransformer
 * @package namespace WatchTime\Transformers;
 */
class UserMovieWatchlistTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ['genres'];

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
            'tmdb'       => (int) $model->movie->tmdb,
            'name'       => $model->movie->name,
            'runtime'    => $model->movie->runtime,
            'rating'     => $model->movie->vote_average,
            'tag_line'   => $model->movie->tag_line,
            'image'      => $model->movie->poster_path,
            'date_added' => $model->created_at->format('d/m/Y'),
        ];
    }

    public function includeGenres(UserMovieWatchList $model) {
        return $this->collection($model->movie->genres, new MovieGenreTransformer());
    }
}

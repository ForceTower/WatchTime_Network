<?php

namespace WatchTime\Transformers;

use League\Fractal\TransformerAbstract;
use WatchTime\Models\Movie;

/**
 * Class MovieTransformer
 * @package namespace WatchTime\Transformers;
 */
class MovieTransformer extends TransformerAbstract
{

    protected $defaultIncludes = ['genres', 'videos', 'cast', 'crew'];

    /**
     * Transform the \Movie entity
     * @param \Movie $model
     *
     * @return array
     */
    public function transform(Movie $model)
    {
        return [
            'id' => (int) $model->id,
            'name' => $model->name,
            'overview' => $model->overview,
            'tag_line' => $model->tag_line,

            'imdb' => $model->imdb,
            'tmdb' => $model->tmdb,

            'budget' => $model->budget,
            'revenue' => $model->revenue,

            'runtime' => $model->runtime,
            'status' => $model->status,
            'release_date' => $model->release_date,

            'popularity' => $model->popularity,
            'vote_count' => $model->vote_count,
            'vote_average' => $model->vote_average,

            'homepage' => $model->homepage,
            'poster_path' => $model->poster_path,
            'backdrop_path' => $model->backdrop_path,
        ];
    }

    public function includeGenres(Movie $model) {
        return $this->collection($model->genres, new MovieGenreTransformer());
    }

    public function includeVideos(Movie $model) {
        return $this->collection($model->videos, new MovieVideoTransformer());
    }

    public function includeCast(Movie $model) {
        return $this->collection($model->cast, new MovieCastTransformer());
    }

    public function includeCrew(Movie $model) {
        return $this->collection($model->crew, new MovieCrewTransformer());
    }
}

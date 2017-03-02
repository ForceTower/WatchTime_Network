<?php

namespace WatchTime\Transformers;

use League\Fractal\TransformerAbstract;
use WatchTime\Models\MovieCrew;

/**
 * Class MovieCrewTransformer
 * @package namespace WatchTime\Transformers;
 */
class MovieCrewTransformer extends TransformerAbstract
{

    /**
     * Transform the \MovieCrew entity
     * @param \MovieCrew $model
     *
     * @return array
     */
    public function transform(MovieCrew $model)
    {
        return [
            'tmdb' => $model->person->tmdb,
            'movie' => $model->movie->name,
            'person' => $model->person->name,
            'profile_path' => $model->person->profile_path,
            'job' => $model->job,
        ];
    }
}

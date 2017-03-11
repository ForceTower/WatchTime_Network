<?php

namespace WatchTime\Presenters;

use WatchTime\Transformers\UserMovieWatchlistTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class UserMovieWatchlistPresenter
 *
 * @package namespace WatchTime\Presenters;
 */
class UserMovieWatchlistPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new UserMovieWatchlistTransformer();
    }
}

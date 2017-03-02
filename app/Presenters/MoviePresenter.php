<?php

namespace WatchTime\Presenters;

use WatchTime\Transformers\MovieTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class MoviePresenter
 *
 * @package namespace WatchTime\Presenters;
 */
class MoviePresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new MovieTransformer();
    }
}

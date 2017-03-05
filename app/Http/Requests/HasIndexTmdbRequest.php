<?php
/**
 * Created by PhpStorm.
 * User: joaop
 * Date: 05/03/2017
 * Time: 13:03
 */

namespace WatchTime\Http\Requests;


class HasIndexTmdbRequest extends Request {
    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'tmdb' => 'required',
        ];
    }

}
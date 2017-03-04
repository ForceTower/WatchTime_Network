<?php
/**
 * Created by PhpStorm.
 * User: joaop
 * Date: 04/03/2017
 * Time: 18:46
 */

namespace WatchTime\Http\Requests;

class CoverUpdateRequest extends Request {

    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'id' => 'required|exists:movie_images',
        ];
    }

}
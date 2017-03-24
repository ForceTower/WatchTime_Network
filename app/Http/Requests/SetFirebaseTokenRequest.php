<?php
/**
 * Created by PhpStorm.
 * User: joaop
 * Date: 23/03/2017
 * Time: 21:37
 */

namespace WatchTime\Http\Requests;


class SetFirebaseTokenRequest extends Request {
    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'token' => 'required'
        ];
    }

}
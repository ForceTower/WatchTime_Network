<?php
/**
 * Created by PhpStorm.
 * User: joaop
 * Date: 21/03/2017
 * Time: 15:22
 */

namespace WatchTime\Http\Requests;


class CreateAccountRequest extends Request {

    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'avatar' => 'mimes:png,jpg,jpeg'
        ];
    }

}
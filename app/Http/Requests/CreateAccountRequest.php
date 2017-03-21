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
            'name' => 'required|max:255',
            'email' => 'required|unique:users|max:255',
            'password' => 'required|min:4|max:16',
            'avatar' => 'mimes:png,jpg,jpeg'
        ];
    }

}
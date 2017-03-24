<?php
/**
 * Created by PhpStorm.
 * User: joaop
 * Date: 24/03/2017
 * Time: 14:37
 */

namespace WatchTime\Http\Requests;


class GoogleAPILoginRequest extends Request{
    public function authorize() {
        return true;
    }

    public function rules()
    {
        return [
            'google_id' => 'required',
            'google_token' => 'required',
        ];
    }
}
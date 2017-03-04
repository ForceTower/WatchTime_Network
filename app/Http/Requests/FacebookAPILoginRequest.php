<?php

namespace WatchTime\Http\Requests;

use WatchTime\Http\Requests\Request;

class FacebookAPILoginRequest extends Request {
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'facebook_id' => 'required',
            'facebook_token' => 'required',
        ];
    }
}

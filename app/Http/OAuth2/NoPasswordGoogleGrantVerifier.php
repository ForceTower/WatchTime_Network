<?php
/**
 * Created by PhpStorm.
 * User: joaop
 * Date: 04/03/2017
 * Time: 01:03
 */

namespace WatchTime\Http\OAuth2;


use Facebook\Exceptions\FacebookSDKException;
use Illuminate\Support\Facades\Auth;
use PulkitJalan\Google\Facades\Google;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;
use WatchTime\Repositories\UserRepository;

class NoPasswordGoogleGrantVerifier{
    private $usersRep;

    public function __construct(UserRepository $userRepository) {
        $this->usersRep = $userRepository;
    }

    public function verify($email, $google_id, $google_token) {
        $client = Google::getClient();
        $payload = $client->verifyIdToken($google_token);

        if (!$payload)
            return false;

        $uid = $payload['sub'];
        if ($uid !== $google_id)
            return false;

        $user = $this->usersRep->findWhere(['google_id' => $uid])->first();
        if ($user && $user['email'] === $email) {
            Auth::login($user);
            return Auth::user()->id;
        }

        return false;
    }
}
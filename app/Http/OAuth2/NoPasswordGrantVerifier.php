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
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;
use WatchTime\Repositories\UserRepository;

class NoPasswordGrantVerifier
{
    private $facebook;
    private $usersRep;

    public function __construct(LaravelFacebookSdk $facebookSdk, UserRepository $userRepository){
        $this->facebook = $facebookSdk;
        $this->usersRep = $userRepository;
    }

    public function verify($email, $fb_id, $fb_token) {
        try {
            $response = $this->facebook->get('/me?fields=id,email', $fb_token);
        } catch(FacebookSDKException $e) {
            return false;
        }

        $userGraph = $response->getGraphUser();

        if ($userGraph->getId() !== $fb_id)
            return false;

        $this->facebook->setDefaultAccessToken($fb_token);
        $user = $this->usersRep->findWhere(['facebook_id' => $userGraph->getId()])->first();

        if ($user && $user['email'] === $email) {
            Auth::login($user);
            return Auth::user()->id;
        }


        return false;
    }
}
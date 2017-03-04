<?php
/**
 * Created by PhpStorm.
 * User: joaop
 * Date: 03/03/2017
 * Time: 18:15
 */

namespace WatchTime\Http\Controllers\API;


use Facebook\Exceptions\FacebookSDKException;
use Faker\Provider\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;
use WatchTime\Http\Controllers\Controller;
use WatchTime\Http\Requests\CoverUpdateRequest;
use WatchTime\Http\Requests\FacebookAPILoginRequest;
use WatchTime\Repositories\UserRepository;

class AccountController extends Controller {
    private $userRepository;
    private $facebookSdk;

    public function __construct(UserRepository $userRepository, LaravelFacebookSdk $facebookSdk) {
        $this->facebookSdk = $facebookSdk;
        $this->userRepository = $userRepository;
    }

    public function myProfile() {
        $id = Authorizer::getResourceOwnerId();
        $user = $this->userRepository->skipPresenter(false)->find($id);
        return $user;
    }

    public function coverUpdate(CoverUpdateRequest $request) {
        $id = Authorizer::getResourceOwnerId();
        $user = $this->userRepository->find($id);

        $data = $request->all();
        $cover_id = $data['id'];

        $user->cover_picture = $cover_id;
        $user->save();

        return [
            'success' => 'Cover Changed'
        ];
    }

    public function userImage($id) {
        $user = $this->userRepository->find($id);

        $user_image = $user['avatar'];
        if (!$user_image)
            return redirect('https://pbs.twimg.com/profile_images/716487122224439296/HWPluyjs.jpg');

        header('Content-Type: image/jpeg');
        echo $user_image;
    }

    public function userProfile($id) {
        $user = $this->userRepository->skipPresenter(false)->find($id);
        return $user;
    }

    public function facebookLogin(FacebookAPILoginRequest $request) {
        $data = $request->all();

        try {
            $response = $this->facebookSdk->get('/me?fields=id,name,email,picture.type(large)', $data['facebook_token']);
        } catch(FacebookSDKException $e) {
            return [
                'error' => true,
                'error_description' => 'Facebook failed to get profile',
                'exception' => $e->getMessage(),
            ];
        }

        $userNode = $response->getGraphUser();
        $id = $userNode->getId();

        if ($id === $data['facebook_id']) {
            $this->facebookSdk->setDefaultAccessToken($data['facebook_token']);
            $user = $this->userRepository->findWhere(['facebook_id' => $id])->first();
            if ($user) {
                return $this->userRepository->skipPresenter(false)->find($user['id']);
            } else {
                $user = $this->userRepository->findWhere(['email' => $userNode->getEmail()])->first();
                if ($user) {
                    return [
                        'error' => true,
                        'error_code' => 1,
                        'error_description' => 'Email already registered',
                    ];
                }
                $img_data = file_get_contents($userNode->getPicture()->getUrl());

                $userData = [
                    'name' => $userNode->getName(),
                    'email' => $userNode->getEmail(),
                    'password' => Hash::make(str_random(10)),
                    'facebook_id' => $id,
                ];

                $user = $this->userRepository->create($userData);

                $user->avatar = $img_data;
                $user->save();

                $array = $this->userRepository->skipPresenter(false)->find($user['id']);
                $array['not_registered'] = 'Account Created';
                Auth::login($user);
                return $array;
            }
        } else {
            return [
                'error' => true,
                'error_description' => 'ID\'s differ on Server/Android',
            ];
        }

    }
}
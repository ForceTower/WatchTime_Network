<?php
/**
 * Created by PhpStorm.
 * User: joaop
 * Date: 03/03/2017
 * Time: 18:15
 */

namespace WatchTime\Http\Controllers\API;


use Facebook\Exceptions\FacebookSDKException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;
use PulkitJalan\Google\Facades\Google;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;
use WatchTime\Http\Controllers\Controller;
use WatchTime\Http\Requests\CoverUpdateRequest;
use WatchTime\Http\Requests\CreateAccountRequest;
use WatchTime\Http\Requests\FacebookAPILoginRequest;
use WatchTime\Http\Requests\GoogleAPILoginRequest;
use WatchTime\Http\Requests\SetFirebaseTokenRequest;
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

    public function setFirebaseToken(SetFirebaseTokenRequest $request) {
        $uid = Authorizer::getResourceOwnerId();
        $data = $request->all();

        $user = $this->userRepository->skipPresenter(true)->find($uid);
        $user->firebase_token = $data['token'];
        $user->save();
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

        header('content-type: '. 'image/png');
        return readfile("/profile_img/$id.png");
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
                'error_code' => 0,
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

                $uid = $user->id;
                file_put_contents(public_path()."/profile_img/$uid.png", $img_data);

                $array = $this->userRepository->skipPresenter(false)->find($user['id']);
                $array['not_registered'] = 'Account Created';
                Auth::login($user);
                return $array;
            }
        } else {
            return [
                'error' => true,
                'error_code' => 3,
                'error_description' => 'ID\'s differ on Server/Android',
            ];
        }
    }

    public function googleLogin(GoogleAPILoginRequest $request) {
        $data = $request->all();

        $client = Google::getClient();
        $payload = $client->verifyIdToken($data['google_token']);

        if ($payload) {
            $uid = $payload['sub'];
            if ($uid !== $data['google_id'])
                return [
                    'error' => true,
                    'error_code' => 3,
                    'error_description' => 'ID\'s differ on Server/Android',
                ];

            $user = $this->userRepository->findWhere(['google_id' => $uid])->first();
            if ($user)
                return $this->userRepository->skipPresenter(false)->find($user['id']);

            $user = $this->userRepository->findWhere(['email' => $payload['email']])->first();
            if ($user) {
                return [
                    'error' => true,
                    'error_code' => 1,
                    'error_description' => 'Email already registered',
                ];
            }

            $img_data = file_get_contents($payload['picture']);

            $userData = [
                'name' => $payload['name'],
                'email' => $payload['email'],
                'password' => Hash::make(str_random(10)),
                'google_id' => $payload['sub'],
            ];

            $user = $this->userRepository->create($userData);

            $uid = $user->id;
            file_put_contents(public_path()."/profile_img/$uid.png", $img_data);

            $array = $this->userRepository->skipPresenter(false)->find($user['id']);
            $array['not_registered'] = 'Account Created';
            Auth::login($user);
            return $array;

        } else {
            return [
                'error' => true,
                'error_code' => 0,
                'error_description' => 'Google Failed to Authenticate your token'
            ];
        }
    }

    public function createAccount(CreateAccountRequest $request) {
        $data = $request->all();

        $existing = $this->userRepository->skipPresenter(true)->findWhere(['email' => $data['email']])->first();
        if ($existing)
            return [
                'error' => true,
                'error_description' => 'Email already registered',
                'error_code' => 0,
            ];

        $boolean = false;
        $data_image = null;
        if($data['image'] !== 'no image') {
            $boolean = true;
            $data_image = base64_decode($data['image']);
        }

        $user = $this->userRepository->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        $uid = $user['id'];
        if ($boolean) {
            $user->avatar = $data_image;
            file_put_contents(public_path() . "/profile_img/$uid.png", $data_image);
        }

        $user->save();

        return [
            'success' => 'Account Created',
        ];
    }
}
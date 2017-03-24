<?php
/**
 * Created by PhpStorm.
 * User: joaop
 * Date: 23/03/2017
 * Time: 22:32
 */

namespace WatchTime\Http\Controllers\API;

use WatchTime\Http\Controllers\Controller;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use LaravelFCM\Facades\FCM;
use WatchTime\Repositories\UserRepository;

class FirebaseController extends Controller {
    const NEW_FRIEND = 0;

    private $userRepository;

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    public static function sendNotification($tokens, $data, UserRepository $userRep){
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);

        $option = $optionBuilder->build();

        $downstreamResponse = FCM::sendTo($tokens, $option, null, $data);

        $delete_queue = $downstreamResponse->tokensToDelete();
        foreach($delete_queue as $delete) {
            $user = $userRep->skipPresenter(true)->findWhere(['firebase_token' => $delete])->first();
            $user->firebase_token = null;
            $user->save();
        }
    }

    public static function buildDataPayload($notification_id, array $others) {
        $dataBuilder = new PayloadDataBuilder();

        $data = [];

        if ($notification_id == FirebaseController::NEW_FRIEND)
            $data = FirebaseController::buildNewFriendData($others);

        $dataBuilder->addData($data);
        return $dataBuilder->build();
    }

    public static function buildNewFriendData(array $others) {
        $data = [
            'title' => 'New Friend',
            'title_id' => 0,
            'message' => 'Your friend '. $others['name']. ' is now using the app',
            'message_id' => 0,
            'icon' => 'https://image.tmdb.org/t/p/w185/s0C78plmx3dFcO3WMnoXCz56FiN.jpg',
        ];

        return array_merge($data, $others);
    }


    public function test() {
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);

        $notificationBuilder = new PayloadNotificationBuilder('Hum...');
        $notificationBuilder->setBody('Realmente me faz pensar')->setSound('default')->setIcon('app_logo');

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['message' => 'Thinking...', 'title' => 'Hum...', 'icon' => 'https://image.tmdb.org/t/p/w185/s0C78plmx3dFcO3WMnoXCz56FiN.jpg']);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        $tokens =[];

        $users = $this->userRepository->skipPresenter(true)->all();
        foreach ($users as $user) {
            $token = $user->firebase_token;
            if ($token !== null)
                array_push($tokens, $token);
        }

        if(sizeof($tokens) === 0)
            return;

        $downstreamResponse = FCM::sendTo($tokens, $option, null, $data);

        $downstreamResponse->numberSuccess();
        $downstreamResponse->numberFailure();
        $downstreamResponse->numberModification();


        $delete_queue = $downstreamResponse->tokensToDelete();
        foreach($delete_queue as $delete) {
            $user = $this->userRepository->skipPresenter(true)->findWhere(['firebase_token' => $delete])->first();
            $user->firebase_token = null;
            $user->save();
        }

        //return Array (key : oldToken, value : new token - you must change the token in your database )
        $downstreamResponse->tokensToModify();

        //return Array - you should try to resend the message to the tokens in the array
        $downstreamResponse->tokensToRetry();
    }
}
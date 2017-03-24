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
    private $userRepository;

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function test() {
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);

        $notificationBuilder = new PayloadNotificationBuilder('Hum...');
        $notificationBuilder->setBody('Realmente me faz pensar')->setSound('default');

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['message' => 'Server Test Message']);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        $token = $this->userRepository->find(17)->firebase_token;

        $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);

        $downstreamResponse->numberSuccess();
        $downstreamResponse->numberFailure();
        $downstreamResponse->numberModification();

        $downstreamResponse->tokensToDelete();

        //return Array (key : oldToken, value : new token - you must change the token in your database )
        $downstreamResponse->tokensToModify();

        //return Array - you should try to resend the message to the tokens in the array
        $downstreamResponse->tokensToRetry();
    }
}
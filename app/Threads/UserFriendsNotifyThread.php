<?php
/**
 * Created by PhpStorm.
 * User: joaop
 * Date: 24/03/2017
 * Time: 16:26
 */

namespace WatchTime\Threads;


use WatchTime\Http\Controllers\API\FirebaseController;
use WatchTime\Repositories\UserRepository;

class UserFriendsNotifyThread implements WTWorker {
    private $name;
    private $friends;
    private $usersRep;
    private $image;

    public function __construct($name, $friends, UserRepository $userRepository, $image) {
        $this->friends = $friends;
        $this->usersRep = $userRepository;
        $this->name = $name;
        $this->image = $image;
    }

    public function executeTask() {
        $tokens =[];
        foreach ($this->friends as $friend) {
            $fid = $friend['id'];
            $ff = $this->usersRep->skipPresenter(true)->findWhere(['facebook_id' => $fid])->first();

            if (!$ff || !$ff->firebase_token)
                continue;
            else {
                $token = $ff->firebase_token;
                array_push($tokens, $token);
            }
        }

        if (sizeof($tokens) === 0)
            return;

        $data = FirebaseController::buildDataPayload(FirebaseController::NEW_FRIEND, ['name' => $this->name, 'image' => $this->image]);
        FirebaseController::sendNotification($tokens, $data, $this->usersRep);
    }
}
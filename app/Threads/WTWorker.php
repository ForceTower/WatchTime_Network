<?php
/**
 * Created by PhpStorm.
 * User: joaop
 * Date: 24/03/2017
 * Time: 16:30
 */

namespace WatchTime\Threads;


interface WTWorker {
    public function executeTask();
}
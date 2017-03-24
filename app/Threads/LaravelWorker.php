<?php
/**
 * Created by PhpStorm.
 * User: joaop
 * Date: 24/03/2017
 * Time: 16:27
 */

namespace WatchTime\Threads;

use Thread;

class LaravelWorker extends Thread {
    private $work;

    public function __construct(WTWorker $work) {
        $this->work = $work;
    }

    public function run() {
        $this->work->executeTask();
    }
}
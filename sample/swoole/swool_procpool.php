<?php
/**
 * Created by IntelliJ IDEA.
 * User: mcatom
 * Date: 2019-04-19
 * Time: 15:15
 */


$workNum = 2;
$pool = new \Swoole\Process\Pool($workNum);

$pool->on("WorkerStart", function ($pool, $workerId) {
    echo "ll{$workerId}\n";
    echo "xxx\n";
    while (true) {
        echo "fffff\n";
        sleep(1);
    }

});

$pool->start();














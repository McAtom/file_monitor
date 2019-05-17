<?php
/**
 * Created by IntelliJ IDEA.
 * User: mcatom
 * Date: 2019-03-26
 * Time: 15:31
 */
mt_rand(0,1);

for($i = 0; $i <= 10; $i++) {
    $process = new swoole_process('child_async', false, 2);
    $pid = $process->start();
}

function child_async(swoole_process $worker) {
    echo mt_rand(0, 100).PHP_EOL;
    $worker->exit();
}
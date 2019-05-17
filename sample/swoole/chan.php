<?php
/**
 * Created by IntelliJ IDEA.
 * User: mcatom
 * Date: 2019-03-27
 * Time: 16:27
 */

//$chann = new chan(10);
//co::create(function() use($chann){
//    for($i = 0; $i < 99; $i++) {
//        co::sleep(1.0);
//        $chann->push(['date'=>rand(0, 1000), 'id'=>$i]);
//        echo "{$i}\n";
//    }
//});
//co::create(function() use ($chann){
//    while (1) {
//        $data = $chann->pop();
//        print_r($data);
//    }
//});
//echo "nnn\n";
//swoole_event_wait();

//go(function(){
//    co::sleep(1);
//    echo '1';
//    co::sleep(3);
//    echo '2';
//});
//
\Swoole\Runtime::enableCoroutine(true);
go(function(){
    co::sleep(1);
    echo date("Y-m-d H:i:s")."=>3";
    co::sleep(2);
    echo date("Y-m-d H:i:s")."=>4";
});
//\Swoole\Runtime::enableCoroutine(true);
//function tick() {
//    echo "hello\n";
//}
//go(function () {
//    \Swoole\Timer::tick(1000,"tick");
//});
//go(function () {
//    echo "开始sleep 4\n";
//    sleep(4);
//    echo "结束sleep 4\n";
//});
//
//go(function (){
//    echo "开始sleep 3\n";
//    sleep(3);
//    echo "结束sleep 3\n";
//});



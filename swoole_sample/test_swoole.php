<?php
/**
 * Created by IntelliJ IDEA.
 * User: mcatom
 * Date: 2019-03-19
 * Time: 21:20
 */
//$srv = new swoole_server("f", 9001);
//$srv->on("connect", function ($srv, $fd){
//    echo " client:connect...\n";
//});
//
//$srv->on('receive', function ($srv, $fd, $from_id, $data) {
//    $srv->send($fd, "Server: ".$data);
//});
//
////监听连接关闭事件
//$srv->on('close', function ($srv, $fd) {
//    echo "Client: Close.\n";
//});
//
////启动服务器
//$srv->start();

//每隔2000ms触发一次
go(function (){
    swoole_timer_tick(2000, function ($timer_id) {
        echo "tick-2000ms\n";
    });
});

while(true) {
    echo "lll\n";
    sleep(1);
}
//3000ms后执行此函数
swoole_timer_after(3000, function () {
    echo "after 3000ms.\n";
});
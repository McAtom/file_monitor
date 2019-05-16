<?php
/**
 * Created by IntelliJ IDEA.
 * User: mcatom
 * Date: 2019-03-27
 * Time: 15:56
 */
//yield
//$cid = go(function(){
//    echo "col 1\n";
//    co::yield();
//    echo "col 2\n";
//});
//var_dump($cid);
//go(function() use ($cid){
//    echo "col 3\n";
//    sleep(2);
//    co::resume($cid);
//});

//fread

//$fp = fopen('/data/logs/login/20190322/0050.log', "r");
//co::create(function () use ($fp)
//{
//    $r =  co::fread($fp);
//    var_dump($r);
//});

go(function() {
    $array = co::getHostByName("www.baidu.com");
    print_r($array);
});

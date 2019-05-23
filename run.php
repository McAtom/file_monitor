<?php
/**
 * Created by IntelliJ IDEA.
 * User: mcatom
 * Date: 2019-03-21
 * Time: 14:06
 */
include dirname(__FILE__)."/core/YakAutoFile.php";

if(!empty($_SERVER['argv'][1]) && PHP_SAPI == 'cli') {
    parse_str($_SERVER['argv'][1], $_GET);
}

if(!empty($_GET['monitor_name'])) {
    $monitor_name = $_GET['monitor_name'];
} else {
    die("monitor_name 不能为空");
}

$swoole = new YakSwoole();
$swoole->startInotify($monitor_name);










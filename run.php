<?php
/**
 * Created by IntelliJ IDEA.
 * User: mcatom
 * Date: 2019-03-21
 * Time: 14:06
 */
include dirname(__FILE__)."/core/YakAutoFile.php";

$swoole = new YakSwoole();
$monitor_name = "test";
$swoole->startInotify($monitor_name);










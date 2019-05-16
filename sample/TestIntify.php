<?php
/**
 * Created by IntelliJ IDEA.
 * User: mcatom
 * Date: 2019-03-20
 * Time: 15:09
 */

//class TestIntify {
//
//
//
//
//}
$events = [
    IN_ACCESS => 'File Accessed',
    IN_MODIFY => 'File Modified',
    IN_ATTRIB => 'File Metadata Modified',
    IN_CLOSE_WRITE => 'File Closed, Opened for Writing',
    IN_CLOSE_NOWRITE => 'File Closed, Opened for Read',
    IN_OPEN => 'File Opened',
    IN_MOVED_FROM => 'File Moved Out',
    IN_CREATE => 'File Created',
    IN_DELETE => 'File Deleted'
];

$mask = array_sum(array_keys($events));
$monitorPath = "/data/logs/login";
$fp = inotify_init();
if($fp === false) {
    echo "初始化失败\n";
}
$id = inotify_add_watch($fp, $monitorPath, $mask);
var_dump($id);
stream_set_blocking($fp, 1);

echo "开始监控{$monitorPath}...\n";
while ($event_list = inotify_read($fp)) {
    echo date('Y-m-d H:i:s')."\n";
    echo json_encode($event_list)."\n";
    sleep(2);
    echo "完成\n";
//    foreach ($event_list as $arr) {
//        $ev_mask = $arr['mask'];
//        $ev_file = $arr['name'];
//        if (isset($events[$ev_mask])) {
//            echo "  ",$events[$ev_mask], ", Filename: ", $ev_file, PHP_EOL;
//        } else {
//            print_r($arr);
//        }
//    }
}

inotify_rm_watch($fp, $id);
fclose($fp);






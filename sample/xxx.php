<?php
$filename = '/tmp/ll';
$handle = inotify_init();
stream_set_blocking($handle, 0);
inotify_add_watch($handle, $filename, IN_MODIFY | IN_CREATE | IN_DELETE_SELF);
while(true) {
    $events = inotify_read($handle);
    echo inotify_queue_len($handle)."\n";
    var_dump($events);
    echo "\n======进入sleep======\n";
    sleep(1);
    echo "======华丽的分割线======\n";
}

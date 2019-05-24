<?php
/**
 * Created by IntelliJ IDEA.
 * User: mcatom
 * Date: 2019-03-21
 * Time: 09:43
 * 返回事件
 */
return array(
    1 => array('IN_ACCESS','File was accessed (read)'),
    2 => array('IN_MODIFY','File was modified'),
    4 => array('IN_ATTRIB','Metadata changed (e.g. permissions, mtime, etc.)'),
    8 => array('IN_CLOSE_WRITE','File opened for writing was closed'),
    16 => array('IN_CLOSE_NOWRITE','File not opened for writing was closed'),
    32 => array('IN_OPEN','File was opened'),
    128 => array('IN_MOVED_TO','File moved into watched directory'),
    64 => array('IN_MOVED_FROM','File moved out of watched directory'),
    256 => array('IN_CREATE','File or directory created in watched directory'),
    512 => array('IN_DELETE','File or directory deleted in watched directory'),
    1024 => array('IN_DELETE_SELF','Watched file or directory was deleted'),
    2048 => array('IN_MOVE_SELF','Watch file or directory was moved'),
    24 => array('IN_CLOSE','Equals to IN_CLOSE_WRITE | IN_CLOSE_NOWRITE'),
    192 => array('IN_MOVE','Equals to IN_MOVED_FROM | IN_MOVED_TO'),
    4095 => array('IN_ALL_EVENTS','Bitmask of all the above constants'),
    8192 => array('IN_UNMOUNT','File system containing watched object was unmounted'),
    16384 => array('IN_Q_OVERFLOW','Event queue overflowed (wd is -1 for this event)'),
    32768 => array('IN_IGNORED','Watch was removed (explicitly by inotify_rm_watch() or because file was removed or filesystem unmounted'),

    1073741824 => array('IN_ISDIR','Subject of this event is a directory'),
    1073741840 => array('IN_CLOSE_NOWRITE','High-bit: File not opened for writing was closed'),
    1073741856 => array('IN_OPEN','High-bit: File was opened'),
    1073742080 => array('IN_CREATE','High-bit: File or directory created in watched directory'),
    1073742336 => array('IN_DELETE','High-bit: File or directory deleted in watched directory'),
    16777216 => array('IN_ONLYDIR','Only watch pathname if it is a directory (Since Linux 2.6.15)'),
    33554432 => array('IN_DONT_FOLLOW','Do not dereference pathname if it is a symlink (Since Linux 2.6.15)'),
    536870912 => array('IN_MASK_ADD','Add events to watch mask for this pathname if it already exists (instead of replacing mask).'),
    2147483648 => array('IN_ONESHOT','Monitor pathname for one event, then remove from watch list.')
);
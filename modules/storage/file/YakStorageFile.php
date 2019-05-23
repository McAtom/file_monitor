<?php
/**
 * Created by IntelliJ IDEA.
 * User: mcatom
 * Date: 2019-05-23
 * Time: 10:31
 */
include_once "config.php";

class YakStorageFile implements YakInterface {


    public function sendData($data) {
        $file = $data['log_type'];
        $line = $data['line'];
        file_put_contents(YAK_LOG."/test/{$file}.log", $line."\n", FILE_APPEND);
        return true;
    }

    public function flush() {
        //nothing to do
    }


}
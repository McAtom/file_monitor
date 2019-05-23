<?php
/**
 * Created by IntelliJ IDEA.
 * User: mcatom
 * Date: 2019-05-23
 * Time: 10:31
 */
class YakStorageConsole implements YakInterface {


    public function sendData($data) {
        $file = $data['log_type'];
        $line = $data['line'];
//        echo "{$file}=>{$line}\n";
        return true;
    }

    public function flush() {
        //nothing to do
    }


}
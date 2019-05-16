<?php
/**
 * Created by IntelliJ IDEA.
 * User: mcatom
 * Date: 2019-05-05
 * Time: 19:32
 */

function readLines($file) {
    $file_obj = new SplFileObject($file, "r");
    $line_no = 0;
    $file_obj->seek($line_no);
    $line_index = 0;
    while(!$file_obj->eof()) {
        $line_info = $file_obj->current();
        $line_index++;
        $file_obj->next();
        $line_info = trim($line_info);
        if(trim($line_info) == "") continue;
        file_put_contents("//data/project/file_monitor/logs/runtime/test2.log", $line_info."\n", FILE_APPEND);
    }
}
//$t1 = microtime(true);
$spl = new SplFileObject("/data/project/logs/login/20190505/1927.log", "r");
print_r($spl->fstat());
//$dv = microtime(true) - $t1;
//echo $dv;
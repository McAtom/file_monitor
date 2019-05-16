<?php
/**
 * Created by IntelliJ IDEA.
 * User: mcatom
 * Date: 2019-03-21
 * Time: 09:41
 */

class YakTools {

    /**
     * @param $msg
     * @param string $filepath
     * @param string $flag
     * 记录一些日志
     */
    public static function Logger($msg, $filepath="runtime", $flag="default") {
        $time = time();
        if(is_array($msg)) {
            $msg = var_export($msg, true);
        }
        $msg = date("Y-m-d H:i:s", $time)."|{$flag}=>{$msg}\n";
        $filepath = YAK_LOG."/{$filepath}";
        if(!file_exists($filepath)) {
            mkdir($filepath, 0777, true);
        }
        echo $msg;
//        $filepath = "{$filepath}/".date('Ymd', $time).".log";
//        return file_put_contents($filepath, $msg, FILE_APPEND);
    }

    /**
     * @param $confFile
     * @return mixed
     * 加载配置文件
     */
    public static function loadConf($conf_file) {
        $file = YAK_CONF."/YakConf{$conf_file}.php";
        if(!file_exists($file)) {
            throw new RuntimeException("{$file}不存在");
        }
        return include $file;
    }

    /**
     * @param $dir
     * @return bool
     * 检查文件，
     */
    public static function dirExistOrCreate($dir) {
        $flag = true;
        if(!file_exists($dir)) {
            $flag = mkdir($dir, 0777, true);
            YakTools::Logger("监控目录不存在[{$dir}],已经创建");
        }
        if($flag === false) {
            YakTools::Logger("监控目录创建失败[{$dir}]");
        }
        return $flag;
    }

    /**
     * @param $dir
     * @return array
     * 递归扫描目录，不借助scandir(此函数可能会被禁用)
     * 而且符合 日期格式（Ymd）才做监控
     * 如果想改成监控子目录，可以写成递归的方式
     */
    public static function scandirRec($dir) {
        $dirs = array();
        if(YakTools::dirExistOrCreate($dir) === false) {
            return $dirs;
        }
        if ($handle = opendir($dir)) {
            while (false !== ($filename = readdir($handle))) {
                if($filename == '.' || $filename == '..') {
                    continue;
                }
                $dirs[] = array(
                    'dir' => $dir,
                    'filename' => $filename,
                    'path' => $dir."/".$filename
                );
            }
            closedir($handle);
        }
        return $dirs;
    }

    /**
     * @param $file
     * @return array
     * file = /data/logs/login/20190322
     * return = ['file_name', 'file_dir']
     */
    public static function explodeFileName($file) {
        $file_arr = explode("/", $file);
        $length = count($file_arr);
        $date = $file_arr[$length - 1];
        return [
            'file_name'     => $date."_".md5($file).".db",
            'file_dir'      => $file_arr[$length - 2],
            'file_date'     => $date
        ];
    }

    /**
     * @param $dir_str
     * @param $time_range
     * @return bool
     * 检查监控目录的格式
     */
    public static function checkMonitorDirFormart($dir_str, $time_range) {
        if(strlen($dir_str) != 8) {
            return false;
        }
        $start = date("Ymd", time() - ($time_range - 1) * 86400);
        $stop = date("Ymd", time());
        if($dir_str >= $start && $dir_str <= $stop) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $dir_str
     * @return bool
     * 检查监控的文件格式
     * todo:可以改成正则表达式
     */
    public static function checkMonitorFileFormart($dir_str) {
        if(strlen($dir_str) != 8) {
            return false;
        }
        $start = "0000.log";
        $stop = "2359.log";
        if($dir_str >= $start && $dir_str <=$stop) {
            return true;
        } else {
            return false;
        }
    }

}
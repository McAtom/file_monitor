<?php
/**
 * Created by IntelliJ IDEA.
 * User: mcatom
 * Date: 2019-04-23
 * Time: 16:41
 */
class YakInotify {

    private $logread_handle = array();      //记录行号的文件句柄,需要定时清除
    private $logread_save_times = 60;      //轮询60次之后savelineno
    private $logread_now_times = 0;         //当前轮询次数

    private $monitor_mask = array();

    private $monitor_time_range = 1;        //监控日志事件1表示当天，2表示近2天
    private $monitor_loop_time = 1;         //轮询2秒检查一次
    private $fd_inotify = array();          //监控句柄，需要定时清除
    private $watch_handle = array();        //好像用不到
    //监控文件的几个事件
    private $file_masks = IN_MODIFY | IN_CREATE | IN_DELETE_SELF;          //真实要使用的。

    private $param;


    /**
     * YakInotify constructor.
     * @param $param
     * monitor_name, log_type, work_id, log_path
     */
    public function __construct($param) {
        //todo:检查必须的参数
        $this->monitor_mask = YakTools::loadConf("Mask");
        $this->param = $param;
    }

    /**
     * @return $this
     * 预处理方法
     */
    public function pretreatment() {
        echo $this->param['log_path']."============\n";
        $new_dirs = YakTools::scandirRec($this->param['log_path']);
        if(empty($new_dirs)) {
            echo "要监控的目录为空\n";
        }
        foreach ($new_dirs as $new_dir) {
            if($this->checkNewDirIfMonitor($new_dir['dir'], $new_dir['filename'])) {
                $this->initScanFile($new_dir['path']);          //扫描整个目录文件，实现一次初始化数据
                $this->initInotify($new_dir['path']);           //初始化inotify的对象
            }
        }
        $this->initInotify($this->param['log_path']);
        return $this;
    }

    /**
     * 启动监控
     */
    public function startWatch(){
        while(true) {
            $t1 = microtime(true);
            foreach ($this->fd_inotify as $path => $handle){
                $events = inotify_read($handle);
                if ($events) {
                    $events = YakTools::eventUnique($events);
                    print_r($events);
                    foreach ($events as $event){
                        $change_file = "{$path}/{$event['name']}";
                        $change_event = $this->monitor_mask[$event['mask']][0];
                        YakTools::Logger("{$change_file}: {$change_event} ({$this->monitor_mask[$event['mask']][1]})", $this->param['monitor_name']);

                        if($change_event =='IN_CREATE' && is_dir($change_file)) {
                            if($this->checkNewDirIfMonitor($path, $event['name'])) {
                                $this->initInotify($change_file);
                            }

                        } else if($change_event == 'IN_MODIFY' && is_file($change_file)) {
                            $this->checkNewFileMonitor($path, $event['name']);

                        }  else if($change_event =='IN_DELETE_SELF') {
                            $this->removeWatch($change_file);
                        }
                    }
                }
            }
            $div = microtime(true) - $t1;
            YakTools::Logger("({$this->param['log_path']})时间差：{$div}");

            sleep($this->monitor_loop_time);

            //定期检查清理无用变量
            $this->logread_now_times++;
            if($this->logread_now_times >= $this->logread_save_times) {
                $this->clearResource();
            }

            YakTools::Logger("======华丽的分割线======", $this->param['monitor_name']);
        }
    }

    /**
     * @param $path
     * @param $filename
     * @return bool
     * 监控检查新创建的文件是否需要加入监控
     */
    private function checkNewDirIfMonitor($path, $filename) {
        $file = $path."/".$filename;
        $flag = YakTools::checkMonitorDirFormart($filename, $this->monitor_time_range);
        if($flag === true) {
            $this->logread_handle[$file] = new YakLogReader($file);
            YakTools::Logger("{$filename}加入监控队列中",$this->param['monitor_name']);
        } else {
            YakTools::Logger("{$filename}不需要加入监控队列",$this->param['monitor_name']);
        }
        return $flag;
    }

    /**
     * @param $path
     * @param $filename
     * 检查文件是否在可以读的格式中
     */
    private function checkNewFileMonitor($path, $filename) {
        $file = $path."/".$filename;
        $flag = YakTools::checkMonitorFileFormart($filename);
        if($flag === true) {
            if(!empty($this->logread_handle[$path])) {
                $this->logread_handle[$path]->readLines($this->param, $file);
            } else {
                YakTools::Logger("logread没实例化[{$path}]",$this->param['monitor_name']);
            }
        } else {
            YakTools::Logger("{$file}格式不符合，不需要扫描",$this->param['monitor_name']);
        }
    }

    /**
     * @param $filename
     * @return bool
     * 初始化句柄
     */
    private function initInotify($filename) {
        $handle = inotify_init();
        if($handle === false) {
            YakTools::Logger("监控初始化失败[{$filename}]", $this->param['monitor_name']);
            return false;
        }
        stream_set_blocking($handle, 0);
        $watch_handle = inotify_add_watch($handle, $filename, $this->file_masks);
        YakTools::Logger("[{$filename}],watcherId={$watch_handle}", $this->param['monitor_name']);
        $this->fd_inotify[$filename] = $handle;
        $this->watch_handle[$filename] = $watch_handle;
        return true;
    }

    /**
     * @param $dir
     * @return bool
     * 初始化扫描文件
     */
    private function initScanFile($dir) {
        echo "扫描====".$dir."\n";
        if($handle = opendir($dir)) {
            while (false !== ($filename=readdir($handle))) {
                if($filename == '.' || $filename == '..') {
                    continue;
                }
                $this->checkNewFileMonitor($dir, $filename);
            }
        }
        return true;
    }

    /**
     * 定时执行操作
     * 1、把filepoint保存到数据里面。ps 这个步骤已经移动到logreader，每读一次，就保存一次
     * 2、清除filepoint 的对象。
     * 3、清除watcher对象。
     * 4、清除inotify对象。
     */
    private function clearResource() {
        YakTools::Logger("进入定时操作环节", $this->param['monitor_name']);
        $this->logread_now_times = 0;
        $date = date("Ymd", time() - 43200);
        foreach ($this->logread_handle as $file_dir => $handle) {
            $file_arr = YakTools::explodeFileName($file_dir);
            if($file_arr['file_date'] < $date) {
                unset($this->logread_handle[$file_dir]);
                $this->removeWatch($file_dir);
            }
        }
    }

    /**
     * @param $path
     * 移除监控
     */
    private function removeWatch($path) {
        YakTools::Logger("移除监控:{$path}", $this->param['monitor_name']);
        if(!empty($this->fd_inotify[$path])) {
            inotify_rm_watch($this->fd_inotify[$path], $this->watch_handle[$path]);
            unset($this->watch_handle[$path]);
            fclose($this->fd_inotify[$path]);
            unset($this->fd_inotify[$path]);
        }
    }

    public function __destruct() {
        foreach ($this->fd_inotify as $path => $val){
            $this->removeWatch($path);
        }

    }
}
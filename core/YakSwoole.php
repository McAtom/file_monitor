<?php
/**
 * Created by IntelliJ IDEA.
 * User: mcatom
 * Date: 2019-04-23
 * Time: 16:44
 */

class YakSwoole {

    /**
     * 启动监听日志变化程序
     */
    public function startInotify($monitor_name) {

        $all_conf = YakTools::loadConf("Monitor");
        if(empty($all_conf[$monitor_name]) || empty($all_conf[$monitor_name]['logs'])) {
            $msg = "文件监控配置{$monitor_name}为空或者file_paths为空, 不需要监控";
            YakTools::Logger($msg, $monitor_name);
            throw new RuntimeException($msg);
        }

        $log_conf = $all_conf[$monitor_name]['logs'];
        $work_num = count($log_conf);
        echo "{$monitor_name} 启动了{$work_num}个进程数\n";

        $process = new \Swoole\Process\Pool($work_num);
        $process->on("WorkerStart", function ($pool, $workerId) use ($monitor_name, $all_conf) {
            $logs = array_keys($all_conf[$monitor_name]['logs']);
            $param = [
                'monitor_name'  => $monitor_name,
                'worker_id'     => $workerId,
                'log_type'      => $logs[$workerId],
                'log_path'      => $all_conf[$monitor_name]['logs'][$logs[$workerId]]['path'],
                'storage'       => $all_conf[$monitor_name]['logs'][$logs[$workerId]]['storage'],
            ];
            print_r($param);
            $yak_inotify = new YakInotify($param);
            $yak_inotify->pretreatment()->startWatch();
        });

        $process->start();
    }

}
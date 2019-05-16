<?php
/**
 * Created by IntelliJ IDEA.
 * User: mcatom
 * Date: 2019-03-21
 * Time: 23:58
 * 生成测试日志对接
 */

class YakGenData {

    private $record_num = 100;          //要生成多少条记录
    private $file_dir = "/data/project/logs/";
    private $file_date = "";


    public function setParam($num="", $date="") {
        if(!empty($num)) {
            $this->record_num = $num;
        }
        if(empty($date)) {
            $this->file_date = date("Ymd");
        } else {
            $this->file_date = $date;
        }
        $this->record_num = $num;
        return $this;
    }

    /**
     * 注册日志
     */
    public function reg() {
        $file_dir = $this->file_dir.__FUNCTION__;
        $param = array(
            'reg_game' => 'chuanqi',
            'reg_recdate' => '2019-01-01'
        );
        $this->genLog($file_dir, $param);
        return $this;
    }

    public function login() {
        $file_dir = $this->file_dir.__FUNCTION__;
        $param = array(
            'flg_game_rt' => 100,
        );
        $this->genLog($file_dir, $param);
        return $this;
    }

    public function view() {
        $file_dir = $this->file_dir.__FUNCTION__;
        $param = array(
            'channel_id' => 100,
        );
        $this->genLog($file_dir, $param);
        return $this;
    }

    public function download() {
        $file_dir = $this->file_dir.__FUNCTION__;
        $param = array(
            'ref' => 'dkm',
        );
        $this->genLog($file_dir, $param);
        return $this;
    }

    /**
     * @param array $param
     * 生成日志log
     */
    private function genLog($file_dir, $param = array()) {
        $dir = "{$file_dir}/{$this->file_date}";
        if(!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        for ($i = 1; $i <= $this->record_num; $i++) {
            $time = time();
            $data = array(
                'game_id' => rand(0, 500),
                'game_pkg' => 'game_pkg'.rand(0, 500),
                'partner_id' => rand(0, 100),
                'uuid' => 'uuid-'.rand(0, 1000000),
                'rt' => $time
            );
            if(!empty($param)) {
                $_param = array_map(function ($val) use ($i) {
                    return $val.$i;
                },$param);
                $data = array_merge($data, $_param);
            }
            $file = "{$dir}/".date("Hi", $time).".log";
            file_put_contents($file, json_encode($data)."\n", FILE_APPEND);
            usleep(10000);     //0.01秒
        }
    }
}
if(!empty($_SERVER['argv'][1]) && PHP_SAPI == 'cli'){
    parse_str($_SERVER['argv'][1], $_GET);
}
if(empty($_GET['log'])) {
    die("请带上log参数\n");
}
$method = $_GET['log'];
$gen = new YakGenData();
$gen->setParam(5000)->$method();
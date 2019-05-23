<?php
/**
 * Created by IntelliJ IDEA.
 * User: mcatom
 * Date: 2019-03-21
 * Time: 18:00
 * 一个存储的记录文件一个实例
 */

class YakLogReader {

    private $recorde_file = "";             //文件指针记录到文件里面
    private $record_lines = array();        //记录文件指针行数
    private $storage;
    private $limit_line = 3000;             //一次事件只能读3000条记录，还没做限制

    /**
     * data/logs/login/20190322
     */
    public function __construct($file) {
        $explode_arr = YakTools::explodeFileName($file);
        $dir = YAK_LOG."/filepoint/{$explode_arr['file_dir']}";
        if(!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        $this->storage = new YakStorage();
        $this->recorde_file = "{$dir}/{$explode_arr['file_name']}";
        $this->initFileLineNo();
    }

    /**
     * @param $logtype
     * @param $file
     * 根据指针来读数据
     */
    public function readLines($param, $file) {
        $file_obj = new SplFileObject($file, "r");
        $line_no = $this->getLineNo($file);
        YakTools::Logger("{$file}=开始行号：{$line_no}");
        $file_obj->seek($line_no);
        $line_index = 0;
        while(!$file_obj->eof()) {
            $line_info = $file_obj->current();
            $line_index++;
            $file_obj->next();
            $line_info = trim($line_info);
            if(trim($line_info) == "") continue;
            $this->storage->store($param, $line_info);
        }
        $this->storage->flush($param);
        $line_index = $line_index == 0 ? 0 : $line_index -1 ;
        $new_line_no = $line_no + $line_index;
        YakTools::Logger("{$file}=结束行号：{$new_line_no}");
        $this->record_lines[$file] = $new_line_no;
        $this->saveFileLineNo();
    }

    /**
     * @param $file
     * @return mixed
     * 获取行号
     */
    private function getLineNo($file) {
        $this->record_lines[$file] = empty($this->record_lines[$file]) ? 0 : $this->record_lines[$file];
        return $this->record_lines[$file];
    }

    /**
     * 保存行号到文件里面
     * todo: 改成使用sqlite来存储数据，读也是一样原理
     */
    public function saveFileLineNo() {
        if(!empty($this->record_lines)) {
            file_put_contents($this->recorde_file, json_encode($this->record_lines));
        }
    }

    /**
     * 获取文件行号
     */
    private function initFileLineNo() {
        if(file_exists($this->recorde_file)) {
            $d = file_get_contents($this->recorde_file);
            $d = trim($d);
            $this->record_lines = !empty($d) ? json_decode($d, true) : array();
        } else {
            $this->record_lines = array();
        }
    }

}

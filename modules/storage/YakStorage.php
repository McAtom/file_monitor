<?php
/**
 * Created by IntelliJ IDEA.
 * User: mcatom
 * Date: 2019-05-22
 * Time: 20:28
 */
class YakStorage {

    /**
     * @var $storageObj YakInterface;
     */
    static $storageObj;

    /**
     * @param $param
     * @param $line
     * 存储
     */
    public function store($param, $line) {
        $obj = null;
        switch ($param['storage']) {
            case 'kafka':
                $this->getKafkaObj($param)->sendData(['line'=>$line]);
                break;
            case 'file':
                $this->getFileObj()->sendData(['line'=>$line, 'log_type'=>$param['log_type']]);
                break;
            case 'console':
                $this->getConsoleObj()->sendData(['line'=>$line, 'log_type'=>$param['log_type']]);
                break;
            default:
                echo "not storage modules={$param['storage']}";
        }
    }

    /**
     * @param $param
     * 刷新，目前只有kafka需要
     */
    public function flush($param) {
        switch ($param['storage']) {
            case 'kafka':
                $this->getKafkaObj($param)->flush();
        }
    }

    /**
     * @param $param
     * @return YakInterface
     */
    private function getKafkaObj($param) {
        $topic = $param['log_type'];
        if(!empty(self::$storageObj['kafka'][$topic])) {
            return self::$storageObj['kafka'][$topic];
        }
        echo "dddd";
        include_once YAK_MODULES."/storage/kafka/YakStorageKafka.php";
        self::$storageObj['kafka'][$topic] = new YakStorageKafka();
        self::$storageObj['kafka'][$topic]->setProducerTopic($param['log_type']);
        return self::$storageObj['kafka'][$topic];
    }

    /**
     * @param $param
     * @return YakInterface
     */
    private function getFileObj() {
        if(!empty(self::$storageObj['file'])) {
            return self::$storageObj['file'];
        }
        include_once YAK_MODULES."/storage/file/YakStorageFile.php";
        self::$storageObj['file'] = new YakStorageFile();
        return self::$storageObj['file'];
    }

    /**
     * @param $param
     * @return YakInterface
     */
    private function getConsoleObj() {
        if(!empty(self::$storageObj['console'])) {
            return self::$storageObj['console'];
        }
        include_once YAK_MODULES."/storage/console/YakStorageConsole.php";
        self::$storageObj['console'] = new YakStorageConsole();
        return self::$storageObj['console'];
    }

}
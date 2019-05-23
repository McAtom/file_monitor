<?php
/**
 * Created by PhpStorm.
 * User: qkl
 * Date: 2018/8/14
 * Time: 15:45
 */
include_once "config.php";

class YakStorageKafka implements YakInterface {

    private $rkp;
    /**
     * @var $topicObj \RdKafka\ProducerTopic
     */
    private $topicObj;

    public function __construct($config = []) {
        $rkConf = new RdKafka\Conf();
        $rkConf->setDrMsgCb(function ($kafka, $message) {
//            file_put_contents("./dr_cb.log", var_export($message, true).PHP_EOL, FILE_APPEND);
        });
        $rkConf->setErrorCb(function ($kafka, $err, $reason) {
//            file_put_contents("./err_cb.log", sprintf("Kafka error: %s (reason: %s)", rd_kafka_err2str($err), $reason).PHP_EOL, FILE_APPEND);
        });
        $this->rkp = new RdKafka\Producer($rkConf);
        $this->rkp->setLogLevel(LOG_WARNING);
        $this->rkp->addBrokers("kafka-01:9092");
    }

    public function setProducerTopic($topicName) {
        $cf = new RdKafka\TopicConf();
        $cf->set('request.required.acks', 0);
        $this->topicObj = $this->rkp->newTopic($topicName, $cf);
        return $this;
    }

    public function sendData($data) {
        $msg = $data['line'];
        $option = "";
        $this->topicObj->produce(RD_KAFKA_PARTITION_UA, 0, $msg, $option);
        //刷新事件队列
        $num = $this->rkp->getOutQLen();
        if($num > 2000) {
            $this->poll();
        }
        return $this;
    }

    public function flush() {
        $len = $this->rkp->getOutQLen();
        while ($len > 0) {
            $len = $this->rkp->getOutQLen();
            $this->poll();
        }
        return true;
    }

    private function poll() {
        $this->rkp->poll(50);
    }
}
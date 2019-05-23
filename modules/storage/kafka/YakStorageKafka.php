<?php
/**
 * Created by PhpStorm.
 * User: qkl
 * Date: 2018/8/14
 * Time: 15:45
 */
include_once "YakKafkaConfig.php";

class YakStorageKafka implements YakInterface {

    private $rkp;
    /**
     * @var $topicObj \RdKafka\ProducerTopic
     */
    private $topicObj;


    public function __construct() {
        $rkConf = new RdKafka\Conf();
        //不需要每条记录都有反馈
//        $rkConf->setDrMsgCb(function ($kafka, $message) {
//            file_put_contents(YakKafkaConfig::CB_INFO_LOG, var_export($message, true)."\n", FILE_APPEND);
//        });
        //错误回调
        $rkConf->setErrorCb(function ($kafka, $err, $reason) {
            file_put_contents(YakKafkaConfig::CB_ERROR_LOG, sprintf("Kafka error: %s (reason: %s)", rd_kafka_err2str($err), $reason)."\n", FILE_APPEND);
        });
        $this->rkp = new RdKafka\Producer($rkConf);
        $this->rkp->setLogLevel(YakKafkaConfig::LOG_LEVEL);
        $this->rkp->addBrokers(YakKafkaConfig::KAFKA_BROKERS);
    }

    public function setProducerTopic($topicName) {
        $cf = new RdKafka\TopicConf();
        foreach (YakKafkaConfig::TopicConf() as $name=>$val) {
            $cf->set($name, $val);
        }
        $this->topicObj = $this->rkp->newTopic($topicName, $cf);
        return $this;
    }

    public function sendData($data) {
        $msg = $data['line'];
        $option = "";
        $this->topicObj->produce(RD_KAFKA_PARTITION_UA, 0, $msg, $option);
        //刷新事件队列
        $num = $this->rkp->getOutQLen();
        if($num > YakKafkaConfig::POLL_LIMIT_NUM) {
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
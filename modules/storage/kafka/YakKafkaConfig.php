<?php
/**
 * Created by IntelliJ IDEA.
 * User: mcatom
 * Date: 2019-05-22
 * Time: 19:57
 */

class YakKafkaConfig {

    const KAFKA_BROKERS = "kafka-01:9092";
    const LOG_LEVEL = LOG_WARNING;
    const POLL_LIMIT_NUM = 2000;
    const CB_ERROR_LOG = YAK_MODULES."/storage/kafka/err_cb.log";
    const CB_INFO_LOG = YAK_MODULES."/storage/kafka/info_cb.log";

    public static function TopicConf() {
        return [
            'request.required.acks' => 0
        ];
    }

}
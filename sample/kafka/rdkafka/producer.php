<?php
/**
 * Created by IntelliJ IDEA.
 * User: mcatom
 * Date: 2019-05-22
 * Time: 17:56
 */
$conf = new RdKafka\Conf();
$conf->setDrMsgCb(function ($kafka, $message) {
//    file_put_contents("./dr_cb.log", var_export($message, true).PHP_EOL, FILE_APPEND);
});
$conf->setErrorCb(function ($kafka, $err, $reason) {
    file_put_contents("./err_cb.log", sprintf("Kafka error: %s (reason: %s)", rd_kafka_err2str($err), $reason).PHP_EOL, FILE_APPEND);
});

$rk = new RdKafka\Producer($conf);
$rk->setLogLevel(LOG_WARNING);
$rk->addBrokers("kafka-01:9092");




$cf = new RdKafka\TopicConf();
// -1必须等所有brokers同步完成的确认 1当前服务器确认 0不确认，这里如果是0回调里的offset无返回，如果是1和-1会返回offset
// 我们可以利用该机制做消息生产的确认，不过还不是100%，因为有可能会中途kafka服务器挂掉
$cf->set('request.required.acks', 0);
$topic = $rk->newTopic("login", $cf);

$mi = microtime(true);
for ($i = 0; $i < 300000; $i++) {
    $topic->produce(RD_KAFKA_PARTITION_UA, 0, "{\"game_id\":110,\"game_pkg\":\"game_pkg182\",\"partner_id\":23,\"uuid\":\"uuid-574226\",\"rt\":1558595492,\"flg_game_rt\":\"1009979\"}", '');
    if($rk->getOutQLen() > 5000) {
        $rk->poll(50);
    }
}
echo microtime(true) - $mi;

$len = $rk->getOutQLen();
while ($len > 0) {
    $len = $rk->getOutQLen();
    $rk->poll(50);
}
echo microtime(true) - $mi;


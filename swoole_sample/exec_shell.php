<?php
/**
 * Created by IntelliJ IDEA.
 * User: mcatom
 * Date: 2019-03-26
 * Time: 13:46
 */


//协程1
//$c = 10;
//while($c--) {
//    go(function () {
//        co::exec("sleep 2");
//        echo "xxx";
//    });
//}

//协程2
//Swoole\Runtime::enableCoroutine();
//go(function() {
//    sleep(1);
//    echo "a";
//});
//
//go(function() {
//    sleep(2);
//    echo 'b';
//});

//协程3
//Swoole\Runtime::enableCoroutine();
//class DeferTask{
//
//    private $tasks;
//
//    function addTask(callable $fn) {
//        $this->tasks[] = $fn;
//    }
//
//    public function __destruct() {
//
//        foreach ($this->tasks as $fn){
//            go($fn);
//        }
//    }
//
//}
//
//function tasks() {
//
//    $o = new DeferTask();
//    $o->addTask(function () {
//        sleep(3);
//        echo 'a';
//    });
//    $o->addTask(function (){
//        sleep(2);
//        echo "b";
//    });
//    $o->addTask(function (){
//        sleep(1);
//        echo 'c';
//    });
//
//}
//
//tasks();

//协程4
//class WaitGroup {
//
//    private $count = 0;
//    private $chan;
//
//    public function __construct() {
//        $this->chan = new Chan();
//    }
//
//    public function add() {
//        $this->count++;
//    }
//
//    public function done() {
//        $this->chan->push(true);
//    }
//
//    public function wait() {
//        while ($this->count-- ) {
//            $this->chan->pop();
//        }
//    }
//
//}
//
//go(function(){
//    $wg = new WaitGroup();
//    $result = [];
//
//    $wg->add();
//    go(function () use ($wg, &$result){
//        $cli = new \Swoole\Coroutine\Http\Client('www.taobao.com', 443, true);
//        $cli->setHeaders([
//            'Host' => 'www.taobao.com',
//            "User-Agent" => 'Chrome/49.0.2587.3',
//            'Accept' => 'text/html,application/xhtml+xml,application/xml',
//            'Accept-Encoding' => 'gzip',
//        ]);
//        $cli->set(['timeout' => 2]);
//        $cli->get('/index.php');
//
//        $result['1.0'] = $cli->body;
//        $cli->close();
//
//        $wg->done();
//    });
//
//    $wg->add();
//    //启动第二个协程
//    go(function () use ($wg, &$result) {
//        //启动一个协程客户端client，请求百度首页
//        $cli = new \Swoole\Coroutine\Http\Client('www.baidu.com', 443, true);
//        $cli->setHeaders([
//            'Host' => 'www.baidu.com',
//            "User-Agent" => 'Chrome/49.0.2587.3',
//            'Accept' => 'text/html,application/xhtml+xml,application/xml',
//            'Accept-Encoding' => 'gzip',
//        ]);
//        $cli->set(['timeout' => 2]);
//        $cli->get('/index.php');
//
//        $result['2.0'] = $cli->body;
//        $cli->close();
//
//        $wg->done();
//    });
//    $wg->wait();
//    var_dump($result);
//});

//协程5
go(function() {
    go(function () {
        co::sleep(1.0);
        go(function () {
            co::sleep(2.0);
            echo "co[3] end\n";
        });
        echo "co[2] end\n";
    });

    co::sleep(3.0);
    echo "co[1] end\n";
});
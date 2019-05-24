# file_monitor

这是php版本的flume，实时监听文件变化，并且把变化内容读出来写入到指定的存储介质

## 环境要求
1. php7.0+
    * swoole 4.0+
    * inotify
2. 目标是kafka，需要安装rdkafka扩展

## 配置
    config/YakConfMonitor.php
配置如下
``` php
return [
    'test' => [
        'logs' => [
            'login' => [
                'path' => '监控目录',  //例如：/data/log
                'storage' => '数据流向',    //例如：kafka,file,conlie
            ],
        ]
        . . .
    ],
];
```

## 运行
    php run.php "monitor_name=test"

## 重点目录介绍
1. modules  数据流向指定
    * storage/kafka  写kafka的类
    * storage/file   写文件的类
    * storage/console 直接打印
    * 可以自己添加storage
---
title: redis消息队列
date: 2020-04-14 15:30:47
tags:
- redis
- php
categories:
- redis
- php
keywords:
- redis消息队列
- php redis消息队列
---

> 模拟多用户同时访问
```php
<?php
//redis数据入队操作
$redis = new Redis();
$redis->connect('127.0.0.1',6379);
for($i=0;$i<5000;$i++){
    try{
        $redis->LPUSH('data',$i);
    }catch(Exception $e){
        echo $e->getMessage();
    }
}
```

> 出列操作,模拟业务,使用`php index.php`守护执行

```php
<?php
//redis数据出队操作
$redis = new Redis();
$redis->pconnect('127.0.0.1',6379);
while(true){
    try{
        $value = $redis->LPOP('data');
        if($value){
            var_dump($value)."\n";
        }
        /*
         *  利用$value进行逻辑和数据处理
         */
    }catch(Exception $e){
        echo $e->getMessage();
    }
}

```


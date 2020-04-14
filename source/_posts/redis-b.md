---
title: redis并发小测试
date: 2020-04-14 15:30:47
tags:
- redis
- php
categories:
- redis
- php
keywords:
- redis并发小测试
- php redis并发小测试
---

> 模拟多用户同时访问
```php
<?php
//redis数据入队操作
$redis = new Redis();
$redis->connect('127.0.0.1',6379);
for($i=0;$i<5000;$i++){
    try{
        $redis->LPUSH('click',$i);
    }catch(Exception $e){
        echo $e->getMessage();
    }
}
```

> 出列操作,模拟业务

```php
<?php
$redis = new Redis();
$redis->pconnect('127.0.0.1',6379);
while(true){
    try{
        $value = $redis->LPOP('click');
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


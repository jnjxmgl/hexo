---
title: redis秒杀抢购并发核心代码及测试
date: 2020-04-22 09:24:17
tags:
- redis
- php
categories:
- redis
- php
keywords:
- redis秒杀抢购
- php 秒杀抢购并发核心代码
- php实现秒杀
- php抢购实现
- 秒杀抢购核心代码
---
>核心代码

如下:

```php
<?php

try {

    $redis = new Redis();
    $redis->connect('127.0.0.1', 6379);
    //   $uid=$this->uid;//获取用户的id
    $uid = $_GET['id'];//因为我没有模拟登陆,所以用户id使用get传递以下,实际场景下可已使用上面的类似代码获取
    $count = $redis->LLEN('data');
    if ($count >= 100) {//查看秒杀请求是否满了,满了提示
        echo ('100个了');
        die;
    }
    $redis->LPUSH('data', $uid);//进来一个秒杀请求
 

  
    //TODO  成功进来的请求走后续逻辑挨个出列,如创建订单
    echo ($uid.'  开始创建订单了');

} catch (Exception $e) {
    echo $e->getMessage();
}

```

>讲解

#### 1. redis的原子性

redis之所以能够用做并发处理的中间件,很大程度上是得益于它的IO操作是原子性的,那么redis的原子性到底是什么??

原子性是数据库的事务中的特性。在数据库事务的情景下，原子性指的是：`一个事务（transaction）中的所有操作，要么全部完成，要么全部不完成，不会结束在中间某个环节`。

对于Redis而言，命令的原子性指的是：`一个操作的不可以再分，操作要么执行，要么不执行`。

Redis操作原子性的原因

Redis的操作之所以是原子性的，是因为Redis是`单线程`的。

由于对操作系统相关的知识不是很熟悉，从上面这句话并不能真正理解Redis操作是原子性的原因，进一步查阅进程与线程的概念及其区别。

 

#### 2. 进程与线程

`2.1 进程`

计算机中已执行程序的实体。比如，一个启动了的php-fpm，就是一个进程。

`2.2 线程`

操作系统能够进行运算调度的最小单元。它被包含在进程之中，是进程的实际运作单位。一条线程指的是进程中一个单一顺序的控制流，一个进程中可以并发多个线程，每条线程并行执行不同的任务。比如，mysql运行时，mysql启动后，该mysql服务就是一个进程，而mysql的连接、查询的操作，就是线程。


#### 3. 进程与线程的区别

资源（如打开文件）：`进程间的资源相互独立，同一进程的各线程间共享资源。某进程的线程在其他进程不可见`。

通信：进程间通信：`消息传递、同步、共享内存、远程过程调用、管道。线程间通信：直接读写进程数据段（需要进程同步和互斥手段的辅助，以保证数据的一致性）`。

调度和切换：`线程上下文切换比进程上下文切换要快得多`。

线程，是操作系统最小的执行单元，在单线程程序中，任务一个一个地做，必须做完一个任务后，才会去做另一个任务。


#### 4. 代码含义

从上面的代码中可以看出,当请求过来以后我们判断队列的请求数是否溢出,溢出的话,会提示:

`秒杀商品不足,已结束`

等等用户友好提示;如过人数没有溢出,就将其放入redis的队列,继续执行创建订单及付款的操作

>测试脚本

**下面我就用图文的方式简单的测试下我们刚刚的业务脚本,这里为了方便测试,我将总的秒杀数量改为 `5` 个(如下),模拟100用户同时在1s内同时发起请求的场景,因为实际场景会比我们测试的要复杂的多,所以结果仅供参考**



```php
<?php

try {

    $redis = new Redis();
    $redis->connect('127.0.0.1', 6379);
    //   $uid=$this->uid;//获取用户的id
    $uid = $_GET['id'];//因为我没有模拟登陆,所以用户id使用get传递以下,实际场景下可已使用上面的类似代码获取
    $count = $redis->LLEN('data');
    if ($count >= 5) {//查看秒杀请求是否满了,满了提示
        echo ('5个,够了');
        die;
    }
    $redis->LPUSH('data', $uid);//进来一个秒杀请求
 
    //TODO  成功进来的请求走后续逻辑挨个出列,如创建订单
    echo ($uid.'  开始创建订单了');

} catch (Exception $e) {
    echo $e->getMessage();
}

```

1. 首先将脚本php放入网站运行根目录

2. 我用的`apache-jmeter`做测试,这个基于java的测试工具个人觉得还是挺好用的,下面附上我自己保存的链接地址,需要的话可已自行下载;
当然百度一下去官网下载更好,因为可已体验最新的

apache-jmeter下载地址: https://res.imgl.net/hexo/redisbingfaceshi/apache-jmeter-5.2.1.zip

3. 创建测试用户数据,这里我使用excel预先处理好了模拟不同的用户id请求,如下图:

![创建用户](https://res.imgl.net/hexo/redisbingfaceshi/Snap1.png '创建用户')

附件: https://res.imgl.net/hexo/redisbingfaceshi/test.csv

4. 使用jmeter,操作如下图

![使用](https://res.imgl.net/hexo/redisbingfaceshi/Snap2.png '使用')
![切换简体中文](https://res.imgl.net/hexo/redisbingfaceshi/Snap3.png '切换简体中文')
![创建并发计划](https://res.imgl.net/hexo/redisbingfaceshi/Snap10.png '创建并发计划')
![创建http请求](https://res.imgl.net/hexo/redisbingfaceshi/Snap5.png '创建http请求')
![CSV数据文件设置](https://res.imgl.net/hexo/redisbingfaceshi/Snap6.png 'CSV数据文件设置')
![查看结果树](https://res.imgl.net/hexo/redisbingfaceshi/Snap7.png '查看结果树')
![汇总报告](https://res.imgl.net/hexo/redisbingfaceshi/Snap8.png '汇总报告')
![聚合报告](https://res.imgl.net/hexo/redisbingfaceshi/Snap9.png '聚合报告')


最后点击`创建并发计划`图示的地方开始,附我创建计划保存的配置,直接使用jmeter打开就行

https://res.imgl.net/hexo/redisbingfaceshi/test.jmx

5. 查看结果树,如下图:

![正常下单](https://res.imgl.net/hexo/redisbingfaceshi/Snap11.png '正常下单')
![溢出了](https://res.imgl.net/hexo/redisbingfaceshi/Snap12.png '溢出了')
![查看redis中的数据](https://res.imgl.net/hexo/redisbingfaceshi/Snap13.png '查看redis中的数据')


从上面我们可以看出有的正常走了下单逻辑,有的请求就提示已经够数了,我数了一下,确实发起了100个请求,也只有5个是走下面逻辑的

到此,对redis这块的讲解就完成了



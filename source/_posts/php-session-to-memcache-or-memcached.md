---
title: 将PHP的session存储到memcache或者memcached
date: 2020-07-27 09:30:04
tags:
- php
- memcache
- memcached
categories:
- php
keywords:
- 将PHP的session存储到memcache
- 将PHP的session存储到memcached
- 设置php的配置文件,将session设置为memcache
---

目前已知memcache 和 memcached 不完全是一个东西,但两者的差异也并不是很大,毕竟memcached只是memcache的升级

> 操作方法(memcache篇)

打开php的配置文件php.ini,搜索`"session.save_handler"`关键词,找到如下位置

    [Session]
    ; Handler used to store/retrieve data.
    ; http://php.net/session.save-handler
    session.save_handler = files

将上面的`"files"`修改为`"memcache"`

再次搜索关键词`"session.save_path"`,找到如下位置

    session.save_path = "/tmp"

修改为

    session.save_path = "tcp://127.0.0.1:11211"

如果你的redis建立在内网或者公网服务器,你需要将`"127.0.0.1"`修改为相应的公网或内网的ip地址

> 操作方法(memcached篇)

打开php的配置文件php.ini,搜索`"session.save_handler"`关键词,找到如下位置

    [Session]
    ; Handler used to store/retrieve data.
    ; http://php.net/session.save-handler
    session.save_handler = files

将上面的`"files"`修改为`"memcached"`

再次搜索关键词`"session.save_path"`,找到如下位置

    session.save_path = "/tmp"

修改为

    session.save_path = "127.0.0.1:11211"

如果你的redis建立在内网或者公网服务器,你需要将`"127.0.0.1"`修改为相应的公网或内网的ip地址


> 总结

两者的配置主要区别在于`"save_handler"` 的参数必须为`"['memcache','memcached']"`中的一个,不能将`"memcache"`的配置应用到memcached,反之亦然

还有就是`"memcached"`不需要在设置`"save_path"`时指定`"tcp://"`


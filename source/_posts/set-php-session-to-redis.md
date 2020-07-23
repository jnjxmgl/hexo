---
title: 将PHP的session存储到redis
date: 2020-07-23 10:47:55
tags:
- php
- redis
categories:
- php
- redis
keywords:
- 将PHP的session存储到redis
---

> 操作方法

打开php的配置文件php.ini,搜索`"session.save_handler"`关键词,找到如下位置

    [Session]
    ; Handler used to store/retrieve data.
    ; http://php.net/session.save-handler
    session.save_handler = files

将上面的`"files"`修改为`"redis"`

再次搜索关键词`"session.save_path"`,找到如下位置

    session.save_path = "/tmp"

修改为

    session.save_path = "tcp://127.0.0.1:6379"

如果你的redis建立在内网或者公网服务器,你需要将`"127.0.0.1"`修改为相应的公网或内网的ip地址
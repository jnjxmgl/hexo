---
title: 在windows server 2019 中设置开机启动
date: 2020-07-22 09:55:53
tags:
- windows server
categories:
- windows
keywords:
- 在windows server 2019 中设置开机启动
---

windows server 2012以下的windows版本中(包括非windows server 系列),可已通过开始菜单中的`"启动"`文件夹里创建快捷方式来达到开机启动的目的.

但是在2016/2019等版本系统中,在开始菜单中找不到`"启动"`，如果写了个bat批处理文件或者希望执行一个exe，如何能开机启动呢？可以打开文件资源管理器，把下面的位置粘贴到地址栏后回车。将bat文件或者他的快捷方式粘贴到文件夹里就可以了。

    C:\ProgramData\Microsoft\Windows\Start Menu\Programs\StartUp
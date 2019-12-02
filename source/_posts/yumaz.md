---
title: yum 使用指定源安装软件包
date: 2019-07-14 14:26:07
tags: centos7
categories: linux
---

有些时候我们在安装相应的源以后, 直接使用命令安装源中的软件, 会提示软件包不存在, 这种情况下
代码如下:

    [root@imgl ~]# yum install nginx --enablerepo=epel

稍微解释一下：
代码如下:
yum install XXX --enablerepo=YYY
XXX是要安装的软件，YYY是repo源的名字。
建议安装的时候尽量选择同一个源。
因为不同的源安装的软件可能会有冲突。

***引用源***
***作者：在马其顿的街角***
***链接：(https://www.jianshu.com/p/c22faafc7b8b)***
***来源：简书***


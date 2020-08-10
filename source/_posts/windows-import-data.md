---
title: 在windows导入mysql数据脚本
date: 2020-08-05 08:40:02
tags:
- mysql
categories:
- mysql
keywords:
- 在windows导入mysql数据脚本
---

windows下有很多数据库导入导出的工具,用他们来导入数据脚本着实方便;不过个版本之间的导入算法和方式不近相同,因此常常出现`A工具`导出的数据只能由`A工具`导入,如果使用其他的导入工具难免会出错

鉴于此,这里介绍一下使用`mysqldump`命令导出的数据库脚本如何在windows下导入

很多人知道linux系统下的导入方法,直接在终端(mysql运行目录要设置环境变量或者到`/usr/local/bin`等能直接访问的地方),格式如下

    mysql -u root -p [数据库] < 数据库.sql


例:

    mysql -u root -p test < test.sql

`sql脚本文件的名字不一定和数据库名称一致,以下介绍的windows下导入也是一样,但默认情况下使用mysqldump导出的数据库脚本文件名和数据库一致`


> 在windows导入

首先如果你没有设置环境变量到mysql的运行目录,你可以使用`cd`命令到mysql的`bin`目录,如果你设置了可已忽略


然后在cmd控制台或者powershell终端下执行如下命令

    mysql -u root -p 

回车,右键黏贴你复制的密码,登陆到mysql

然后使用

    create database [数据库];
    
创建数据库,如 

    create database test;

然后使用

    use test;

切换数据库

最后执行 

    source test.sql

`test.sql`就是我们导出的数据库脚本,等待执行完毕即可


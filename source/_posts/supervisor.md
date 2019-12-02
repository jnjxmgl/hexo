---
title: SUPERVISORD简介，配置及使用
date: 2019-07-14 14:26:07
tags:
- supervisor
- centos7
categories: linux
---

**centos7安装**

    yum install supervisor

**生成配置文件**

    echo_supervisord_conf > /etc/supervisord.conf

**【启动supervisord】**

    supervisord

【停止supervisord】

    supervisorctl shutdown

**【重新加载配置文件】**

    supervisorctl reload

**【进程管理】**

启动supervisord管理的所有进程

    supervisorctl start all

停止supervisord管理的所有进程

    supervisorctl stop all

启动supervisord管理的某一个特定进程

    supervisorctl start program-name // program-name为[program:xx]中的xx

停止supervisord管理的某一个特定进程

    supervisorctl stop program-name // program-name为[program:xx]中的xx

重启所有进程或所有进程

    supervisorctl restart all // 重启所有

    supervisorctl reatart program-name // 重启某一进程，program-name为[program:xx]中的xx

查看supervisord当前管理的所有进程的状态

    supervisorctl status

开机启动

    supervisorctl enable supervisor

**【遇到问题及解决方案】**
在使用命令supervisorctl start all启动控制进程时，遇到如下错误

    unix:///tmp/supervisor.sock no such file

> 出现上述错误的原因是supervisord并未启动，只要在命令行中使用命令sudo supervisord启动supervisord即可, 或者重启服务器


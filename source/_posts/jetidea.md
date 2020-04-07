---
title: JETBRAINS家族IDEA授权服务器搭建
date: 2019-07-14 15:13:41
tags: 
- jetbrains
- centos7
categories: linux
keywords:
- JETBRAINS
- IDEA授权服务器
- IDEA授权服务器搭建
- 1017
---

首先安装supervisor, 此步骤略过, 在centos7使用命令

    yum install supervisor -y

增加开机启动

    systemctl enable supervisord

supervisord 的配置 文件是 /etc/supervisord.conf

打开

vi /etc/supervisord.conf

添加以下内容

    [program:idea-server]

    command=/home/IdeaServer -p 1024

    autostart=true

    autorestart=true

    startsecs=3

注: IdeaServer 文件是许可文件, 需要具备执行权限, 添加执行权限

    chmod +x IdeaServer

重启supervisord , 命令

    supervisorctl reload

让配置生效

nginx代理:

    server
    {

        listen 80; 

        server_name idea.test.com; 

        location / {

            proxy_pass http://127.0.0.1:1017;

            proxy_redirect off;

        }
    }


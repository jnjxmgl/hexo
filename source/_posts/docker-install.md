---
title: 在linux安装docker
date: 2019-10-17 09:03:27
tags:
- docker
- centos7
categories: linux
---
> 前提条件

Docker 要求 linux 系统的内核版本高于 3.10 ，查看本页面的前提条件来验证你的 linux 版本是否支持 Docker。

通过 uname -r 命令查看你当前的内核版本

    uname -r

> 使用脚本安装 Docker

    wget -qO- https://get.docker.com/ | sh

> 启动docker 后台服务

    systemctl start docker #centos6使用 service docker start 

>测试运行hello-world

    docker run hello-world

> 镜像加速

鉴于国内网络问题，后续拉取 Docker 镜像十分缓慢，我们可以需要配置加速器来解决，我使用的是网易的镜像地址：http://hub-mirror.c.163.com。

新版的 Docker 使用 /etc/docker/daemon.json（Linux） 或者 %programdata%\docker\config\daemon.json（Windows） 来配置 Daemon。

请在该配置文件中加入（没有该文件的话，请先建一个）：

    {
        "registry-mirrors": ["http://hub-mirror.c.163.com"]
    }
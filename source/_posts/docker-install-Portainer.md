---
title: docker安装Portainer
date: 2019-10-17 09:44:58
tags: 
- docker
- centos7
categories: linux
keywords:
- 什么是Portainer？
- Swarm集群和服务
- 下载Portainer镜像
- 运行Portainer

---
一、什么是Portainer？

Portainer是Docker的图形化管理工具，提供状态显示面板、应用模板快速部署、容器镜像网络数据卷的基本操作（包括上传下载镜像，创建容器等操作）、事件日志显示、容器控制台操作、Swarm集群和服务等集中管理和操作、登录用户管理和控制等功能。功能十分全面，基本能满足中小型单位对容器管理的全部需求。

二、下载Portainer镜像

环境用的还是linux的环境，在linux安装Portainer来管理docker集群,执行命令:
```shell
docker search portainer #查询当前有哪些Portainer镜像

```
![镜像列表](http://q8ch2wiw7.bkt.clouddn.com/hexo/docker-install-Portainer/20180527154419885.jpg "镜像列表")

```shell
docker pull portainer/portainer #下载镜像

```

![下载镜像](http://q8ch2wiw7.bkt.clouddn.com/hexo/docker-install-Portainer/20180527154655380.jpg "下载镜像")

三、运行Portainer

如果仅有一个docker宿主机，则可使用单机版运行，运行以下命令就可以启动了:
```shellhexo
    docker run -d -p 9000:9000 \
    --restart=always \
    -v /var/run/docker.sock:/var/run/docker.sock \
    --name prtainer-test \
    portainer/portainer
```
![执行命令](http://q8ch2wiw7.bkt.clouddn.com/hexo/docker-install-Portainer/20180527154855363.jpg "执行命令")

该语句用宿主机9000端口关联容器中的9000端口，并给容器起名为portainer-test。启动成功后，使用该机器IP:PORT即可访问Portainer。

首次登陆需要注册用户，给admin用户设置密码：

![设置密码](http://q8ch2wiw7.bkt.clouddn.com/hexo/docker-install-Portainer/20180527155149430.jpg "设置密码")

单机版这里选择local即可.

设置完成密码登录进去首页

![首页](http://q8ch2wiw7.bkt.clouddn.com/hexo/docker-install-Portainer/20191017104254.png "首页")

---
title: mariadb数据库集群配置
date: 2020-02-01 11:22:10
tags: 
- mysql
- centos7
categories: SQL
keywords:
- mariadb
- mariadb数据库
- mariadb数据库集群
- mariadb数据库集群配置
- 安装ius和epel源
- mariadb-galera
- wsrep_cluster_address
- galera_new_cluster
- wsrep_cluster_size

---
> 先决条件

两到三个运行mariadb的服务器,这里使用192.168.91.129,192.168.91.130,192.168.91.131

`安装ius和epel源`

在centos7上执行命令

```shell
yum install https://repo.ius.io/ius-release-el7.rpm https://dl.fedoraproject.org/pub/epel/epel-release-latest-7.noarch.rpm -y
```

安装完成后请自行配置国内源

`安装mariadb及mariadb-galera`

在centos7上执行命令

```shell
yum install mariadb103-server.x86_64 mariadb103-server-galera.x86_64 -y
```

`关闭防火墙及selinux`

```shell
systemctl stop firewalld #如果有特殊需求,galera模式下使用4567端口,需要同时开启UDP和TCP的4567端口
```

`vi /etc/selinux/config`

```shell
SELINUX=disabled
```

> 配置

进入目录`/etc/my.cnf.d` , vi 编辑 `galera.cnf`文件,将文件中的

```shell
# Group communication system handle
#wsrep_cluster_address="dummy://"
```

改为

```shell
# Group communication system handle
wsrep_cluster_address="gcomm://192.168.91.129,192.168.91.130,192.168.91.131"
```

>启动

在三个服务器中的任何一个中,执行命令启动

```shell
galera_new_cluster
```

剩下的两个使用

```shell
systemctl start mariadb
```

>查看结果

登陆任何一台mariadb服务器

查看一下全局变量`wsrep_cluster_size`对应的值是`3`就对了

下面可已在任意一台mariadb中创建数据库看下,其余两个是不是都有了

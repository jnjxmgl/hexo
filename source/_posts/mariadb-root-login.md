---
title: ubuntu系统mariadb解决远程访问以及root登录
date: 2020-05-06 15:53:25
tags:
- mariadb
- mysql
categories:
- mariadb
- mysql
- ubuntu
- debian
keywords:
- mariadb远程访问
- mariadb用root登录
- mysql远程访问
- mysql用root登录
---


> 修改/etc/mysql/my.conf

找到`bind-address = 127.0.0.1`这一行
直接#掉或者改为`bind-address = 0.0.0.0`即可

> 为需要远程登录的用户赋予权限

1. 新建用户远程连接mysql数据库
```sql

grant all on *.* to admin@'%' identified by '123456' with grant option;

flush privileges;
```
允许任何ip地址(`%表示允许任何ip地址`)的电脑用admin帐户和密码(123456)来访问这个mysql server。

**注意admin账户不一定要存在。**

2. 支持root用户允许远程连接mysql数据库

```sql
grant all privileges on *.* to 'root'@'%' identified by '123456' with grant option;

flush privileges;
```
---
title: 关于ubuntu使用galera3创建mariadb集群出现的错误
date: 2020-05-13 15:50:32
tags:
- mysql
- mariadb
- galera
categories:
- sql
- galera
- ubuntu
keywords:
- ubuntu搭建mysql集群无法启动
---
>安装

    apt install mariadb-server galera-3 galera-arbitrator-3 

>修改`/etc/mysql/mariadb.conf.d/50-server.cnf`配置

    [mysqld]
    bind-address            = 0.0.0.0
    [mariadb]
    wsrep_provider=/usr/lib/libgalera_smm.so
    wsrep_cluster_address="gcomm://192.168.6.128,192.168.6.129"
    binlog_format=ROW
    default_storage_engine=InnoDB
    innodb_autoinc_lock_mode=2
    innodb_doublewrite=1
    query_cache_size=0
    wsrep_on=ON

>坑坑坑坑坑

错误提示包含如下:

    /usr/bin/galera_recovery: 71: cannot create /tmp/wsrep_recovery.HrmnwL: Permission denied


纳尼?

对`/tmp`文件夹无权限?

使用  `ll /`

    dr-xr-xr-x  13 root root          0 May 13 06:10 sys/
    drwxrwxrwt  12 root root       4096 May 13 08:06 tmp/
    drwxr-xr-x  14 root root       4096 Apr 23 07:34 usr/
    drwxr-xr-x  13 root root       4096 Apr 23 07:35 var/


可已看到 /tmp 目录的权限后面多了一个 t , 什么情况?

使用 `stat /tmp`

    jnjxmgl@imgl:/var/log/mysql$ stat /tmp/
    File: /tmp/
    Size: 4096            Blocks: 8          IO Block: 4096   directory
    Device: 802h/2050d      Inode: 1179650     Links: 12
    Access: (1777/drwxrwxrwt)  Uid: (    0/    root)   Gid: (    0/    root)
    Access: 2020-05-13 08:07:24.566439125 +0000
    Modify: 2020-05-13 08:06:06.304104497 +0000
    Change: 2020-05-13 08:06:06.304104497 +0000
     Birth: -

1777 是啥? 不是 0777么?

以下摘自网络

    怎么在777前还有一位，颠覆了我的认知啊，这时候必须翻鸟哥神书了，找到一个链接《7.4.3 文件特殊权限：SUID/SGID/Sticky Bit》，啃了一会终于明白了，整理如下：

    除了传统的读r、写w、执行x以外，还有Linux的文件特殊权限，他们分别是Set UID、Set GID、Sticky Bit三种，也就是多出来的那一位，功能介绍如下：
    Set UID，SUID
    权值：4
    符号：x --> s
    特点：仅对可执行文件有效。
    功能：可执行文件执行时，拥有文件所有者的权限。
    案例：/usr/bin/passwd 权限为4755，普通用户可执行passwd命令时，对应的普通用户，随机秒变高富帅，获得了root权限，可以修改普通用户平常根本想都不敢想、无法修改的root拥有的/etc/shadow系统文件（如果/usr/bin/passwd 权限为755，则普通用户执行passwd的时候，会出现无权限修改root own的/etc/shadow文件的问题）
    Set GID，SGID
    权值：2
    符号：x --> s
    特点：文件、目录都可施法。
    功能：可执行文件、目录执行时，相同用户组的 拥有文件所有者权限。
    案例：SGID多用在特定的多人团队的项目开发上，在系统中用得较少
    Sticky Bit，SBit
    权值：1
    符号：x --> t
    特点：仅对目录有效。
    功能：当目录SBit=1，权限变为rwx rwx rwt时，在此文件夹下删除、重命名、移动的操作只允许是对应创建者用户或root（如果SBit=0，则用户间创建的文件可以互相删除，互相伤害）
    案例：/tmp 权限为1777，该目录下不同用户间不可互删文件，只能删自己的（如果/test 权限为777，则test目录下不同用户间可互删文件）
    【Linux文件特殊权限“隐藏关卡”】
    看到这里，大家应该明白了SUID SGID StickyBit均含有类似“设置后就有了创建者相应权限”的功能（可能不严谨，大家明白意思就好），那么当创建者也没有执行权限（x位为0，例如rw-rw-rw-）时，那么就会出现暗藏关卡——大写SST，即rwSrwSrwT，代表空权限。就像鸟哥书里说的那样“拥有者都无法执行了，哪里来的权限给其他人使用呢？当然就是空的”*

摘自网络结束

根据我个人的理解:

 /tmp `是允许随便创建  但是不允许随便删除呀`

集群启动,使用命令`galera_new_cluster`(需要root权限)的时候,root用去启动mysql,这一步没问提
然后后面的事情,就跟root没关系了,后面的就交给了mysql用户,mysql用户去/tmp创建`/tmp/wsrep_recovery.HrmnwL`(点后面的字符串是随机的)的时候没有问题,
然后这个逼想毁尸灭迹,想把写的东西删了,就触发了权限问题,没删除成功,然后mariadb就提示出错了

>解决办法

直接`chmod 777 /tmp` 最直接,当然也有别的办法,请自行研究.

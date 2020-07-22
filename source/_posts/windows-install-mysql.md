---
title: 在windows系统中安装mysql数据库
date: 2020-07-22 10:02:17
tags:
- mysql
- windows
categories:
- mysql
keywords:
- 在windows系统中安装mysql数据库
---

在windows中没有想linux那样又源站,可以直接执行命令安装mysql,下面就介绍以下如何在windows系统中安装mysql


> 准备工作

1. 去华为镜像源去下载windows系统下的mysql离线安装包,我这里下载mysql最新的5.7.31,如下图:


![下载](https://res.imgl.net/hexo/windows-install-mysql/2aa78771b5766f5f8be0443c5538fee.png "下载")

 将下载好的zip压缩包解压到指定目录,为了方便,我这里解压到了C盘下的mysql文件夹

2. 在windows中为mysql的bin目录设置`环境变量`,在`系统变量`-`Path`变量值中增加一个mysql的运行目录,因为Path变量值不止存在一个路径信息,因此你添加的时候,在最后面输入内容应该和下面类似

    ;c:\mysql\bin

要特别注意前面存在一个英文半角的分号,这个不能忽略

3. 再mysql目录下(注意`不是bin`目录)创建`my.ini`,文件名必须一致,然后用notepad++或者其他二进制文本编辑器打开,录入如下内容:


    [mysqld]
    port = 3306

    basedir=C:\mysql

    datadir=C:\mysql\data

    max_connections=200

    character-set-server=utf8

    default-storage-engine=INNODB

    [mysql]

    default-character-set=utf8


> 安装

打开cmd控制台或者powershell , 执行如下命令:

    mysqld -install

然后紧接着执行安装后的初始化命令

    mysqld --initialize

初始化完成以后,会在data的目录中生成一个以计算机名称为名称,后缀名为`.err`为后缀的文件,用二进制文本编辑器打开,搜索如下类似的一句英文

    A temporary password is generated for root@localhost: raLk,bdq70pi

后面的`root@localhost:` 跟这的就是我们的临时密码

然后我们打开powershell (cmd也可以) ,输入如下命令:

    mysql -u root -p 

回车,然后复制上面提到的临时密码,然后`右键`黏贴,再次回车,看到如下页面证明我们登陆成功

![登陆](https://res.imgl.net/hexo/windows-install-mysql/7fb67c6b63c983b8f668678f639fa76.png "登陆")


> 配置

由于使用的是临时密码,我们无法引用与项目使用中,可已在登陆mysql以后使用如下命令重新设置新密码:

    ALTER USER USER() IDENTIFIED BY 'shabime';

因为我已经设置了,这里不做演示,以后我们就可以使用新的密码登陆了,其他的一些需要链接mysql的项目也可以相应的使用该密码

到此mysql的离线安装方式就结束了,当然在图一的时候你也可以下载msi形式的安装包

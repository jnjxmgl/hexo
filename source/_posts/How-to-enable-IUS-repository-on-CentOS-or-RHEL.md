---
title: centos7下使用ius源
date: 2019-11-06 13:39:21
tags: 
- centos7
- ius
categories: linux
keywords:
- 使用ius源
- centos7下使用ius源
- ius-release


---
> 设定

要在系统上启用IUS存储库，请安装ius-release 软件包。该软件包包含IUS存储库配置和公共软件包签名密钥。许多IUS软件包都具有EPEL存储库中的依赖项 ，因此也请安装epel-release软件包。


#### RHEL / CentOS 7
```
yum install https://repo.ius.io/ius-release-el7.rpm 
yum install https://dl.fedoraproject.org/pub/epel/epel-release-latest-7.noarch.rpm #如果你事先已经安装过了,无需重复安装
```

> 配置ius为阿里云的源站地址

进入配置目录
```
cd /etc/yum.repos.d
```
使用`'ll'`命令可以看到有三个ius源的配置

    -rw-r--r--. 1 root root  669 5月   2 2019 ius-archive.repo
    -rw-r--r--. 1 root root  591 5月   2 2019 ius.repo
    -rw-r--r--. 1 root root  669 5月   2 2019 ius-testing.repo

执行如下命令:

    sed 's/repo.ius.io/mirrors.aliyun.com\/ius/g' ./ius.repo -i
    sed 's/repo.ius.io/mirrors.aliyun.com\/ius/g' ./ius-archive.repo -i
    sed 's/repo.ius.io/mirrors.aliyun.com\/ius/g' ./ius-testing.repo -i

到此,ius源安装配置已完成

阿里云也有`epel`,如果想更换成阿里云的源站,配置类似

附阿里云镜像站地址:https://opsx.alibaba.com/mirror?lang=zh-CN 

上面有详细的配置过程,如您已使用,请忽略本提示
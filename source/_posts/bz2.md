---
title: 解压BZ2文件错误
date: 2019-07-14 14:17:11
tags: centos7
categories: linux
---

> 错误信息

    [root @izwz9i1fkp1z7n0olwliafz~]# tar -jxvf test.tar.bz2
    tar(child): bzip2: Cannot exec: No such file or directory
    tar(child): Error is not recoverable: exiting now
    tar: Child returned status 2
    tar: Error is not recoverable: exiting now

> 系统未安装 bzip2 工具包

    yum -y install bzip2


---
title: 如何从Linux上的RPM软件包中提取文件
date: 2019-11-01 11:20:50
tags: centos7
categories: linux
keywords:
- 列出RPM软件包
- 列出RPM软件包中的文件
- repoquery
- yum-utils
- 如何从Linux上的RPM软件包中提取文件

---

从某个地方下载了一个 RPM 文件（epel-release-latest-8.noarch.rpm），我想从 RPM 软件包中手动提取文件。有没有一种无需安装就可以从 RPM 软件包中提取文件的简便方法？

RPM 软件包包含一组文件，通常是编译后的软件二进制文件，库及其开发源文件。这些文件以 cpio 存档格式打包，最后与所有必需的特定于包的元数据一起包装在 RPM 文件中。

您可以`使用rpm或repoquery命令查看RPM软件包的内容`。

如果要**从 RPM 软件包中提取文件而不安装它**，请按以下步骤操作：首先从 RPM 文件中获取 cpio 归档文件，然后从 cpio 归档文件中提取实际文件。

下面介绍如何从 Linux 命令行实现此目的。

> 安装必要的工具

首先，安装必要的命令行工具。

在`Ubuntu`，`Debian`或`Linux Mint`上：

    sudo apt-get install rpm2cpio

在`CentOS`，`Fedora`或`RHEL`上：

    yum install rpm


从RPM包中提取文件

安装必要的工具后，请按照以下步骤操作。这会将*epel-release-latest-8.noarch.rpm*中的所有文件提取到当前目录中。

    rpm2cpio foo.rpm | cpio -idmv

该`rpm2cpio`命令从一个RPM文件`cpio`归档和的cpio从cpio归档命令提取文件。

`cpio`命令使用的选项描述如下。

- -i：解压缩文件
- -d：创建目录
- -m：保留修改时间
- -v：详细

![epel-release-latest-8.noarch.rpm](https://res.imgl.net/hexo/How-to-extract-files-from-an-RPM-package-on-Linux/20191101113834.png "epel-release-latest-8.noarch.rpm")
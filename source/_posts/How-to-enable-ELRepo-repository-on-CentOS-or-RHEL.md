---
title: 如何在CentOS或RHEL上启用ELRepo存储库
date: 2019-10-31 15:52:52
tags: 
- centos7
- ELRepo
categories: linux
---
尽管CentOS / RHEL平台维护着几个正式的存储库（e.g., base, updates, contrib），但是在软件覆盖范围和发行周期方面却缺乏。为了弥合可用资源与常用需求之间的鸿沟，创建了第三方存储库来满足社区对常用软件包的需求。

用于CentOS / RHEL的此类第三方软件存储库之一是ELRepo（社区企业Linux存储库）。由于具有很高的维护质量，ELRepo实际上是少数“经过社区批准”的存储库之一。

> 什么是ELRepo储存库？

ELRepo是众所周知的CentOS / RHEL的硬件/设备驱动程序和主线内核映像的存储库。该存储库包含大量用于文件系统，图形卡，网卡，声卡，网络摄像头和显示器的最新驱动程序。如果您想增强基于CentOS / RHEL的企业Linux环境中各种硬件设备的使用体验，建议启用ELRepo存储库。ELRepo包含以下四个通道，您可以分别启用它们。

* elrepo：提供CentOS / RHEL发行版中未包含的软件包。默认启用。
* elrepo-kernel：提供LTS内核和最新的稳定内核。默认禁用。
* elrepo-extras：提供替换或更新CentOS / RHEL发行版的软件包。默认禁用。
* elrepo-testing：提供将很快移至elrepo的软件包。默认禁用。

> 在CentOS / RHEL上启用ELRepo存储库

要设置ELRepo存储库，您需要导入其官方GPG密钥，然后按如下所示安装ELRepo RPM。
在CentOS / RHEL 7上：
```
sudo rpm --import https://www.elrepo.org/RPM-GPG-KEY-elrepo.org  
sudo rpm -Uvh https://www.elrepo.org/elrepo-release-7.0-4.el7.elrepo.noarch.rpm

```
> 检查ELRepo存储库的内容
```
yum repolist enabled
```
{% asset_img 20191031161312.png 检查ELRepo存储库的内容 %}

但是个别时候,你需要显示指定使用`ELRepo`源,如下:
```
sudo yum --enablerepo=elrepo-kernel install kernel-ml
```
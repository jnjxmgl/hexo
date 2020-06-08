---
title: 更新Debian服务器
date: 2020-06-08 11:39:20
tags:
- debian
categories:
- linux
keywords:
- 更新Debian服务器
- 更新Debian服务器软件包
---

> 介绍

最佳做法是定期更新服务器以提高安全性和稳定性。使用本指南来保持您的Debian服务器更新。

最佳做法是定期更新服务器以提高安全性和稳定性。使用本指南来保持您的Debian服务器更新。

1. 支持的版本

本指南适用于：
Debian 9 "Stretch"
Debian 10 "Buster"

2. 进行备份

在更新系统之前，请务必进行备份。

> 更新软件包列表

此命令从启用的存储库更新软件包列表。

    sudo apt update
> 列出可升级的软件包

此步骤是可选的。要在执行升级之前查看可升级软件包，请使用apt list命令。

    sudo apt list --upgradable
> 升级包

此命令将升级所有可升级软件包。

    sudo apt upgrade

>重新启动服务器

    sudo reboot

>>>> 一键升级 

如果要接受所有默认设置并在没有干预的情况下执行升级，请使用以下命令：

    sudo apt update && sudo apt upgrade -y
>可选-自动删除

使用apt自动删除旧软件包和依赖项。

    sudo apt autoremove

> 说明

定期更新可确保服务器安全稳定。在更新之前，请确保已制定快照或备份策略。

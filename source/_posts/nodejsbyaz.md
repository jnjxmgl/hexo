---
title: NODEJS 编译安装
date: 2019-07-14 14:43:29
tags:
- nodejs
- centos7 
categories: linux

keywords:
- NODEJS编译安装
- NODEJS
- gcc

---



> 安装编译环境

    yum -y install gcc gcc-c++

> 下载nodejs 命令:

    wget https://nodejs.org/dist/latest-v0.12.x/node-v0.12.18.tar.gz

> 解压

    tar -zxvf node-v0.12.18.tar.gz

> 进入解压后的目录

    cd node-v0.12.18

> 安装

    ./configure && make && make install


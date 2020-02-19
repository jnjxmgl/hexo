---
title: ubuntu server 设置静态ip
date: 2020-02-19 11:39:35
tags: Ubuntu
categories: linux
---
> 本例子在ubuntu 18.04中可用

在ubuntu server中,找到/etc/netplan/文件夹中的`50-cloud-init.yaml`文件,配置说明如下:

```yaml
# This file is generated from information provided by
# the datasource.  Changes to it will not persist across an instance.
# To disable cloud-init's network configuration capabilities, write a file
# /etc/cloud/cloud.cfg.d/99-disable-network-config.cfg with the following:
# network: {config: disabled}
network:
    ethernets:
        ens33:  #网卡名称
            dhcp4: false  # 取消自动获取ip
            gateway4: 192.168.6.2 # 服务器ip网关
            addresses:
            - 192.168.6.131/24 # 服务器ip地址/子网掩码
            nameservers: #名称服务器
                addresses: 
                - 192.168.6.2 # dns地址
    version: 2


#ubuntu server 18.04 配置静态ip

```
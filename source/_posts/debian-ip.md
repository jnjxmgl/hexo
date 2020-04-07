---
title: debian 设置静态ip
date: 2020-02-19 11:39:35
tags:
- ip
- debian
categories: 
- linux
- debian
keywords:
- debian 设置静态ip
- 设置静态ip
- debian
- nameserver
- /etc/network/interfaces
- dns-nameservers
- netmask
- iface
- gateway

---

编辑/etc/network/interfaces文件

```
iface eth0 inet static
address 192.168.6.129
netmask 255.255.255.0
gateway 192.168.6.2
dns-nameservers 192.168.6.2 
```

重启网络

```shell
systemctl restart networking
```

如果默认得网关做了变更,还需要修改`/etc/resolv.conf`的内容,将
```
nameserver xxx.xxx.xxx.xxx
```
修改成当下得网关,即:

```
nameserver 192.168.6.2
```


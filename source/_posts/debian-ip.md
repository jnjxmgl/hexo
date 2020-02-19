---
title: debian 设置静态ip
date: 2020-02-19 11:39:35
tags: debian
categories: linux
---

```

iface eth0 inet static
address 192.168.6.129
netmask 255.255.255.0
gateway 192.168.6.2
dns-nameservers 192.168.6.2 


```
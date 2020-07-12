---
title: Ubuntu 18.04设置dns
date: 2020-07-09 08:39:20
tags: 
- dns
- ubuntu
categories: ubuntu
keywords:
- Ubuntu 18.04设置dns 
- ubuntu设置dns以后会自动变更
---

最近使用ubuntu 18.04运行一些服务，然后发现服务器经常出现网络不通的情况，主要是一些域名无法解析。

检查`/etc/resolv.conf`，发现之前修改的nameserver总是会被修改为127.0.0.53，无论是改成啥，在不确定的时间，总会变回来。

查看`/etc/resolv.conf`这个文件的注释，发现开头就写着这么一行：

    # This file is managed by man:systemd-resolved(8). Do not edit.

这说明这个文件是被`systemd-resolved`这个服务托管的。

 你可以手动修改`/etc/resolve.conf`以后,使用`tail -f /etc/resolve.conf`开一个终端,然后另一个终端执行`systemctl restart systemd-resolved`动态查看会发现你修改的那个文件会自动变回去

通过`netstat -tnpl| grep systemd-resolved`查看到这个服务是监听在53号端口上。

但是有些时候使用上述命令是看不到任何结果的

这个服务的配置文件为/etc/systemd/resolved.conf，大致内容如下：

    [Resolve]
    DNS=223.5.5.5 119.29.29.29
    #FallbackDNS=
    #Domains=
    #LLMNR=no
    #MulticastDNS=no
    #DNSSEC=no
    #Cache=yes
    #DNSStubListener=yes

如果我们要想让`/etc/resolve.conf`文件里的配置生效，需要添加到`systemd-resolved`的这个配置文件里DNS配置项，然后重启`systemd-resolved`服务即可。

修改办法:

    #LLMNR=no 将这个#号去掉


另一种更简单的办法是，我们`直接停掉systemd-resolved`服务，这样再修改`/etc/resolve.conf`就可以一直生效了。

借鉴转载至:https://www.cnblogs.com/breezey/p/9155988.html
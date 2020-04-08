---
title: ifconfig 提示command not found
date: 2019-07-14 15:13:41
tags: centos7
categories: linux
keywords:
- ifconfig
- 提示command not found
- ifconfig 提示command not found
- net-tools
- ipconfig和ifconfig
---
 如过我们再使用linux时, 使用ifconfig查看主机ip提示, 如下图:

``` shell
 -bash: ifconfig: command not found
```

![command not found](https://res.imgl.net/hexo/ifconfig/wzd.PNG "command not found")

是因为我门没有安装相关的命令程序.

## centos7

再centos7中, 我们可以使用命令

    
    yum search ifconfig

来确认需要安装的程序包, 比如我执行命令后会得到如下结果:

![找到相关安装包名](https://res.imgl.net/hexo/ifconfig/azb.PNG "找到相关安装包名")

根据提示, 我们执行, 如下图

    yum install -y net-tools.x86_64

![执行安装](https://res.imgl.net/hexo/ifconfig/az.PNG "执行安装")

最后我们再次执行 `ifconfig` , 就可以看到我们的网络ip配置信息了.

![结果](https://res.imgl.net/hexo/ifconfig/ip.PNG "结果")

*最后说一下: `ifconfig` 和windows系统下的 `ipconfig` 很像, 很多初学者会在linux中执行ipconfig, 这显然是~~不可以的~~, 请认真识别, 不要犯这种低级错误*


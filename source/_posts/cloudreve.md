---
title: 在宝塔上进行cloudreve安装配置
date: 2020-04-19 19:07:32
tags:
- cloudreve
categories:
- cloudreve
- debian
keywords:
- cloudreve
- 宝塔
- 宝塔上cloudreve安装
---
>在宝塔上创建测试站点,如: http://pan.baidu.com ,如下图:

![创建测试站点](https://res.imgl.net/hexo/cloudreve/a.PNG "创建测试站点")

>将下载好的`cloudreve`上传到宝塔,并解压完毕,如下图:

![解压缩](https://res.imgl.net/hexo/cloudreve/b.PNG "解压缩")

>安装`supervisor`创建后台守护进程

![创建守护进程](https://res.imgl.net/hexo/cloudreve/c.PNG "创建守护进程")

>启动守护进程

![启动守护进程](https://res.imgl.net/hexo/cloudreve/d.PNG "启动守护进程")

>配置反向代理

![配置反向代理](https://res.imgl.net/hexo/cloudreve/e.PNG "配置反向代理")


![保存配置](https://res.imgl.net/hexo/cloudreve/f.PNG "保存配置")

**到这一步就算配置完了,下面访问 http://pan.baidu.com 看看吧,后期可已根据自己的需求增加https的支持**

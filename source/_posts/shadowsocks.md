---
title: 搭建SHADOWSOCKS VPN
date: 2019-07-14 14:33:21
tags: 
- shadowsocks
- centos7
categories: linux
---

安装python，安装过程遇到Y/n的一律输入Y（按顺序执行下面命令）：

    yum install python-setuptools

安装pip，依次执行下面几个命令：

    wget https: //files.pythonhosted.org/packages/36/fa/51ca4d57392e2f69397cd6e5af23da2a8d37884a605f9e3f2d3bfdc48397/pip-19.0.3.tar.gz

    tar -xzvf pip-19.0 .3.tar.gz

    cd pip-19.0 .3

    python setup.py install

每行代表一个命令, 依次执行.

> 安装Shadowsocks：

    pip install shadowsocks

出现Successfully installed shadowsocks关键字说明安装成功了

输入以下命令创建shadowsocks.json配置文件：

    vi /etc/shadowsocks.json

按一下i进入输入模式:

``` json
{
    "server": "0.0.0.0", 
    "server_port": 8388, 
    "password": "your_password", 
    "timeout": 600, 
    "method": "aes-256-cfb", 
    "fast_open": false
}
```

“server”：是你服务器的ip地址

“server_port”和”password”可以根据自己的要求设定

如果需要同时开多个端口，config.json的内容可以设置如下：

``` json
{
    "server": "0.0.0.0", 
    "port_password": {
        "8388": "your_password1", 
        "8389": "your_password2"
    }, 
    "timeout": 600, 
    "method": "aes-256-cfb", 
    "fast_open": false
}
```

执行以下命令启动Shadowsocks：

    ssserver-c /etc/shadowsocks.json-d start

永久开启8388端口

    firewall-cmd -–zone=public --add-port=8388/tcp –-permanent

因为centos7防火墙是firewall , centos6的自行百度开启端口的方法

> centos7安装supervisor

    yum install supervisor

生成配置文件

    echo_supervisord_conf > /etc/supervisord.conf

**1. 启动supervisord**

命令:

    supervisord

2. 编辑配置文件/etc/supervisord.conf

命令:

    vi /etc/supervisord.conf

按i进入编辑模式, 输入:

    [program: shadowsocks]

    command = ssserver-c /etc/shadowsocks.json

    autostart = true

    autorestart = true

    startsecs = 3

3. 刷新配置

    supervisorctl reload

4. 启动supervisord管理的所有进程

    supervisorctl start all

5. 查看运行状态

    supervisorctl status

> 如果shadowsocks 是RUNNING状态说明成功了


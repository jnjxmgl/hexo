---
title: v2ray代理创建+ws+nginx+ssl
date: 2019-06-07 10:49:49
tags:
- v2ray
- websocket
categories: 
- linux
- debian
---


## <s>制作本教程的目的是忌于网上的一键脚本可能会安装一些挖矿的恶意程序,没有人权保证<s>

由于v2ray官方网站的教程对于新手来说有些东西不容易理解,这里特意做了一篇文章教给大家v2ray的基本使用,以及基本的安全防护和nginx的分流,本教程需要你具备一点linux的使用知识

附:官方网站https://www.v2ray.com/

> 第一步 下载v2ray的服务器端程序和PC端程序

预编译的压缩包可以在如下几个站点找到：

1. Github Release: https://github.com/v2ray/v2ray-core
2. Github 分流: https://github.com/v2ray/dist
3. Homebrew: https://github.com/v2ray/homebrew-v2ray
4. Arch Linux: https://packages/community/x86_64/v2ray/
5. Snapcraft: https://snapcraft.io/v2ray-core.


压缩包均为 zip 格式，找到对应平台的压缩包，下载解压即可使用
推荐`2`位置,都是编译好的稳定发布版本

由于国内网络问题,出现打不开的情况可已使用我之前已经下载好的(`非最新`)

***服务器端:***

![v2ray服务器端](http://q8ch2wiw7.bkt.clouddn.com/hexo/create-v2ray-vpn/v2ray-linux-64.zip "v2ray服务器端")

***PC端:***

![v2ray-PC端](http://q8ch2wiw7.bkt.clouddn.com/hexo/create-v2ray-vpn/v2rayN.zip "v2ray-PC端")


>搭环境

我这里基于Debian10,不推荐centos7,因为如过你要开启BBR加速,需要4.10+以上内核,centos7的内核不符合要求.

在服务器的根`/`下创建www目录

将域名证书上传到/www/certs

将v2ray服务器端上传到www并解压到test.com目录


***安装nginx及supervisor:***
```shell
apt install nginx supervisor
```

***创建nginx配置***

在目录`/etc/nginx/conf.d/`创建v2ray.conf文件

    #v2ray
        server {
            listen       443 ssl http2;
            listen       [::]:443 ssl http2;
            server_name  test.com;
            ssl_certificate    /www/certs/test.com.pem;
            ssl_certificate_key    /www/certs/test.com.key;
            ssl_session_cache shared:SSL:1m;
            ssl_session_timeout  10m;
            ssl_ciphers HIGH:!aNULL:!MD5;
            ssl_prefer_server_ciphers on;
            location / {
                proxy_pass http://127.0.0.1:56789;
                proxy_set_header Host $host;
                proxy_set_header X-Real-IP $remote_addr;
                proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
                proxy_set_header REMOTE-HOST $remote_addr;
                proxy_connect_timeout 30s;
                proxy_read_timeout 86400s;
                proxy_send_timeout 30s;
                proxy_http_version 1.1;
                proxy_set_header Upgrade $http_upgrade;
                proxy_set_header Connection "upgrade";        
                expires 12h;
            }
        }
    
在目录`/etc/supervisor/conf.d`创建v2ray.conf文件

    [program:v2ray]
    command=/www/test.com/v2ray
    autostart=true
    autorestart=true
    startsecs=3


注意要给test.com目录中的所有文件赋予读写可执行权限,简单的操作就直接都改成777

清空test.com目录中config.json文件,复制如下内容

    {
        "log": {
            "access": "/www/test.com/access.log",
            "error": "/www/test.com/error.log",
            "loglevel": "warning"
        },
        "inbounds": [
            {
                "port": 56789,
                "protocol": "vmess",
                "settings": {
                    "clients": [
                        {
                            "id": "61fb26da-0fa0-4d0e-9e4d-404c1d7538a9",
                            "level": 1,
                            "alterId": 8888
                        }
                    ]
                },
                "streamSettings": {
                    "network": "ws"
                },
                "sniffing": {
                    "enabled": true,
                    "destOverride": [
                        "http",
                        "tls"
                    ]
                }
            }
        ],
        "outbounds": [
            {
                "protocol": "freedom",
                "settings": {}
            }
        ],
        "dns": {
            "server": [
                "1.1.1.1",
                "1.0.0.1",
                "8.8.8.8",
                "8.8.4.4",
                "localhost"
            ]
        },
        "transport": {
            "sockopt": {
                "tcpFastOpen": true
            }
        }
    }

新的UUID可以去http://www.uuid.online/创建

重启nginx和supervisor
```shell
systemctl reload nginx

supervisorctl reload

```

使用`supervisorctl status` 查看运行情况.


服务器端所有操作基本完成,注意开启443端口

可已使用PC端创建上网方式了,这里不做讲解,网上对PC端操作的教程比较多,自行百度
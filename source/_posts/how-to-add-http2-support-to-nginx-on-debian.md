---
title: 如何向 Debian 上的 Nginx 添加 HTTP2 支持
date: 2020-08-11 15:31:28
tags:
- nginx
categories:
- nginx
- debian
keywords:
- 如何向 Debian 上的 Nginx 添加 HTTP2 支持
- nginx站点http强制跳转https
---

 
> 配置 

若要为 SSL Vhost 启用 `"HTTP2"`，请将 `server` 块的配置修改为如下

    listen 443 ssl http2;

如果您希望强制将所有非 SSL （HTTP） 网站重定向到 HTTPS，请添加 Nginx 配置文件增加80站点

    server {
        listen         80;
        listen         [::]:80;
        server_name    yourdomain;
        return 301 https://$host$request_uri;
    }

现在可以重新启动Nginx，访问：https://yourdomain/

    service nginx restart

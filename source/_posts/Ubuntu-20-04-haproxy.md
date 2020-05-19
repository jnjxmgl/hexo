---
title: 在Ubuntu20.04上进行haproxy的简单配置使用
date: 2020-05-19 10:20:06
tags:
- haproxy
categories:
- 代理服务器
keywords:
- haproxy代理配置
- haproxy代理实现动静分离
- Ubuntu20.04上进行haproxy的简单配置
---

> 准备工作

1. 三台主机,分别是192.168.6.128,192.168.6.129,192.168.6.130

2. 在192.168.6.128安装haproxy

        apt install haproxy

3. 在192.168.6.129安装apache+php

        apt install php7.4 #这一步会自动安装apache,并配置好php的解析环境,很方便

4. 在192.168.6.130安装nginx

        apt install nginx

注:因为对于php等动态脚本语言解析apache相对于nginx更有优势,我们选择apache+php的组合,静态文件nginx更有优势;

>在`192.168.6.128`上配置haproxy,找到/etc/haproxy/haproxy.cfg文件

配置代码如下:

    listen stats
        bind 0.0.0.0:8888
        stats refresh 30s
        stats uri /stats
        stats realm Haproxy Manager
        stats auth admin:admin

    frontend main
        bind 0.0.0.0:80

        acl url_static path_beg -i /static /images /javascript /stylesheets
        acl url_static path_end -i .jpg .gif .png .css .js
        acl url_dynamic path_end -i .php
        use_backend static if url_static
        use_backend dynamic if url_dynamic
        default_backend static


    backend static
        balance roundrobin
        server websrv1 192.168.6.130:80 check maxconn 1000

    backend dynamic
        balance roundrobin
        server dynamic1 192.168.6.129:80 inter 3000 rise 2 fall 3 check maxconn 100

配置完成后使用

    systemctl restart haproxy

重新启动haproxy

> 验证

1. 在浏览器中打开站点 `http://192.168.6.128:8888/stats` , 可以看到如下图所示的数据统计也面

![统计报告](https://res.imgl.net/hexo/Ubuntu-20-04-haproxy/20200519101647.png '统计报告')
2. 在192.168.6.129服务器的`/var/www/html`创建index.php,内容如下:

```php
<?php
    phpinfo();
>
```

在浏览器中打开站点 `http://192.168.6.128/index.php` , 可以看到如下图所示的phpinfo信息,表示我们配置的动态代理没有问题

![phpinfo](https://res.imgl.net/hexo/Ubuntu-20-04-haproxy/20200519101828.png 'phpinfo')
3. 随便将一张图片(我这里命名为timg.jpg)上传到192.168.6.130服务器的`/var/www/html`目录下

在浏览器中打开站点 `http://192.168.6.128/timg.jpg` , 可以看到如下图所示的phpinfo信息,表示我们配置的静态资源代理没有问题

![timg.jpg](https://res.imgl.net/hexo/Ubuntu-20-04-haproxy/20200519101746.png 'timg.jpg')

>总结

至此,haproxy的基本使用配置就算完了,其中可能包含不恰到的配置,但是对于初次学习使用的我们还需要慢慢深究,保持一个谦虚的心.

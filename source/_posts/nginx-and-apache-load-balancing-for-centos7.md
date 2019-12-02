---
title: 在centos7上配置nginx与apache负载均衡
date: 2019-11-06 11:39:40
tags: 
- apache
- nginx
- php
- centos7
categories: 
- linux
- php
---

> 停掉防火墙

因为是在虚拟机中测试,为了省去繁琐的端口开放步骤,直接将`firewall`停掉,执行代码
```
systemctl stop firewalld
```

>安装apache

apache其实是基金会的名字,我们其实要装的是`httpd`,httpd是apache基金会的一个开源项目.

执行安装命令
```
yum install httpd
```
安装完成以后,先别着急启动,首先将apache的端口号改成`8080`(这个随意,目的是为了防止和nginx的80端口冲突),执行命令:
```
vi /etc/httpd/conf/httpd.conf
```
大约在`42`行

    42  Listen 8080

完事以后`'wq'`保存

> 安装php

在centos7中自带的php版本(5.4)较低,下面介绍使用`ius`源安装最新的php,具体怎么配置`ius`源,请看我的另一篇关于`'centos7下使用ius源'`.

配置好源站以后我们安装最新的php7.3,这里只介绍安装最基本的php扩展支持,执行命令:
```
yum list | grep php73
```
输出结果如下:

    php73-cli.x86_64                        7.3.11-1.el7.ius               @ius
    php73-common.x86_64                     7.3.11-1.el7.ius               @ius
    php73-fpm.x86_64                        7.3.11-1.el7.ius               @ius
    php73-fpm-httpd.noarch                  7.3.11-1.el7.ius               @ius
    php73-fpm-nginx.noarch                  7.3.11-1.el7.ius               @ius
    mod_php73.x86_64                        7.3.11-1.el7.ius               ius
    php73-bcmath.x86_64                     7.3.11-1.el7.ius               ius
    php73-dba.x86_64                        7.3.11-1.el7.ius               ius
    php73-dbg.x86_64                        7.3.11-1.el7.ius               ius
    php73-devel.x86_64                      7.3.11-1.el7.ius               ius
    php73-embedded.x86_64                   7.3.11-1.el7.ius               ius
    php73-enchant.x86_64                    7.3.11-1.el7.ius               ius
    php73-gd.x86_64                         7.3.11-1.el7.ius               ius
    php73-gmp.x86_64                        7.3.11-1.el7.ius               ius
    php73-imap.x86_64                       7.3.11-1.el7.ius               ius
    php73-interbase.x86_64                  7.3.11-1.el7.ius               ius
    php73-intl.x86_64                       7.3.11-1.el7.ius               ius
    php73-json.x86_64                       7.3.11-1.el7.ius               ius
    php73-ldap.x86_64                       7.3.11-1.el7.ius               ius
    php73-mbstring.x86_64                   7.3.11-1.el7.ius               ius
    php73-mysqlnd.x86_64                    7.3.11-1.el7.ius               ius
    php73-odbc.x86_64                       7.3.11-1.el7.ius               ius
    php73-opcache.x86_64                    7.3.11-1.el7.ius               ius
    php73-pdo.x86_64                        7.3.11-1.el7.ius               ius
    php73-pdo-dblib.x86_64                  7.3.11-1.el7.ius               ius
    php73-pecl-amqp.x86_64                  1.9.4-3.el7.ius                ius
    php73-pecl-apcu.x86_64                  5.1.18-1.el7.ius               ius
    php73-pecl-apcu-devel.x86_64            5.1.18-1.el7.ius               ius
    php73-pecl-apcu-panel.noarch            5.1.18-1.el7.ius               ius
    php73-pecl-geoip.x86_64                 1.1.1-10.el7.ius               ius
    php73-pecl-igbinary.x86_64              3.0.1-1.el7.ius                ius
    php73-pecl-igbinary-devel.x86_64        3.0.1-1.el7.ius                ius
    php73-pecl-imagick.x86_64               3.4.4-2.el7.ius                ius
    php73-pecl-imagick-devel.x86_64         3.4.4-2.el7.ius                ius
    php73-pecl-lzf.x86_64                   1.6.7-2.el7.ius                ius
    php73-pecl-memcached.x86_64             3.1.4-1.el7.ius                ius
    php73-pecl-mongodb.x86_64               1.6.0-1.el7.ius                ius
    php73-pecl-msgpack.x86_64               2.0.3-3.el7.ius                ius
    php73-pecl-msgpack-devel.x86_64         2.0.3-3.el7.ius                ius
    php73-pecl-redis.x86_64                 5.0.2-1.el7.ius                ius
    php73-pecl-smbclient.x86_64             1.0.0-3.el7.ius                ius
    php73-pecl-xdebug.x86_64                2.7.2-2.el7.ius                ius
    php73-pecl-yaml.x86_64                  2.0.4-3.el7.ius                ius
    php73-pgsql.x86_64                      7.3.11-1.el7.ius               ius
    php73-process.x86_64                    7.3.11-1.el7.ius               ius
    php73-pspell.x86_64                     7.3.11-1.el7.ius               ius
    php73-recode.x86_64                     7.3.11-1.el7.ius               ius
    php73-snmp.x86_64                       7.3.11-1.el7.ius               ius
    php73-soap.x86_64                       7.3.11-1.el7.ius               ius
    php73-sodium.x86_64                     7.3.11-1.el7.ius               ius
    php73-tidy.x86_64                       7.3.11-1.el7.ius               ius
    php73-xml.x86_64                        7.3.11-1.el7.ius               ius
    php73-xmlrpc.x86_64                     7.3.11-1.el7.ius               ius

下面说下我们必须安装的几个
```
yum install php73-fpm-httpd  php73-common php73-cli php73-fpm
```
其中`php73-fpm-httpd`在安装的时候默认会将后面的`php73-common` `php73-fpm`依赖一起安装,安装`php73-fpm-httpd`这个东西,就省去了我们配置php和apache环境的麻烦.

到此时,我们已经可以启动apache和php了,执行命令:
```
systemctl start php-fpm
systemctl start httpd
```
此时 ,在目录 `/var/www/html/` (注:该目录不是nginx的项目默认目录,后面做负载均衡以后代码路径要按照nginx默认路径,或者在nginx中配置你希望的目录) 下创建`index.php`,已经可以通过虚拟机地址--http://192.168.0.134:8080/index.php ,正常访问php站点了

>安装nginx配置负载均衡

执行命令安装nginx
```
yum install nginx #请注意,该命令需要'epel'源的支持,请保证预先配置完毕
```
同样是先不要启动nginx,执行如下命令对nginx进行配置
```
cd /etc/nginx/conf.d
```
创建`php.conf`,文件
```
vi php.conf
```
输入一下内容:

    upstream backend {
        server 127.0.0.1:8080;
    }

最后`'wq'`保存

然后配置`nginx.conf`

    vi /etc/nginx/nginx.conf

在`server`块添加如下内容:

    location ~ \.php$ {
        proxy_pass http://backend;   
    }
最后`wq`保存退出

然后启动nginx 
```
systemctl start nginx
```

在`/usr/share/nginx/html`(nginx默认路径,可自定义)中创建index.php文件

然后使用http://虚拟机ip/index.php就可以访问了,至此,负载均衡配置完毕.

如果你想安装php的其他扩展,比如`gd`,你可以执行命令
```
yum install  php73-gd
```
其他类似

最后说明一下,在Centos及redhat中,由于selinux的策略问题,nginx反向代理apache会出现`502`的错误,查看日志会有`Permission denied`的提示,解决方式有两种:

第一就是关闭selinux策略,这个太常见,自行百度

第二就是执行如下命令:

    /usr/sbin/setsebool httpd_can_network_connect true




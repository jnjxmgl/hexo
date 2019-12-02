---
title: scp命令文件传输
date: 2019-08-03 09:23:03
tags: centos7
categories: linux
---
在linux下一般用scp这个命令来通过ssh传输文件。

> 1、从服务器上下载文件

    scp username@servername:/path/filename /var/www/local_dir（本地目录）

 例如

    scp root@192.168.0.101:/var/www/test.txt  #把192.168.0.101上的/var/www/test.txt 的文件下载到/var/www/local_dir（本地目录）

> 2、上传本地文件到服务器

    scp /path/filename username@servername:/path

例如

    scp /var/www/test.php  root@192.168.0.101:/var/www/  #把本机/var/www/目录下的test.php文件上传到192.168.0.101这台服务器上的/var/www/目录中

> 3、从服务器下载整个目录

    scp -r username@servername:/var/www/remote_dir/（远程目录） /var/www/local_dir（本地目录）

例如:

    scp -r root@192.168.0.101:/var/www/test  /var/www/  

> 4、上传目录到服务器

    scp  -r local_dir username@servername:remote_dir

例如：

    scp -r test  root@192.168.0.101:/var/www/   #把当前目录下的test目录上传到服务器的/var/www/ 目录

如过有端口的话，可已使用 `-P` 后面跟上端口，比如：

    scp -r -P 端口号 root@192.168.0.101:/var/www/test  /var/www/  

注：目标服务器要开启写入权限。


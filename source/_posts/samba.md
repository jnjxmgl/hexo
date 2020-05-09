---
title: centos7使用samba搭建nfs服务器
date: 2019-07-19 17:40:11
tags: 
- samba
- centos7
categories: linux
keywords:
- samba
- nfs服务器
- 搭建nfs服务器
- 使用samba
- smb.conf

---

## 安装

    [root@imgl samba]# yum -y install samba

## 配置

进入samba配置目录

    [root@imgl samba]# cd /etc/samba/

 
备份smb.conf

    [root@imgl samba]# cp smb.conf smb.conf.bak

 

编辑smb.conf

    [root@imgl samba]# vi smb.conf

修改为如下内容

    [global]
        workgroup = WORKGROUP
        security = user
        server string = Samba Server Version %v
        passdb backend = tdbsam
        map to guest = Bad User

    [FileShare]
            comment = share some files
            path = /smb/fileshare
            public = yes
            writeable = yes
            create mask = 0644
            directory mask = 0755

    [WebDev]
            comment = project development directory
            path = /smb/webdev
            valid users = mgl
            write list = mgl
            printable = no
            create mask = 0644
            directory mask = 0755

保存并推出
注释：

> workgroup 项应与 Windows 主机保持一致，这里是WORKGROUP

再下面有两个section，实际为两个目录，section名就是目录名（映射到Windows上可以看见）。
第一个目录名是FileShare，匿名、公开、可写
第二个目录吗是WebDev，限定mgl用户访问
默认文件属性644/755（不然的话，Windows上在这个目录下新建的文件会有“可执行”属性）

## 创建用户

    [root@imgl samba]# groupadd nfs
    [root@imgl samba]# useradd mgl -g nfs -s /sbin/nologin
    [root@imgl samba]# smbpasswd -a mgl
    New SMB password:
    Retype new SMB password:
    Added user mgl.
    [root@imgl samba]# 

注意这里smbpasswd将使用系统用户。设置密码为1

## 创建共享目录

    [root@imgl samba]# mkdir -p /smb/{fileshare,webdev}
    [root@imgl samba]# chown nobody:nogroup /smb/fileshare/
    [root@imgl samba]# chown mgl:nfs /smb/webdev/

注意设置属性，不然访问不了。

 

## 启动Samba服务，设置开机启动

    [root@imgl samba]# systemctl start smb
    [root@imgl samba]# systemctl enable smb
    Created symlink from /etc/systemd/system/multi-user.target.wants/smb.service to /usr/lib/systemd/system/smb.service.
    [root@imgl samba]# 

 

## 开放端口

    [root@imgl samba]# firewall-cmd --permanent --add-port=139/tcp
    success
    [root@imgl samba]# firewall-cmd --permanent --add-port=445/tcp
    success
    [root@imgl samba]# systemctl restart firewalld
    [root@imgl samba]# 

或者直接把防火墙关了也行

> 最后一个相当重要, 如果你使用的是centos, 那么你需要关闭Selinux, 操作如下:

### 临时关闭

    [root@localhost ~]# getenforce #查看当前状态
    Enforcing
    
    [root@localhost ~]# setenforce 0 #临时关闭
    [root@localhost ~]# getenforce #查看当前状态
    Permissive

### 永久关闭

    [root@localhost ~]# vi /etc/sysconfig/selinux

SELINUX=enforcing 改为 SELINUX=disabled

### 重启操作系统

    reboot

## 使用

在windows系统的运行那里, 输入:\\\你的ip

如果有密码账户验证, 账户时mgl密码是1

参考文章(https://www.cnblogs.com/nidey/p/6195483.html)


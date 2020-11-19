---
title: 在Debian / Ubuntu上安装Subversion（SVN）存储库
date: 2020-11-19 09:49:49
tags:
categories:
keywords:
---

> 安装所需的软件包

为了减少资源使用，我们将在xinetd下运行SVN 。
```
apt-get install xinetd subversion
```

> 创建SVN用户
```
adduser --system --home /var/svn --disabled-password --disabled-login --group svn
```
> 创建您的第一个存储库
```
svnadmin create /var/svn/repositories
```
运行以下命令以将设置插入到 `/var/svn/repositories/conf/svnserve.conf`

```
cat >/var/svn/repositories/conf/svnserve.conf <<EOF
[general]
anon-access = none
auth-access = write
password-db = passwd
authz-db    = authz

[sasl]
EOF
```
编辑`/var/svn/repositories/conf/passwd`以添加用户和密码。

插入：
```
[users]
jnjxmgl = 123456
```
编辑`/var/svn/repositories/conf/authz`以修改用户权限。

例如:
```
[/]
jnjxmgl = rw
```

注意： `r =只读；rw =读写`

运行以下命令以为Subversion创建xinetd配置文件 `/etc/xinetd.d/svnserve`

```
cat >/etc/xinetd.d/svnserve <<EOF
service svn
{
        port        = 3690
        socket_type = stream
        protocol    = tcp
        wait        = no
        user        = svn
        server      = /usr/bin/svnserve
        server_args = -i -r /var/svn/repositories
}
EOF
```

重新启动`xinetd`，您就完成了。
```
/etc/init.d/xinetd restart
```

> 检验

使用以下命令确保Subversion正在运行：
```
netstat -ant | grep ':3690'
```
如果正在运行Subversion，则应该看到类似以下内容：
```
tcp        0      0 0.0.0.0:3690            0.0.0.0:*               LISTEN
```
如果安装了防火墙，请记住打开端口`3690`。

现在，您可以使用`svn://192.168.6.128/`任何SVN客户端访问您的Subversion存储库。
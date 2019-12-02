---
title: 如何列出RPM软件包中包含的所有文件
date: 2019-11-01 11:40:10
tags:
---

假设您要在Fedora或CentOS上安装特定的RPM软件包，但是要在安装前检查软件包的内容。当您尝试在系统中安装任何丢失的二进制文件或库文件时，可能会出现这种情况。

在本教程中，我将描述如何列出CentOS，Fedora或RHEL上RPM软件包中包含的所有文件。

> 方法一

有一个称为`repoquery`的命令行工具，可以查询YUM存储库的信息。该工具使您无需安装RPM软件包即可查看其内容。

要在CentOS，Fedora或RHEL上安装`repoquery`：

    sudo yum install yum-utils

要使用`repoquery`检查包含在特定软件包（例如nginx）中的文件的列表：

    repoquery -l nginx
如下:

    /etc/logrotate.d/nginx
    /etc/nginx/fastcgi.conf
    /etc/nginx/fastcgi.conf.default
    /etc/nginx/fastcgi_params
    /etc/nginx/fastcgi_params.default
    /etc/nginx/koi-utf
    /etc/nginx/koi-win
    /etc/nginx/mime.types
    /etc/nginx/mime.types.default
    /etc/nginx/nginx.conf
    /etc/nginx/nginx.conf.default
    /etc/nginx/scgi_params
    /etc/nginx/scgi_params.default
    /etc/nginx/uwsgi_params
    /etc/nginx/uwsgi_params.default
    /etc/nginx/win-utf
    /usr/bin/nginx-upgrade
    /usr/lib/systemd/system/nginx.service
    /usr/lib64/nginx/modules
    /usr/sbin/nginx
    /usr/share/doc/nginx-1.16.1
    /usr/share/doc/nginx-1.16.1/CHANGES
    /usr/share/doc/nginx-1.16.1/README
    /usr/share/doc/nginx-1.16.1/README.dynamic
    /usr/share/doc/nginx-1.16.1/UPGRADE-NOTES-1.6-to-1.10
    /usr/share/licenses/nginx-1.16.1
    /usr/share/licenses/nginx-1.16.1/LICENSE
    /usr/share/man/man3/nginx.3pm.gz
    /usr/share/man/man8/nginx-upgrade.8.gz
    /usr/share/man/man8/nginx.8.gz
    /usr/share/nginx/html/404.html
    /usr/share/nginx/html/50x.html
    /usr/share/nginx/html/en-US
    /usr/share/nginx/html/icons
    /usr/share/nginx/html/icons/poweredby.png
    /usr/share/nginx/html/img
    /usr/share/nginx/html/index.html
    /usr/share/nginx/html/nginx-logo.png
    /usr/share/nginx/html/poweredby.png
    /usr/share/vim/vimfiles/ftdetect/nginx.vim
    /usr/share/vim/vimfiles/ftplugin/nginx.vim
    /usr/share/vim/vimfiles/indent/nginx.vim
    /usr/share/vim/vimfiles/syntax/nginx.vim
    /var/lib/nginx
    /var/lib/nginx/tmp
    /var/log/nginx

`*注意:该命令只能列出源仓库中的软件包,也就是说,我们使用 yum list ,能够列出软件名称的rpm包*`

>方法二

第二种查看软件包中所有文件而不安装的方法是通过rpm命令。但是，在这种情况下，您需要在本地下载RPM软件包，以便使用rpm命令查询该软件包。

您可以使用yum命令下载RPM软件包。由于不想安装它，因此必须使用“ `--downloadonly`”选项。

    yum install yum-plugin-downloadonly

要下载特定的RPM软件包下载到指定路径“ `--downloaddir`”命令选项

现在，您可以使用yum命令下载RPM软件包。

    yum install nginx -y --downloadonly --downloaddir=/tmp

使用'reinstall'选项，即使当前在系统上安装了RPM软件包，上述命令也将下载该软件包,当然也可以用'install'。下载的软件包将根据要求存储在`/tmp`目录中。

要列出下载的RPM软件包的文件，请运行以下命令。

    [root@localhost tmp]# rpm -qpl /tmp/nginx-1.16.1-1.el7.x86_64.rpm
    警告：/tmp/nginx-1.16.1-1.el7.x86_64.rpm: 头V3 RSA/SHA256 Signature, 密钥 ID 352c64e5: NOKEY
    /etc/logrotate.d/nginx
    /etc/nginx/fastcgi.conf
    /etc/nginx/fastcgi.conf.default
    /etc/nginx/fastcgi_params
    /etc/nginx/fastcgi_params.default
    /etc/nginx/koi-utf
    /etc/nginx/koi-win
    /etc/nginx/mime.types
    /etc/nginx/mime.types.default
    /etc/nginx/nginx.conf
    /etc/nginx/nginx.conf.default
    /etc/nginx/scgi_params
    /etc/nginx/scgi_params.default
    /etc/nginx/uwsgi_params
    /etc/nginx/uwsgi_params.default
    /etc/nginx/win-utf
    /usr/bin/nginx-upgrade
    /usr/lib/systemd/system/nginx.service
    /usr/lib64/nginx/modules
    /usr/sbin/nginx
    /usr/share/doc/nginx-1.16.1
    /usr/share/doc/nginx-1.16.1/CHANGES
    /usr/share/doc/nginx-1.16.1/README
    /usr/share/doc/nginx-1.16.1/README.dynamic
    /usr/share/doc/nginx-1.16.1/UPGRADE-NOTES-1.6-to-1.10
    /usr/share/licenses/nginx-1.16.1
    /usr/share/licenses/nginx-1.16.1/LICENSE
    /usr/share/man/man3/nginx.3pm.gz
    /usr/share/man/man8/nginx-upgrade.8.gz
    /usr/share/man/man8/nginx.8.gz
    /usr/share/nginx/html/404.html
    /usr/share/nginx/html/50x.html
    /usr/share/nginx/html/en-US
    /usr/share/nginx/html/icons
    /usr/share/nginx/html/icons/poweredby.png
    /usr/share/nginx/html/img
    /usr/share/nginx/html/index.html
    /usr/share/nginx/html/nginx-logo.png
    /usr/share/nginx/html/poweredby.png
    /usr/share/vim/vimfiles/ftdetect/nginx.vim
    /usr/share/vim/vimfiles/ftplugin/nginx.vim
    /usr/share/vim/vimfiles/indent/nginx.vim
    /usr/share/vim/vimfiles/syntax/nginx.vim
    /var/lib/nginx
    /var/lib/nginx/tmp
    /var/log/nginx


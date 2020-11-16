---
title: 在CentOS 7上使用设置Nginx的HTTP身份验证
date: 2020-11-16 09:58:13
tags: nginx
categories: http
keywords:
- 设置Nginx的HTTP验证
- 使用Nginx设置HTTP身份验证
---

> 安装`httpd-tools`软件包

```
yum install httpd-tools
```

>创建一个`.htpasswd`文件
```
htpasswd -c /path/to/directory/.htpasswd username
```

该`.htpasswd`文件将包含有关用户名和密码的信息。`/path/to/directory`是要为其设置身份验证的目录的完整路径。`username`我们将使用它进行身份验证-您可以选择所需的任何内容。系统将要求您输入该用户的密码。输入一个安全密码，然后再次输入相同的密码来确认。

我们已经成功创建了用于身份验证的用户，现在剩下要做的就是修改Nginx配置以使用`.htpasswd`我们刚刚创建的文件。

您可以在找到默认配置`/etc/nginx/conf.d/default.conf`。

我们将在配置中添加2行。

```
server {
    listen       80;
    server_name  example.com www.example.com;

    location / {
        root /path/to/directory/;
        index index.php index.html index.htm;
        auth_basic "Restricted area - This system is for the use of authorized users only!";
        auth_basic_user_file /path/to/directory/.htpasswd
    }
```

特别是，我们添加了以下几行：
```
auth_basic "Restricted area - This system is for the use of authorized users only";
auth_basic_user_file /path/to/directory/.htpasswd
```

第一行定义访问安全目录时登录框中显示的文本，第二行包含`.htpasswd`文件路径。

保存配置并使用以下命令重新启动Nginx服务 

```
/etc/init.d/nginx restart
```

> 设定完成

访问您网站上的安全目录。您将看到一个如下所示的登录提示：

![提示](https://res.imgl.net/hexo/nginx-http-auth/1.jpg "提示")

输入您的用户名和密码，您将被授予访问权限。
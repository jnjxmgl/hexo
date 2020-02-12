---
title: 在centos7下配置pure-ftpd的mysql环境
date: 2020-02-04 19:45:46
tags: 
- ftp
- centos7
categories: ftp
---
`说明:` 我这里讲的是yum安装及配置,编译安装的出门左转.

安装必要源,执行命令:

```shell
yum install \
https://repo.ius.io/ius-release-el7.rpm \
https://dl.fedoraproject.org/pub/epel/epel-release-latest-7.noarch.rpm

```

安装pure-ftpd

```shell
yum install pure-ftpd -y
```

安装mariadb

```shell
yum install mariadb103-server.x86_64 mariadb103-devel.x86_64 -y
```

启动mariadb

```shell
systemctl start mariadb
```

配置mariadb

```shell
mysql_secure_installation
```

如下:

    Securing the MySQL server deployment.

    Connecting to MySQL using a blank password.

    VALIDATE PASSWORD PLUGIN can be used to test passwords
    and improve security. It checks the strength of password
    and allows the users to set only those passwords which are
    secure enough. Would you like to setup VALIDATE PASSWORD plugin?

    Press y|Y for Yes, any other key for No: #这里直接回车
    Please set the password for root here.

    New password: #这里设置root密码

    Re-enter new password: #这里重复输入
    By default, a MySQL installation has an anonymous user,
    allowing anyone to log into MySQL without having to have
    a user account created for them. This is intended only for
    testing, and to make the installation go a bit smoother.
    You should remove them before moving into a production
    environment.

    Remove anonymous users? (Press y|Y for Yes, any other key for No) : y #输入y
    Success.


    Normally, root should only be allowed to connect from
    'localhost'. This ensures that someone cannot guess at
    the root password from the network.

    Disallow root login remotely? (Press y|Y for Yes, any other key for No) : y #输入y
    Success.

    By default, MySQL comes with a database named 'test' that
    anyone can access. This is also intended only for testing,
    and should be removed before moving into a production
    environment.


    Remove test database and access to it? (Press y|Y for Yes, any other key for No) : y #输入y
    - Dropping test database...
    Success.

    - Removing privileges on test database...
    Success.

    Reloading the privilege tables will ensure that all changes
    made so far will take effect immediately.

    Reload privilege tables now? (Press y|Y for Yes, any other key for No) : y #输入y
    Success.

    All done!

创建ftp用户表

```sql
CREATE TABLE users (
  User VARCHAR(255) BINARY NOT NULL, -- ftp用户 
  Password VARCHAR(255) BINARY NOT NULL, -- ftp密码
  Uid INT NOT NULL default '-1',
  Gid INT NOT NULL default '-1',
  Dir VARCHAR(255) BINARY NOT NULL, -- 可访问目录
  PRIMARY KEY (User)
);
```

创建测是用户

```sql
insert into users values('test',password(123456),1000,1000,'/usr');
```

配置连接mysql

编辑 `/etc/pure-ftpd/pure-ftpd.conf` 及 `/etc/pure-ftpd/pureftpd-mysql.conf` 两个配置文件

在第一个配置文件中,将

    # MySQLConfigFile               /etc/pure-ftpd/pureftpd-mysql.conf

前面的 `#` 去掉另外注意该配置中的

    MinUID                      1000

这里指定最小的 `UID` 是 1000 , 前面创建用户的时候,表字段 `UID` `GID` 的取值不能小于 `1000` ;

然后在第二个配置文件中,将如下配置改成你的mysql连接信息

    # MYSQLServer     127.0.0.1 #跟MYSQLSocket互斥,用一个就行
    MYSQLSocket     /var/lib/mysql/mysql.sock
    MYSQLUser       root #mysql连接用户
    MYSQLPassword   123456 #mysql连接密码
    MYSQLDatabase   pureftpd #数据库名称
    MYSQLCrypt      password #加密函数
    #用户表中的Password字段加密方式,该值表示使用mysql的password函数进行加密,如:password(123456),实际存储的字段值是*6BB4837EB74329105EE4568DDA7DC67ED2CA2AD9

最后启动pure-ftpd

```shell
systemctl start pure-ftpd
```

这样就配置完了,赶紧使用ftp工具连接一下吧,账号是`test` 密码是`123456` ,注意不要忘了在服务器开启21端口或者关闭服务器防火墙

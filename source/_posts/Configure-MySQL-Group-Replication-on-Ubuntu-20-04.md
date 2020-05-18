---
title: 在 Ubuntu 20.04 上配置 MySQL 组复制
date: 2020-05-16 15:16:47
tags:
- mysql
categories:
- mysql
keywords:
- 配置 MySQL 组复制
---

> 介绍

MySQL Group Replication是一个创建高可用性容错数据库架构的插件。在无共享架构下，组复制消除了单点故障(SPOF)的机会。在本指南中，您将安装并配置MySQL组复制插件，以创建高可用性的数据库集群。

>先决条件

开始之前，请确保您具有以下内容：

* 一组配置了私有网络的三个Ubuntu 20.04服务器。本指南使用示例私有IP地址192.0.2.1、192.0.2.2和192.0.2.3。

* 在每个服务器上具有sudo特权的非根用户。

* 这三个服务器都是最新的升级版本。

 > 第 1 步：安装 MySQL 服务器

使用非root账户ssh登陆到第一台服务器访问,安装 MySQL 服务器。

    $ sudo apt install mysql-server

为 MySQL 设置root密码并配置安全性。

    $ sudo mysql_secure_installation

在其他两台服务器上重复相同的过程。

> 第 2 步：生成组复制名称

SSH 到服务器 1，以root身份登录到 MySQL 服务器。

    $ sudo mysql -u root -p

输入 MySQL 服务器root密码，然后按<kbd>ENTER</kbd>以继续。

生成 UUID。

    mysql> SELECT UUID();

记下您的新 UUID,其格式将与下面的示例类似,每个服务器使用此 UUID 作为复制组名称。

    +--------------------------------------+
    | UUID()                               |
    +--------------------------------------+
    | 00000000-1111-2222-3333-444444444444 |
    +--------------------------------------+

退出 MySQL。

    mysql> QUIT;

步骤 3：配置`服务器1`

SSH 到`服务器1`,编辑 /etc/mysql/my.cnf

    $ sudo nano /etc/mysql/my.cnf

将下面的信息粘贴到文件末尾。

* 用`服务器1`的地址替换192.0.2.1。
* 将`192.0.2.2`和`192.0.2.3`分别替换为服务器`2和3`的地址。
* 用步骤2中的UUID替换loose-group_replication_group_name的值。

        [mysqld]
        server_id=1
        bind-address=0.0.0.0
        gtid_mode=ON 
        enforce_gtid_consistency=ON
        binlog_checksum=NONE

        plugin_load_add='group_replication.so'
        group_replication_single_primary_mode=OFF
        loose-group_replication_group_name="00000000-1111-2222-3333-444444444444"
        loose-group_replication_start_on_boot=OFF
        loose-group_replication_local_address= "192.0.2.1:33061"
        loose-group_replication_group_seeds="192.0.2.1:33061, 192.0.2.2:33061, 192.0.2.3:33061"
        loose-group_replication_bootstrap_group=OFF
        report_host=192.0.2.1

注：`loose-group_replication`前缀指示服务器启动，即使服务器启动时未加载组复制插件也是如此。

保存并关闭文件。

MySQL 配置设置说明了：

    server_id=1
    这是复制组中的服务器1
    bind-address=0.0.0.0
    收听所有 IP 地址。
    gtid_mode=ON
    使用全局事务标识符运行复制。
    enforce_gtid_consistency=ON
    MySQL 将仅执行可以使用 GTID 安全地记录的语句。
    binlog_checksum=NONE
    禁用将校验和写入二进制日志。
    plugin_load_add='group_replication.so'
    加载组复制插件。
    group_replication_single_primary_mode=OFF
    多主机复制模型，所有成员都有读写访问权限。
    loose-group_replication_group_name="00000000-1111-2222-3333-444444444444444"
    复制组的唯一名称。
    loose-group_replication_start_on_boot_OFF
    请勿在启动时启动复制。您需要手动启动复制。详细了解自动启动复制的含义和注意事项。
    loose-group_replication_local_address= "192.0.2.1：33061"
    此服务器用于复制的地址和端口。
    loose-group_replication_group_seeds="192.0.2.1：33061，192.0.2.2：33061，192.0.2.3：33061"
    其他组成员的主机和端口组合。
    loose-group_replication_bootstrap_group_OFF
    设置 OFF 以避免从多个服务器引导组并引发冲突。
    report_host=192.0.2.1
    此服务器用于向其他成员报告的 IP 地址。

重新启动 MySQL 服务器以应用更改。

    $ sudo service mysql restart

步骤 4：创建复制用户

在服务器 1 上登录到 MySQL。

    $ sudo mysql -u root -p


创建复制用户,用你的密码替换EXAMPLE_PASSWORD。

    mysql> SET SQL_LOG_BIN=0;
    mysql> CREATE USER 'replication_user'@'%' IDENTIFIED WITH mysql_native_password BY 'EXAMPLE_PASSWORD';
    mysql> GRANT REPLICATION SLAVE ON *.* TO 'replication_user'@'%';
    mysql> FLUSH PRIVILEGES;
    mysql> SET SQL_LOG_BIN=1;
    mysql> CHANGE MASTER TO MASTER_USER='replication_user', MASTER_PASSWORD='EXAMPLE_PASSWORD' FOR CHANNEL 'group_replication_recovery';

引导第一台服务器上的组复制插件
仅引导组的一个成员以避免创建同名的多个组。为此，在第一台服务器上运行以下命令。

    mysql> SET GLOBAL group_replication_bootstrap_group=ON;
    mysql> START GROUP_REPLICATION;

关闭`group_replication_bootstrap_group`以避免在重新启动 MySQL 服务器时创建多个组。

    mysql> SET GLOBAL group_replication_bootstrap_group=OFF;

通过查询`replication_group_members`验证组的状态。

    mysql> SELECT MEMBER_ID, MEMBER_HOST, MEMBER_STATE FROM performance_schema.replication_group_members;

验证您的输出与此类似。你的`MEMBER_ID`会有所不同。

    +--------------------------------------+-------------+--------------+
    | MEMBER_ID                            | MEMBER_HOST | MEMBER_STATE |
    +--------------------------------------+-------------+--------------+
    | 11111111-40c4-11e9-92b4-7a4c400acda6 | 192.0.2.1   | ONLINE       |
    +--------------------------------------+-------------+--------------+
    
创建测试数据库，`test_db`。

    mysql> CREATE DATABASE test_db;

切换到数据库。

    mysql> USE test_db; 

创建测试表，`test_tbl`。

    mysql> CREATE TABLE test_tbl (employee_id INT PRIMARY KEY, employee_name VARCHAR(30) NOT NULL) Engine = InnoDB;

确认表存在。

    mysql> SHOW TABLES;

    +----------------------------+
    | Tables_in_test_replication |
    +----------------------------+
    | test_tbl                   |
    +----------------------------+

>第5步: 配置服务器 2

ssh链接到服务器2 ,编辑 `/etc/mysql/my.cnf`

    $ sudo nano /etc/mysql/my.cnf

将下面的信息粘贴到文件末尾。

* 用服务器2的地址替换为192.0.2.2
* 将192.0.2.1和192.0.2.3分别替换为服务器 1 和 3 的地址。
* 将loose-group_replication_group_name的值替换为步骤 2 中的 UUID。

        [mysqld]
        server_id=2
        bind-address=0.0.0.0
        gtid_mode=ON 
        enforce_gtid_consistency=ON
        binlog_checksum=NONE

        plugin_load_add='group_replication.so'
        group_replication_single_primary_mode=OFF
        loose-group_replication_group_name="00000000-1111-2222-3333-444444444444"
        loose-group_replication_start_on_boot=OFF
        loose-group_replication_local_address= "192.0.2.2:33061"
        loose-group_replication_group_seeds="192.0.2.1:33061, 192.0.2.2:33061, 192.0.2.3:33061"
        loose-group_replication_bootstrap_group=OFF
        report_host=192.0.2.2

保存并关闭文件。

重新启动 MySQL。

    $ sudo service mysql restart

以根身份登录到 MySQL。

$ sudo mysql -u root -p
为服务器 2 创建复制用户,将EXAMPLE_PASSWORD替换为你的密码。

    mysql> SET SQL_LOG_BIN=0;
    mysql> CREATE USER 'replication_user'@'%' IDENTIFIED WITH mysql_native_password BY 'EXAMPLE_PASSWORD';
    mysql> GRANT REPLICATION SLAVE ON *.* TO 'replication_user'@'%';
    mysql> FLUSH PRIVILEGES;
    mysql> SET SQL_LOG_BIN=1;
    mysql> CHANGE MASTER TO MASTER_USER='replication_user', MASTER_PASSWORD='EXAMPLE_PASSWORD' FOR CHANNEL 'group_replication_recovery';

启动组复制插件。

    mysql> START GROUP_REPLICATION;

验证服务器 2 现在是组的成员。

    mysql> SELECT MEMBER_ID, MEMBER_HOST, MEMBER_STATE FROM performance_schema.replication_group_members;

验证您的输出与此类似。你的MEMBER_ID会有所不同。

    +--------------------------------------+-------------+--------------+
    | MEMBER_ID                            | MEMBER_HOST | MEMBER_STATE |
    +--------------------------------------+-------------+--------------+
    | 11111111-40c4-11e9-92b4-7a4c400acda6 | 192.0.2.1  | ONLINE        |
    | 22222222-40c4-11e9-92b4-7a4c400acda6 | 192.0.2.2  | ONLINE        |
    +--------------------------------------+-------------+--------------+

验证服务器2已经复制了测试数据库。

    mysql> SHOW databases;

    +--------------------+
    | Database           |
    +--------------------+
    | test_db            |
    +--------------------+

确认`服务器 2` 上存在测试表。

    mysql> SHOW TABLES;

    +----------------------------+
    | Tables_in_test_replication |
    +----------------------------+
    | test_tbl                   |
    +----------------------------+

步骤6: 配置服务器3
SSH连接到服务器3,编辑`/etc/mysql/my.cnf`

    $ sudo nano `/etc/mysql/my.cnf`

将下面的信息粘贴到文件末尾

* 将`192.0.2.3`替换为`服务器 3 `的地址。
* 将`192.0.2.1和192.0.2.2`分别替换为`服务器 1 和 2 `的地址。
* 将`loose-group_replication_group_name`的值替换为步骤 2 中的 UUID。


        [mysqld]
        server_id=3
        bind-address=0.0.0.0
        gtid_mode=ON 
        enforce_gtid_consistency=ON
        binlog_checksum=NONE

        plugin_load_add='group_replication.so'
        group_replication_single_primary_mode=OFF
        loose-group_replication_group_name="00000000-1111-2222-3333-444444444444"
        loose-group_replication_start_on_boot=OFF
        loose-group_replication_local_address= "192.0.2.3:33061"
        loose-group_replication_group_seeds="192.0.2.1:33061, 192.0.2.2:33061, 192.0.2.3:33061"
        loose-group_replication_bootstrap_group=OFF
        report_host=192.0.2.3

保存并关闭

重启mysql

    $ sudo service mysql restart

使用root权限登陆mysql

    $ sudo mysql -u root -p

为`服务器3`创建复制用户,用你的密码替换`EXAMPLE_PASSWORD`。

    mysql> SET SQL_LOG_BIN=0;
    mysql> CREATE USER 'replication_user'@'%' IDENTIFIED WITH mysql_native_password BY 'EXAMPLE_PASSWORD';
    mysql> GRANT REPLICATION SLAVE ON *.* TO 'replication_user'@'%';
    mysql> FLUSH PRIVILEGES;
    mysql> SET SQL_LOG_BIN=1;
    mysql> CHANGE MASTER TO MASTER_USER='replication_user', MASTER_PASSWORD='EXAMPLE_PASSWORD' FOR CHANNEL 'group_replication_recovery';

启动组复制插件。

    mysql> START GROUP_REPLICATION;

验证`服务器3`现在是组的成员。

    mysql> SELECT MEMBER_ID, MEMBER_HOST, MEMBER_STATE FROM performance_schema.replication_group_members;

验证您的输出与此类似。你的MEMBER_ID会有所不同。

    +--------------------------------------+-------------+--------------+
    | MEMBER_ID                            | MEMBER_HOST | MEMBER_STATE |
    +--------------------------------------+-------------+--------------+
    | 11111111-40c4-11e9-92b4-7a4c400acda6 | 192.0.2.1  | ONLINE        |
    | 22222222-40c4-11e9-92b4-7a4c400acda6 | 192.0.2.2  | ONLINE        |
    | 33333333-40c4-11e9-92b4-7a4c400acda6 | 192.0.2.3  | ONLINE        |
    +--------------------------------------+-------------+--------------+

验证`服务器3 ` 已复制测试数据库。

    mysql> SHOW databases;

    +--------------------+
    | Database           |
    +--------------------+
    | test_db            |
    +--------------------+

确认`服务器3 `上存在测试表。

    mysql> SHOW TABLES;

    +----------------------------+
    | Tables_in_test_replication |
    +----------------------------+
    | test_tbl                   |
    +----------------------------+


上述输出确认 MySQL 组复制插件在所有三台服务器上工作。

在生产环境中，必须处理连接到失败成员的客户端，并将它们重定向到组中的 ONLINE 成员。MySQL不处理客户端故障转移,您必须管理具有连接器、负载均衡器、中间件或路由器（如 MySQL Router 8.0）的连接

您最多可以将` 9 `个组成员添加到组复制拓扑中。

> OVER

您已成功在 Ubuntu 20.04 服务器上设置 MySQL 组复制。使用此复制数据库的 Web 应用程序将避免单点故障。
---
title: mysql表修复
date: 2020-07-28 16:02:10
tags: mysql
categories: mysql
keywords: 
- mysql表修复
- marked as crashed and should be repaired
---

在使用mysql的时候出现异常的数据丢失,查询不到的情况,根据mysql错误日志出现类似下面的错误提示

    Table './test/users' is marked as crashed and should be repaired

解决办法:

在控制台执行如下命令

    mysqlcheck -u root -p [数据库名] --auto-repair

示例

    mysqlcheck -u root -p test --auto-repair

最后输入密码确认就可以了 

会输出如下类似信息,等待执行完毕即可

    Repairing tables
    users.wp_commentmeta                       OK
    users.wp_comments                          OK
    users.wp_links                             OK
    users.wp_options                           OK
    users.wp_postmeta                          OK
    users.wp_posts                             OK
    users.wp_term_taxonomy                     OK
    users.wp_terms                             OK




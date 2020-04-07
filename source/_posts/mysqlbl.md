---
title: MYSQL变量定义
date: 2019-07-14 15:05:01
tags: mysql
categories: SQL
keywords:
- MYSQL
- MYSQL变量定义
- MYSQL对象赋值
- MYSQL定义变量赋值

---

> 定义变量赋值:

    declare @i int default 0;

    set @i=100;

    select @i;

将变量@i重命名为i

    select @i i;

> 结果对象赋值:

    select username into @j from wp_admin_user where id=3;

    select @j;

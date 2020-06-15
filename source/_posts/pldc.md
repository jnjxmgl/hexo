---
title: '批量导出指定表前缀的数据表'
date: 2020-06-12 16:10:37
tags:
categories:
keywords:
---

>格式

mysqldump -u [mysql用户] -p [数据库] $(mysql -u [mysql用户] -p [数据库] -Bse "show tables like '[前缀字符串]%'") > "[文件名].sql"

>示例

mysqldump -u root -p enterprise $(mysql -u root -p enterprise -Bse "show tables like 'fa_%'") > "enterprise.sql"
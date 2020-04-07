---
title: MYSQL 数据库数据维护、备份 、还原数据
date: 2019-07-14 15:02:34
tags: mysql
categories: SQL
keywords:
- MYSQL
- 备份
- 数据库数据维护
- 还原数据
- mysqldump

---



> 备份所有数据库：

    mysqldump -u root -p root –-quick -–force -–all-databases > mysqldump.sql

> 还原数据

    mysql -u root -p root < mysqldump.sql

> 备份单个数据库的数据和结构(数据库名TEST)

    mysqldump -u root -p 123456 test > test.sql

> 还原单个数据库 (数据库名TEST)

    mysql -u root -p 123456 test < test.sql

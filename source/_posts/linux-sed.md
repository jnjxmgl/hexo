---
title: Linux批量替换文件夹及子文件夹下的所有文件内容
date: 2019-12-10 15:35:36
tags:
- linux
categories: linux
keywords:
- Linux批量替换文件夹及子文件夹下的所有文件内容
- Linux批量替换文件夹
- Linux批量替换所有文件内容
- sed
- sed命令


---

```shell
sed -i "s/555/sk/g" `grep 555 -rl ./`
```

这里的`555`是要替换的内容,`sk`是替换后的内容, `./`指的是当前目录,可以是相对目录也可以是绝对目录,根据自己的实际要求.
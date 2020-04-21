---
title: 使用新版cloud studio搭建c语言云开发环境
date: 2020-04-20 14:37:31
tags: 
- c
- ubuntu
- cloud studio
categories:
- c
- ubuntu
keywords:
- 使用 新版cloudstudio
- cloudstudio 搭建c语言云开发环境
- 新版 cloudstudio 创建c云开发空间
- 使用 新版 cloud studio 创建c++云开发空间
- 搭建 c或c++云开发空间
- 云开发空间
---

> 第一步创建空间

登陆网站 https://cloudstudio.net/ , 单击 `新建工作空间` ,如下图

![创建空间](https://res.imgl.net/hexo/ccloudstudio/Snap1.png '创建空间')

>进入工作空间

![进入工作空间](https://res.imgl.net/hexo/ccloudstudio/Snap2.png '进入工作空间')


如上图所示,进入空间后默认给我们弹出几个模板,但是 `没有` 我们想要的 `c` 或者 `c++` 环境,怎么办呢?

**答:自己装**

>装环境

1. 在第一步的时候我们已经知道,新创建的空间环境是linux,并且内核是3+,目测是ubuntu,我们可已用vscode调出终端,然后用如下图所示的`命令`进行查验

![查看系统环境](https://res.imgl.net/hexo/ccloudstudio/Snap3.png '查看系统环境')

2. 更新本地源,如下图所示

![更新本地源](https://res.imgl.net/hexo/ccloudstudio/Snap4.png '更新本地源')

3. 安装 `gcc`、`g++` 环境 ,如下图所示, 中间一步使用`y`表示同意并完成安装

![安装](https://res.imgl.net/hexo/ccloudstudio/Snap5.png '安装')

![完成安装](https://res.imgl.net/hexo/ccloudstudio/Snap6.png '完成安装')

可以使用如下图所示的方法进行安装完成后的版本验证

![版本验证](https://res.imgl.net/hexo/ccloudstudio/Snap7.png '版本验证')

4. 使用安装后的环境创建第一个 `Hello World` 控制台程序

在vscode中创建test.c文件,代码如下:

```c
#include <stdio.h>
 
int main()
{
    /* 我的第一个 C 程序 */
    printf("Hello, World! \n");
 
    return 0;
}
```

如下图所示

![创建第一个 `Hello World` 控制台程序](https://res.imgl.net/hexo/ccloudstudio/Snap8.png '创建第一个 `Hello World` 控制台程序')

然后在项目目录的终端命令行里输入
```shell
gcc test.c
```
这样自动将我们原有的`.c`文件,编译为一个`a.out`

下面我门用如下图所示的方式,在当前目录执行编译后的`a.out`文件,可已看到在控制台成功输出了

```shell
./a.out
```

![Hello World](https://res.imgl.net/hexo/ccloudstudio/Snap9.png 'Hello World')


**PS: 由于我们一开始选得环境是纯净的,因此该方法适用于很多语言环境的搭建,包括`Golang`、`PHP`、`.NET Core`等语言的开发环境搭建,其操作过程类似**
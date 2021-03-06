---
title: debian系统apt命令摘要
date: 2020-02-28 16:11:46
tags:
- debian
categories: 
- linux
- debian
keywords:
- 命令行软件包管理器 apt
- apt-get
- apt-cache
- sources.list
- apt.conf
- 什么是apt

---
> apt

```shell
jnjxmgl@debian:~$ apt

apt 1.8.2 (amd64)
用法： apt [选项] 命令

命令行软件包管理器 apt 提供软件包搜索，管理和信息查询等功能。
它提供的功能与其他 APT 工具相同（像 apt-get 和 apt-cache），
但是默认情况下被设置得更适合交互。

常用命令：
  list - 根据名称列出软件包
  search - 搜索软件包描述
  show - 显示软件包细节
  install - 安装软件包
  reinstall - reinstall packages
  remove - 移除软件包
  autoremove - 卸载所有自动安装且不再使用的软件包
  update - 更新可用软件包列表
  upgrade - 通过 安装/升级 软件来更新系统
  full-upgrade - 通过 卸载/安装/升级 来更新系统
  edit-sources - 编辑软件源信息文件

参见 apt(8) 以获取更多关于可用命令的信息。
程序配置选项及语法都已经在 apt.conf(5) 中阐明。
欲知如何配置软件源，请参阅 sources.list(5)。
软件包及其版本偏好可以通过 apt_preferences(5) 来设置。
关于安全方面的细节可以参考 apt-secure(8).
                                         本 APT 具有超级牛力。
```

> apt-get

```shell

jnjxmgl@debian:~$ apt-get
apt 1.8.2 (amd64)
用法： apt-get [选项] 命令
　　　 apt-get [选项] install|remove 软件包1 [软件包2 ...]
　　　 apt-get [选项] source 软件包1 [软件包2 ...]

apt-get 可以从认证软件源下载软件包及相关信息，以便安装和升级软件包，
或者用于移除软件包。在这些过程中，软件包依赖会被妥善处理。

常用命令：
  update - 取回更新的软件包列表信息
  upgrade - 进行一次升级
  install - 安装新的软件包(注：软件包名称是 libc6 而非 libc6.deb)
  reinstall - Reinstall packages (pkg is libc6 not libc6.deb)
  remove - 卸载软件包
  purge - 卸载并清除软件包的配置
  autoremove - 卸载所有自动安装且不再使用的软件包
  dist-upgrade - 发行版升级，见 apt-get(8)
  dselect-upgrade - 根据 dselect 的选择来进行升级
  build-dep - 为源码包配置所需的编译依赖关系
  clean - 删除所有已下载的包文件
  autoclean - 删除已下载的旧包文件
  check - 核对以确认系统的依赖关系的完整性
  source - 下载源码包文件
  download - 下载指定的二进制包到当前目录
  changelog - 下载指定软件包，并显示其变更日志（changelog）

参见 apt-get(8) 以获取更多关于可用命令的信息。
程序配置选项及语法都已经在 apt.conf(5) 中阐明。
欲知如何配置软件源，请参阅 sources.list(5)。
软件包及其版本偏好可以通过 apt_preferences(5) 来设置。
关于安全方面的细节可以参考 apt-secure(8).
                                         本 APT 具有超级牛力。
```

> apt-cache

```shell
jnjxmgl@debian:~$ apt-cache
apt 1.8.2 (amd64)
用法： apt-cache [选项] 命令
       apt-cache [选项] show 软件包1 [软件包2 ...]

apt-cache 可以查询和显示已安装和可安装软件包的可用信息。
它专门工作在本地的数据缓存上，而这些缓存可以通过比如
apt-get 的 'update' 命令来更新。如果距离上一次更新的时间太久，
那么它显示的信息可能就会过时。不过作为交换，apt-cache 不依赖
当前软件源的可用性（比如：离线状态）。

常用命令：
  showsrc - 显示源文件的各项记录
  search - 根据正则表达式搜索软件包列表
  depends - 显示该软件包的依赖关系信息
  rdepends - 显示所有依赖于该软件包的软件包名字
  show - 以便于阅读的格式介绍该软件包
  pkgnames - 列出所有软件包的名字
  policy - 显示软件包的安装设置状态

参见 apt-cache(8) 以获取更多关于可用命令的信息。
程序配置选项及语法都已经在 apt.conf(5) 中阐明。
欲知如何配置软件源，请参阅 sources.list(5)。
软件包及其版本偏好可以通过 apt_preferences(5) 来设置。
关于安全方面的细节可以参考 apt-secure(8).
```

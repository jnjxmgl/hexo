---
title: CentOS7 开启BBR加速
date: 2019-07-14 14:17:11
tags: 
- centos7
- BBR
categories: linux
---

## 升级内核

**开启 BBR 要求 4.10 以上版本 Linux 内核，可使用如下命令查看当前内核版本：**

    uname -r

可以得到类似如下的结果：

    3.10.0-514.10.2.el7.x86_64

如果当前内核版本低于 4.10，可使用 ELRepo 源更新：

    sudo rpm --import https://www.elrepo.org/RPM-GPG-KEY-elrepo.org
    sudo rpm -Uvh http://www.elrepo.org/elrepo-release-7.0-2.el7.elrepo.noarch.rpm
    sudo yum --enablerepo=elrepo-kernel install kernel-ml -y

安装完成后，查看已安装的内核：

    rpm -qa | grep kernel

得到结果如下：

    kernel-3.10 .0-123. el7.x86_64
    kernel-headers-3.10 .0-514.16 .1.el7.x86_64
    kernel-ml-4.11 .0-1. el7.elrepo.x86_64
    kernel-tools-3.10 .0-514.16 .1.el7.x86_64
    kernel-3.10 .0-514.16 .1.el7.x86_64
    kernel-tools-libs-3.10 .0-514.16 .1.el7.x86_64

在输出中看到 kernel-ml-4.11.0-1.el7.elrepo.x86_64 类似的内容，表示安装成功。

修改grub2引导
执行：

    sudo egrep ^menuentry /etc/grub2.cfg | cut -f 2 -d \'

会得到如下结果：

    CentOS Linux 7 Rescue a0cbf86a6ef1416a8812657bb4f2b860(4.11 .0-1. el7.elrepo.x86_64)
    CentOS Linux(4.11 .0-1. el7.elrepo.x86_64) 7(Core)
    CentOS Linux(3.10 .0-514.16 .1.el7.x86_64) 7(Core)
    CentOS Linux(3.10 .0-123. el7.x86_64) 7(Core)
    CentOS Linux(0-rescue-2 d3f9371c20d3e90a544ccc814d485e3) 7(Core)

由于序号从0开始，设置默认启动项为1并重启系统：

    sudo grub2-set-default 1
    reboot

重启完成后，重新登录并重新运行uname命令来确认你是否使用了正确的内核：

    uname -r

得到如下结果则升级成功：

    4.11.0-1.el7.elrepo.x86_64
开启BBR
执行：

    echo 'net.core.default_qdisc=fq' | sudo tee -a /etc/sysctl.conf
    echo 'net.ipv4.tcp_congestion_control=bbr' | sudo tee -a /etc/sysctl.conf
    sudo sysctl -p

完成后，分别执行如下命令来检查 BBR 是否开启成功：

    sudo sysctl net.ipv4.tcp_available_congestion_control# 输出应为 net.ipv4.tcp_available_congestion_control = bbr cubic reno
    sudo sysctl -n net.ipv4.tcp_congestion_control # 输出应为 bbr
    lsmod | grep bbr# 输出应类似 tcp_bbr 16384 28


---
title: CentOS7升级安装linux内核
date: 2019-10-31 13:32:03
tags: 
- centos7
- kernel
categories: linux
keywords:
- 升级内核
- 删除旧内核
- 修改grub2引导
- 安装相同内核版本的`devel`或者`headers`、`tools`等
- enablerepo=elrepo-kernel
---

## 升级内核

**开启 BBR 要求 4.10 以上版本 Linux 内核，可使用如下命令查看当前内核版本：**

    uname -r

可以得到类似如下的结果：

    3.10.0-1062.el7.x86_64

如果当前内核版本低于 4.10，可使用 ELRepo 源更新：

    sudo rpm --import https://www.elrepo.org/RPM-GPG-KEY-elrepo.org    
    sudo rpm -Uvh https://www.elrepo.org/elrepo-release-7.0-4.el7.elrepo.noarch.rpm
    sudo yum --enablerepo=elrepo-kernel install kernel-ml -y

安装完成后，查看已安装的内核：

    sudo rpm -qa | grep kernel

得到结果如下：

    kernel-3.10.0-1062.el7.x86_64
    kernel-tools-3.10.0-1062.el7.x86_64
    kernel-tools-libs-3.10.0-1062.el7.x86_64
    kernel-ml-5.3.8-1.el7.elrepo.x86_64

在输出中看到 kernel-ml-5.3.8-1.el7.elrepo.x86_64 类似的内容，表示安装成功。

修改grub2引导
执行：

    sudo egrep ^menuentry /etc/grub2.cfg | cut -f 2 -d \'

会得到如下结果：

    CentOS Linux (5.3.8-1.el7.elrepo.x86_64) 7 (Core)
    CentOS Linux (3.10.0-1062.el7.x86_64) 7 (Core)
    CentOS Linux (0-rescue-49749ad159a449e69c079ae4285e2036) 7 (Core)

由于序号从0开始，设置默认启动项为0并重启系统：

    sudo grub2-set-default 0

最后，重新生成GRUB配置

    sudo grub2-mkconfig -o /boot/grub2/grub.cfg

有如下提示:

    Generating grub configuration file ...
    Found linux image: /boot/vmlinuz-5.3.8-1.el7.elrepo.x86_64
    Found initrd image: /boot/initramfs-5.3.8-1.el7.elrepo.x86_64.img
    Found linux image: /boot/vmlinuz-3.10.0-1062.el7.x86_64
    Found initrd image: /boot/initramfs-3.10.0-1062.el7.x86_64.img
    Found linux image: /boot/vmlinuz-0-rescue-49749ad159a449e69c079ae4285e2036
    Found initrd image: /boot/initramfs-0-rescue-49749ad159a449e69c079ae4285e2036.img
    done

重启

    reboot

重启完成后，重新登录并重新运行uname命令来确认你是否使用了正确的内核：

    uname -r

得到如下结果则升级成功：

    5.3.8-1.el7.elrepo.x86_64

删除旧内核

    yum -y remove kernel* #说明：删除旧内核的目的是为了防止 yum 更新旧版内核之后覆盖了 grub 默认启动项

如果需要安装相同内核版本的`devel`或者`headers`、`tools`等,请运行如下命令:

    sudo yum --enablerepo=elrepo-kernel list | grep kernel-ml

结果如下显示:

    kernel-ml.x86_64                        5.3.8-1.el7.elrepo             @elrepo-kernel
    kernel-ml-devel.x86_64                  5.3.8-1.el7.elrepo             elrepo-kernel
    kernel-ml-doc.noarch                    5.3.8-1.el7.elrepo             elrepo-kernel
    kernel-ml-headers.x86_64                5.3.8-1.el7.elrepo             elrepo-kernel
    kernel-ml-tools.x86_64                  5.3.8-1.el7.elrepo             elrepo-kernel
    kernel-ml-tools-libs.x86_64             5.3.8-1.el7.elrepo             elrepo-kernel
    kernel-ml-tools-libs-devel.x86_64       5.3.8-1.el7.elrepo             elrepo-kernel

其中`kernel-ml`我们已经安装过了,下面执行:

    sudo yum --enablerepo=elrepo-kernel install kernel-ml*

会自动滤过我们安装后的内核,转而全装其他组件。

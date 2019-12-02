---
title: 如何在centos 7上设置nfs服务器和客户端
date: 2019-10-31 16:21:01
tags: 
- centos7
- NFS
categories: linux
---
NFS是基于Sun的RPC（远程过程调用）构建的分布式文件系统协议。NFS在LAN网络环境中通常用于共享目录和文件，并且在许多网络附加存储（NAS）系统和云存储后端中也都采用了NFS。在典型的NFS部署中，NFS服务器将其本地文件系统的一部分导出为NFS共享，并且一个或多个远程NFS客户端在其自己的文件系统中安装和访问导出的共享。

这是在`NFS服务器和客户端（都在CentOS 7上运行）之间设置NFS共享的方法`。

> 准备

在本教程中，将使用两台CentOS 7主机，一台用于NFS服务器，另一台用于NFS客户端。NFS中的许多读/写操作都涉及访问和更新时间戳，但是NFS本身没有任何机制可以在服务器和客户端之间同步时间。为了可靠地进行NFS操作，因此强烈建议在每台NFS服务器和客户端主机上`设置NTP`，以避免它们之间的时钟偏差。

>在CentOS 7上设置NFS服务器

使用yum安装必要的NFS守护程序和实用程序：
```
sudo yum install nfs-utils
```
接下来，准备要通过NFS导出的文件夹。在此示例中，我将导出本地文件系统的`/var/nfs`。
```
mkdir /var/nfs
chmod 777 /var/nfs
```
现在，使用文本编辑器打开`/etc/exports`，并使用以下其他导出选项指定该文件夹。
```
/var/nfs 192.168.1.0/24(rw,sync,no_subtree_check)
```
使用的导出选项指示以下内容。显然，您可以自定义选项以满足您的要求。

* 192.168.1.0/24：只有IP地址为192.168.1.0/24的NFS客户端才能访问NFS共享。其他形式的ACL（例如192.168.1.175，*.imgl.net）也是可能的。
* rw：此选项允许NFS客户端在共享上执行读取和写入操作。
* sync：此选项使NFS服务器仅在成功将写入提交到存储后才回复写入请求，从而以性能为代价提高了可靠性。
* no_subtree_check：此选项禁用子树检查，该子树检查可验证访问的文件是否具有正确的权限并属于导出的树。禁用子树检查可以以安全为代价提高性能。

注:使用`*.imgl.net`的目的是想建立域名imgl.net解析的服务器都可以访问的效果。

接下来，启动必要的服务并将其设置为在启动时自动启动。
```
systemctl enable rpcbind
systemctl start rpcbind
systemctl enable nfs-server
systemctl start nfs-server
```
如果修改`/etc/exports`，则需要通过运行以下命令来激活更改：
```
 exportfs -a
 ```
 最后，您应该按照以下步骤在默认防火墙中打开NFS服务使用的端口。
```
firewall-cmd --zone=public --add-service=nfs --permanent
firewall-cmd --zone=public --add-service=rpc-bind --permanent
firewall-cmd --zone=public --add-service=mountd --permanent
firewall-cmd --reload
```
要检查文件夹是否成功导出，请运行：
```
exportfs
```
*/var/nfs      	192.168.1.0/24*

输出应指示将哪些文件夹（例如/var/nfs）导出到谁（例如:192.168.1.0/24）。

> 在CentOS 7上设置NFS客户端

现在，让我们看看如何设置远程NFS客户端以装载和访问导出的NFS共享。

在单独的CentOS 7主机上，安装必要的NFS守护程序和实用程序。
```
yum install nfs-utils
```
为NFS共享准备本地安装点。
```
mkdir /mnt/nfs
```
继续，如下所示，使用`mount`命令安装远程NFS共享。在此示例中，NFS服务器的IP地址为192.168.1.174。
```
mount -t nfs 192.168.1.174:/var/nfs /mnt/nfs
```
直接执行`mount`命令

{% asset_img 26056223006.jpg mount %}

`df`命令应显示从NFS共享额外的存储空间。

{% asset_img 26056223116.jpg df %}

要卸载NFS共享，请使用umount命令。
```
umount /mnt/nfs
```
如果要在引导时自动安装远程NFS共享，则可以在/etc/fstab中添加以下行。
```
vi /etc/fstab
```

`192.168.1.174:/var/nfs    /mnt/nfs  nfs defaults 0 0`

请注意，已挂载的NFS共享会将由根创建的任何文件的所有权更改为`nfsnobody`用户。这是一个称为`root squash`的NFS功能，出于安全原因，从本质上减少了远程超级用户的访问权限。您可以使用`no_root_squash`导出选项禁用`root squash` ，但不建议这样做，因为任何远程root用户都可能以root特权不小心弄乱了共享文件夹。
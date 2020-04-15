---
title: 在freebsd开启openssh登陆
date: 2020-02-28 15:59:31
tags: sshd
categories: freebsd
keywords:
- freebsd
- 不支持ssh远程访问
- 宿主机
- 修改sshd_config
- PermitRootLogin
- PasswordAuthentication
- 重启sshd服务

---

默认情况下 `freebsd` 全系并不支持ssh远程访问,可已通过直接操作宿主机实现.

>修改sshd配置

修改sshd_config的配置,将
```
#PermitRootLogin no
#PasswordAuthentication no
```
修改成
```
#PermitRootLogin yes
#PasswordAuthentication yes
```
*注意这两项前面得#需要去掉*

>重启sshd服务

```
service sshd restart
```
<div class="_jj9m6hxydxf"></div>
<script type="text/javascript">
    (window.slotbydup = window.slotbydup || []).push({
        id: "u5993439",
        container: "_jj9m6hxydxf",
        async: true
    });
</script>

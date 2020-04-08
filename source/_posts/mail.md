---
title: 搭建mail邮箱服务器
date: 2019-07-14 14:33:21
tags:
- postfix
- centos7
- dovecot
categories: linux
keywords:
- POSTFIX
- DOVECOT
- TLS认证
- POSTFIX+DOVECOT
- 搭建mail邮箱服务器
- imap 
- pop3

---

## 一、准备工作

1. 域名一个domain.com(以下地方改成你自己的)

2. 接入公网的云主机或物理主机

3. 一个可用的SSL证书，可以去腾讯云和阿里云申请，申请域名要使用你自己的mai.domain.com

4. 将houstname主机名称改为mail.domain.com，可用命令 `hostnamectl set-hostname " mail.domain.com"` 

5. 在/etc/hosts中增加本地解析， `vi /etc/hosts` 加入一行

    100.100.100.100 mail.domain.com# 注： 100.100.100.100 改成你主机的公网ip

## 二、安装软件

1. 安装postfix，执行命令：

        yum install postfix -y

2. 配置postfix，postfix默认配置文件在/etc/postfix/main.cf，但是为了更改过中的错误，我们使用postconf -e命令，例如:postconf -e "myhostname=mail.domain.com"，这里的双引号也可用单引号。如下我列出来了需要修改的内容，其中含义自行去postfix官方文档查阅：

        myhostname=mail.domain.com
        mydomain=domain.com
        myorigin=$mydomain
        inet_interfaces=all
        inet_protocols=all
        mydestination=$myhostname, localhost.$mydomain, localhost, $mydomain
        home_mailbox=Maildir / #申请的证书存放位置可自定义， 路径是绝对路径
        smtpd_tls_cert_file=/etc/pki/tls/certs/postfix.pem
        smtpd_tls_key_file=/etc/pki/tls/certs/postfix.key
        smtpd_sasl_security_options=noanonymous
        broken_sasl_auth_clients=yes #注 使用postconf命令时 $、 / 可能无法被加入到配置文件中， 可使用\ $\ / 进行转义

  

3. 配置master.cf，将如下内容前面的#全部去掉

        smtp inet n-n--smtpd
        submission inet n-n--smtpd -
            o smtpd_tls_security_level=encrypt -
            o smtpd_sasl_auth_enable=yes -
            o smtpd_client_restrictions=permit_sasl_authenticated, reject -
            o milter_macro_daemon_name=ORIGINATING
        smtps inet n-n--smtpd -
            o smtpd_tls_wrappermode=yes -
            o smtpd_sasl_auth_enable=yes -
            o smtpd_client_restrictions=permit_sasl_authenticated, reject -
            o milter_macro_daemon_name=ORIGINATING

4. 安装dovecot

        yum install dovecot -y

5. 配置 dovecot

打开 [/etc/dovecot/dovecot.conf] 文件，配置将

    protocols=imap pop3

前的#号去掉，保存。

编辑[/etc/dovecot/conf.d/10-auth.conf] ，配置

    disable_plaintext_auth=yes
    auth_mechanisms=plain login# 增加login

保存。

编辑[/etc/dovecot/conf.d/10-mail.conf] ，配置

    mail_location=maildir: ~/Maildir

保存。

编辑[/etc/dovecot/conf.d/10-master.conf] ，配置

    unix_listener auth-userdb {#
        mode=0600
        user=postfix
        group=postfix

    }

编辑[/etc/dovecot/conf.d/10-ssl.conf] ，配置

    ssl=required

编辑[/etc/dovecot/conf.d/15-mailboxes.conf]

    namespace inbox {#
        找到这个地方增加 inbox=yes
        inbox=yes
            ....

最后安装saslauthd

    yum -y install cyrus-sasl
    yum -y install cyrus-sasl-plain
    service saslauthd start# 启动
    chkconfig saslauthd on# 开机启动

配置完毕，系统用户就是邮箱用户 ，一般使用root是被认为粗鲁的，所以创建admin账户，执行脚本如下:

    useradd admin
    passwd admin# 设置密码， 会被要求输入两次密码进行确认

最后记得开启25、465、993、995端口

    firewall-cmd --zone=public --permanent --add-port =25/tcp
    firewall-cmd --zone=public --permanent --add-port=465/tcp
    firewall-cmd --zone=public --permanent --add-port=993/tcp
    firewall-cmd --zone=public --permanent --add-port=995/tcp

增加域名解析，如下图

![域名配置](https://res.imgl.net/hexo/mail/image-1-1024x460.png "域名配置")

注：A记录需要设置@解析和mail解析，ip换成你的，CNAME解析设置这俩就行，MX解析记得设置成你的域名mail.domain.com 优先级随意

然后我们就能使用admin账户再foxmail上登陆邮箱了。

![邮箱配置](https://res.imgl.net/hexo/mail/image-2.png "邮箱配置")


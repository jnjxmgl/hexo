---
title: openssl生成自签名证书
date: 2020-06-08 11:52:56
tags: 
- openssl
categories: 
- linux
keywords:
- 更新Debian服务器
- 更新Debian服务器软件包
---
>第一步,在终端执行如下代码,前提是服务器或者本地主机已经安装openssl

    openssl req -x509 -nodes -newkey rsa:2048 -sha256 -keyout pure-ftpd.pem -out

我这里希望给自己搭建的pure-ftpd创建一个证书,证书的名字是`pure-ftpd.pem`,这里面包含有证书和私钥两部分,再这同一个文件中,当然你也可以从里面摘取分开来保存,执行完成后会有如下提示:

    Generating a RSA private key
    ....................................................................................................................................+++++
    .........................................................................................................................................................................+++++
    writing new private key to 'pure-ftpd.pem'
    -----
    You are about to be asked to enter information that will be incorporated
    into your certificate request.
    What you are about to enter is what is called a Distinguished Name or a DN.
    There are quite a few fields but you can leave some blank
    For some fields there will be a default value,
    If you enter '.', the field will be left blank.
    -----
    Country Name (2 letter code) [AU]:CN
    State or Province Name (full name) [Some-State]:Shandong
    Locality Name (eg, city) []:Jining
    Organization Name (eg, company) [Internet Widgits Pty Ltd]:IMGL
    Organizational Unit Name (eg, section) []:IMGL
    Common Name (e.g. server FQDN or YOUR name) []:IMGL.NET
    Email Address []:nihao@qq.com


这里会让你输入以下参数信息,你可以`直接回车`不予理睬,但这样可能让你生成的证书不够文雅,不能够展示一些信息,根据我上面的提示魔方成你的内容,如下:

1. Country Name (2 letter code)

国家名称（2个字母代码）,它举了个例子`AU`,我们这里是中国,我就填的`CN`,你也可以使用`CC`'科科斯群岛' `SG`(新加坡) `US`(美国) `UK`(英国) `HK`(香港) `JP`(日本)等国家代码

2. State or Province Name (full name) [Some-State]

州或省名（全名）[某些州] ,我们这里是山东,我就输入`Shandong`,你也可以在美国的话输入一些美国的州名

3. Locality Name (eg, city) []

地区名称（如城市）[] , 我这里输入`Jining`

4. Organization Name (eg, company) [Internet Widgits Pty Ltd]

组织名称（例如，公司）[互联网Widgits私人有限公司] ,我这里输入我的域名,你也可已输出类似:`baidu .ltd`

5. Organizational Unit Name (eg, section)

组织单位名称（例如，部门）,假如你们公司不同的部门有不同部门的证书需求,比如财务,你可以输入`Finance`

6. Common Name (e.g. server FQDN or YOUR name)

公用名（例如服务器FQDN或您的姓名）,你可以输入财务部门的专用服务器主机名;比如`finance.imgl.net`

7. Email Address []

邮箱[] , 这个就随意了

以上信息不会提交到任何地方或者组织,只用于证书签名使用,请放心随意填写
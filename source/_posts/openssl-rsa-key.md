---
title: 使用openssl生成普通RSA密钥对
date: 2019-12-04 16:09:01
tags: 
- php
- openssl
categories: linux
---
> 生成私钥
```shell
openssl genrsa -out rsa_private_key.pem 2048  #生成私钥  这里的2048要与php中的openssl.cnf配置文件中的一致,否则在php中使用会出错
```
> 将私钥转换为pkcs8格式
```shell
openssl pkcs8 -topk8 -inform PEM -in rsa_private_key.pem -outform PEM -nocrypt -out private_key.pem
```
>生成公钥
```shell
openssl rsa -in rsa_private_key.pem -pubout -out rsa_public_key.pem
```
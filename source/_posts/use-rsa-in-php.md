---
title: 在php中使用RSA加密算法
date: 2019-12-04 16:17:22
tags:
- php
- openssl
categories: linux
---
> 前情提要

首先你需要先拥有公钥和私钥文件,或者密钥字符串,如果没有请看我写的`使用openssl生成普通RSA密钥对`.

> 上代码

```php


<?php





$private_key = '-----BEGIN PRIVATE KEY-----
MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDeAtc1uTng7nJd
HfKRMFPBRrvYtxAKViYrONkkCFW5cit9HgPlX5NwWDv7+8yZ4b7UgCPn7pqzAL8g
+9omtFj5DaXQP0onr8z1b7KJ0zxfiKL2PaFqUhNwiQLu8NPdeCtPVtabsPvF5fqh
thdm0+7Ryi90o60sLO4573baqpP4WHp7PGhPbvrsHS6tNwRvs25+aUCddPxQrv40
7MysjeJG60c+9YZrcP6C/hY/p5pNcTV2uFg3Szu4s5OIsa3qxk1CYjSFSKyMoOLW
L3hDPznm0LH9ZFEb6yxWCGF7owVMUV9UJBs6wT/9KNREKrb040vzPP7spdNEtrgQ
SsMczqlHAgMBAAECggEBAItTyw3bLZic7EbF6Zn2c0mjg3XxBO8Hu7J6XcOAO5RD
M0m1EYrcnWHu7c4o6vFTu/gOZvpCQvG9sTUY+YI6wm+iggIcBgS8Dmulaq2WVJg7
3tGcQfXAhpSkV6PunXeq17tV6x8QRiGfP9hGt47/yCv+sCOKKL7Ff8f6IWP/kxdR
+7LJOH92hBH1UccTOyZRbSQ5TT/HKebtZWy5fA/ykLU1bjAzFPtUMrFtEn8bcj9V
ie41TC2aVaKoBZsdhpYj9HhAFkcb3EF9c09MPsamAb/r3FP0LrD/S1frcwwLjNaO
MADsyPaTINjgYNfmqtdS9IRHCfOAj+fTiiEf+lqzXxECgYEA/2sJoXf+TFT7qer2
gF3joaTEMfE7twDyXuq6/w/folFhTY9/hwSdc5dLVQbDCGlcqsrdoH2Scu09evWu
88bEKL1GbxEy1LUGHWvOzxdjx7PTh8e67/qghyjFFt5sP0XOQWsvnWEFyKY88koH
k+ZYBYbgw8stxZZ2nB1quC04a7kCgYEA3oRR2lztQ785vsq5J3xTLI0exvMNy75l
qPr3O9EvY072gAVcbun762/0PC66mNt5LMEPJ7hi+XNIlQdWDDXbOXN4taR46dDb
90YeEpZILrWXCnqseAjPuap6kEk7UwFu1zttt5XGTNI5SKue2mToYSL427osVpo0
kc7sPuE8PP8CgYAJtkw7c5Cb3m9jWqfc4bUSJG3BaGbY4pRUv6A0qqnaRjXo+Rfk
TmyeRJZ8uTEZVMhNRkF/JMc4z4SS9FyesGQtGGVhO8ovBGMjI0JN5ZnJsjDM7O9S
yLjp6sbzw6liDol1O1ooJdROeOPAYsg++3dFXoeValhnNv1zmjrAnheTIQKBgQCZ
ncfUdGroZk+7Q8DnXZEeJJ6mwo03p04PhpLAHP4WFSFf6FOTzr04IKYAFlKDzKCv
IOkRht3tUIU/PT1OWK/rzaf73nwLD0GMSD/inRVgCcUoWuBOTeb6SpMqoSPvfgHD
XSe1ohSwXEFnxfN42kkDJl2fc7vAXd0E/FFkZ6JqiQKBgHLybFQaIMTLziRHcCDS
0YA3knGS3x6dRDMaYyqocj2AdtItcAHg5TExVOqlIVBuyT38GBWXy8i53f2nCqMM
NMfdsl6Ewf5nMASkY3wOtMKLNB/0f+LEb/pJS62woNNH8mpf0FGeNeiDtDNddgTn
Hte7WLZGB3uXfoMBbM0E4qTo
-----END PRIVATE KEY-----
';
$public_key = '-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA3gLXNbk54O5yXR3ykTBT
wUa72LcQClYmKzjZJAhVuXIrfR4D5V+TcFg7+/vMmeG+1IAj5+6aswC/IPvaJrRY
+Q2l0D9KJ6/M9W+yidM8X4ii9j2halITcIkC7vDT3XgrT1bWm7D7xeX6obYXZtPu
0covdKOtLCzuOe922qqT+Fh6ezxoT2767B0urTcEb7NufmlAnXT8UK7+NOzMrI3i
RutHPvWGa3D+gv4WP6eaTXE1drhYN0s7uLOTiLGt6sZNQmI0hUisjKDi1i94Qz85
5tCx/WRRG+ssVghhe6MFTFFfVCQbOsE//SjURCq29ONL8zz+7KXTRLa4EErDHM6p
RwIDAQAB
-----END PUBLIC KEY-----
';



$pi_key =  openssl_pkey_get_private($private_key); #判断私钥是否是可用的，可用返回资源id Resource id

$pu_key = openssl_pkey_get_public($public_key); #判断公钥是否是可用的,可用返回资源id Resource id

#使用公钥和私钥文件写法

#$private_key = "private_key.pem";
#$public_key = "rsa_public_key.pem";
#$pi_key =  openssl_pkey_get_private(file_get_contents($private_key)); #判断私钥是否是可用的，可用返回资源id Resource id
#$pu_key = openssl_pkey_get_public(file_get_contents($public_key)); #判断公钥是否是可用的,可用返回资源id Resource id

#使用公钥和私钥文件写法结束


echo "资源id\n";
print_r($pi_key);
echo "\n";
echo "资源id\n";
print_r($pu_key);
echo "\n";


$data = "测试而已"; //原始数据
$encrypted = "";
$decrypted = "";
echo "---------------------------------------\n";
echo "待加密数据:", $data, "\n";

echo "---------------------------------------\n";
echo "私钥加密元数据:\n";

openssl_private_encrypt($data, $encrypted, $pi_key); #私钥加密
$encrypted = base64_encode($encrypted); #由于加密后的内容通常含有特殊字符，需要base64转换下，在http请求时要注意urlencode问题
echo $encrypted, "\n";

echo "公钥解密得到元数据:\n";

openssl_public_decrypt(base64_decode($encrypted), $decrypted, $pu_key); #私钥加密的内容通过公钥可用解密出来
echo $decrypted, "\n";

echo "---------------------------------------\n";
echo "公钥加密元数据:\n";

openssl_public_encrypt($data, $encrypted, $pu_key); //公钥加密
$encrypted = base64_encode($encrypted);
echo $encrypted, "\n";

echo "私钥解密得到元数据:\n";
openssl_private_decrypt(base64_decode($encrypted), $decrypted, $pi_key); //私钥解密
echo $decrypted, "\n";


```
最后保存成php文件哈,我这里保存为index.php.

>执行

在linux控制台输入如下命令:

```shell

php index.php #cd到index.php所在的目录

```
当然如果搭建了web访问php的方式,可以通过web访问.

输出结果如下:

    资源id
    Resource id #6
    资源id
    Resource id #8
    ---------------------------------------
    待加密数据:123
    ---------------------------------------
    私钥加密元数据:
    kDNIsmsqw0/JReqOL8J0QRb+/MPInk0XPbWfreL2+anLinDuHHvSEehca4WhvCtRRU5DySlNpgXmUOd7xF4z9EfQEH4KE2TYD5km3O5h1+aPz6AUbmbNDDcqOhob4Ldmbaw5O9m1cdYNXRLyT3CD1773CriSrcuWsQtxyxwa6jzBO8ZP3Ry5bKKAANbCLamwZ0thioIO3PnOoVGsFr6JsM+/kyIKOKBqhJR071cnl3liNt8GwkOIkmj3PWp+tb3+wxEO9ClLH6SNMvE9c2zbTZ+L3BfLWW4EmusNIIcfq9ifi9yFfiJPeNZhilMXH01B4gcRnsBS8vOfiK6NXBLEJw==
    公钥解密得到元数据:
    123
    ---------------------------------------
    公钥加密元数据:
    WVIdFhmNcdk5VcDm0IfkcWqA1oCdrYCZ/E25Mn7Ku/eLfe8AHm4TzQEzGFyrGdT/NlO3mw7ghqoDC+tvGAhUmuOoxXvCTBwGD4+qSYLQ3dpvqt/F0Q8bwNdbZtciczAR4U9lnwyqQ6AlLZcus3ULfMaBtFPaLQqyWVYSM2vNJ4t3zlcDtZMH/gCUMTasSrFVjND6YgA13SReKlX67TJR1KzjrT14QWgSiQaW3Bw4GY/Db7MGocsj0TnYgXoXg47remzgpll1U7l2TpLgq9WEUqs5YMWS3VQ+MAFBN2ghrSzy91npUReQNZdglSmWAna8Dg+/WgjVzj4waugMqLOzGw==
    私钥解密得到元数据:
    123
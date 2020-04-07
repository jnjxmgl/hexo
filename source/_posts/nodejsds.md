---
title: NODEJS 定时请求指定HTTP地址任务, 以10S为例
date: 2019-07-14 14:51:27
tags: nodejs
categories: javascript
keywords:
- NODEJS
- 定时请求HTTP地址任务
---

``` javascript
var request = require('request');
var fs = require('fs');
setInterval(function() {

        request('http: //127.0.0.1/index.php/api/api/open', function(error, response) {
            fs.writeFile('open.txt', '状态码: ' + response.statusCode + '时间：' + new Dat() + '\r\ n', {
                    encoding: 'utf8',
                    mode: '0666',
                    flag: 'a'
                },
                function(err) {
                    if (err) {
                        return console.error(err);
                    }
                });
        });
    },
    10000);
```


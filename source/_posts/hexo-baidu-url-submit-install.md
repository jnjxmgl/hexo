---
title: 'hexo-baidu-url-submit,百度收录必备良药'
author: JNJXMGL
tags:
  - baidu
categories:
  - SEO
date: 2020-04-06 14:41:00
---

> 安装
首先，在Hexo根目录下，安装插件：

```shell
npm install hexo-baidu-url-submit --save
```

然后，同样在根目录下，把以下内容配置到`_config.yml`文件中:

{% asset_img baidu_20200406152200.png 百度搜索资源平台 %}

    baidu_url_submit:
    count: 1 ## 提交最新的一个链接,这里要根据baidu_urls.txt文档中的实际链接数量
    host: www.imgl.net ## 在百度站长平台中注册的域名
    token: your_token ## 请注意这是您的秘钥， 所以请不要把博客源代码发布在公众仓库里!
    path: baidu_urls.txt ## 文本文档的地址， 新链接会保存在此文本文档里

其次，记得查看_config.yml文件中`url`的值， 必须包含是百度站长平台注册的域名（一般有www）， 比如:

    # URL
    url: http://www.imgl.net
    root: /
    permalink: :year/:month/:day/:title
    
    
最后，加入新的deployer:

	deploy:
  	- type: baidu_url_submitter   # 百度
  	# - type: baidu_xz_url_submitter # 百度熊掌号
  	- bucket: imgl.net
  	- type: baidu_url_submitter ## 这是新加的
    

执行`hexo deploy`的时候，新的连接就会被推送了。

##### 实现原理

推送功能的实现，分为两部分：

新链接的产生， `hexo generate` 会产生一个文本文件，里面包含最新的链接

新链接的提交， `hexo deploy` 会从上述文件中读取链接，提交至百度搜索引擎
---
title: 在iis配置thinkphp的url重写
date: 2020-07-22 10:43:58
tags:
- iis
- url rewrite
categories:
- iis
keywords:
- 在iis配置thinkphp的url重写
---

部分情况下我们需要使用iis作为php的web服务器,nginx和apache的有没有喷子会说我菜的连nginx和apache都不会用

其实web服务器的优缺点是都存在的,nginx和apache也不是绝对的完美,iis也并非想象中的一无是处
> 第一步

闲话少说,开始第一步操作,从windows的iis官方网站下载url重写扩展,地址如下

    https://www.iis.net/downloads/microsoft/url-rewrite
    

![下载](https://res.imgl.net/hexo/iis-url-rewrite/fa7b1dc6d98c6dd11da97e3543ae19f.png "下载")

需要说明的是这个扩展的工作环境仅限于IIS7及以上版本(IIS 7, IIS 7.5, IIS 8, IIS 8.5, IIS 10)

注:如过你的服务器安装了如下图示的组件


![web平台安装程序](https://res.imgl.net/hexo/iis-url-rewrite/31fb2b11fa8d489d87b03eec859db53.png "web平台安装程序")

你可以打开这个从里面搜索`url`关键词,找到如下图示然后选择右边的`添加`,最后单击安装

![搜索安装](https://res.imgl.net/hexo/iis-url-rewrite/11ea62874149107f223fdf631ce1e3a.png "搜索安装")

如过你没有上图所示的组件,你通过前面的链接,然后往下拉,选择简体中文版下载下载,如下图示:

![下载中文版](https://res.imgl.net/hexo/iis-url-rewrite/4e5447e36b7e2e8d8e41f26872bdfe7.png "下载中文版")

将下载好的exe运行安装就行,直接下一步默认就行,这里不做演示,需要注意的是安装完成以后

> 第二步

既然选择了iis,说明你应该相对了解他的配置作用域,我们为了其它站点的url不至于也一起被重写,我们这里选择了特定的站点,然后单击右侧的`url 重写`,然后找到右上角的`导入规则`选项,如下图示:

![搜索安装](https://res.imgl.net/hexo/iis-url-rewrite/a4c3f2eaa3409caf589e8d5932920e3.png "搜索安装")

我这里使用的是tp框架,我们可以按照上图所示的方法将apache的url重写规则文件通过图中的`导入`按钮,直接转化为iis可已识别的url从写,然后不要忘记点击图右上角的`应用`

到这里,iis配置url重写的就讲完了,像laraval等框架的url重写,同样可以使用,该方法进行处理
---
title: windows域服务器搭建
date: 2019-07-19 19:38:51
tags: 域服务器
categories: windows
keywords:
- Windows server 2008
- 服务管理器
- Active Directory
- 创建域组织及用户

---
本安装教程在Windows server 2008 r2上测试通过, 其他windows服务器安装类似

> 第一步

我们需要把域服务器的ip地址设置成静态ip地址, 我这里的dns是虚拟机分配的, 正常使用过程用一般是我们内网的路由器ip, 常见的有 `192.168.1.1` 等等当然ip也是 `192.168.1.*` 号段的, 如下图:

![配置静态ip](https://res.imgl.net/hexo/yufw/Windows-Server-2008-R2-x64-2019-07-19-18-50-14.png "配置静态ip")

> 第二步

为windows server服务器安装角色, 依次点击[ `开始` ]->[ `管理工具` ]->[ `服务管理器` ], 找到添加角色, 选择[ `Active Directory` ], 如下图, 中间有提示选择[ `是` ], 然后一直下一步, 最后点击安装

![安装服务](https://res.imgl.net/hexo/yufw/Windows-Server-2008-R2-x64-2019-07-19-19-37-18.png "安装服务")

> 第三步

安装完成以以后, 我们需要进行配置, 再次点击[ `开始` ]->[ `管理工具` ]->[ `服务管理器` ], 会看到如下界面, 我们点击图片中的划线部分

![开始配置](https://res.imgl.net/hexo/yufw/Windows-Server-2008-R2-x64-2019-07-19-19-55-35.jpg "开始配置")

然后再次点击下图中所标记的地方

![创建新林](https://res.imgl.net/hexo/yufw/Windows-Server-2008-R2-x64-2019-07-19-19-58-24.jpg "创建新林")

然后选择[ `在新林中新建域` ], 如果有下图的错误提示, 可以跟我一样做下处理, 首先是给administrator账户设置密码保护, 然后在系统中打开cmd或者powershell, 执行如下代码:

    net user administrator /passwordreq:yes 

![创建新林](https://res.imgl.net/hexo/yufw/Windows-Server-2008-R2-x64-2019-07-19-19-59-55.png "创建新林")

然后下一步要我们配置我们的域名是什么, 这点根据自己的情况输入, 比如我的是[ `imgl.net` ], 那么我就输入[ `imgl.net` ], 如下图:
![配置域名](https://res.imgl.net/hexo/yufw/Windows-Server-2008-R2-x64-2019-07-19-20-10-03.png "配置域名")

然后我们选择[ `windows server 2008` ]

![选择配置](https://res.imgl.net/hexo/yufw/Windows-Server-2008-R2-x64-2019-07-19-20-11-58.png "选择配置")

然后紧接着还有一个我们依旧选择[ `windows server 2008` ], 到达下面这个页面

![选择配置](https://res.imgl.net/hexo/yufw/Windows-Server-2008-R2-x64-2019-07-19-20-13-44.png "选择配置")

默认是选中[ `DNS 服务器` ]的, 这一个一定要勾选, 然后点击下一步, 中间它会检查dns配置, 等待, 喝茶

完事以后会抛出如下提示, 不要管它, 选择[ `是` ]

![选择配置](https://res.imgl.net/hexo/yufw/Windows-Server-2008-R2-x64-2019-07-19-20-16-52.png "选择配置")

然后到下面这个步骤, 我们不用更改这些东西, 默认就行

![默认](https://res.imgl.net/hexo/yufw/Windows-Server-2008-R2-x64-2019-07-19-20-18-45.png "默认")

然后一路下一步, 到下面这个情况, 等待它配置完成即可.

![等待](https://res.imgl.net/hexo/yufw/Windows-Server-2008-R2-x64-2019-07-19-20-20-25.png "等待")
![等待](https://res.imgl.net/hexo/yufw/Windows-Server-2008-R2-x64-2019-07-19-20-20-30.png "等待")

最后点击完成即可, 如下图:

![完成配置](https://res.imgl.net/hexo/yufw/Windows-Server-2008-R2-x64-2019-07-19-20-25-42.png "完成配置")

然后提示我们重启服务器, 点击重启即可.

> 第四步

如下图, 服务器重启以后我们发现之前配置的dns变成了 `127.0.0.1` , 并且网络标志也出现了感叹号, 这点不用管他, 这是系统给我们设定的, 不要随意修改

![确认](https://res.imgl.net/hexo/yufw/Windows-Server-2008-R2-x64-2019-07-19-20-32-25.png "确认")

#### 创建域组织及用户

再次点击[ `开始` ]->[ `管理工具` ]->[ `Active Directory 用户和计算机` ], 然后右键单击域名根部, 如图中划线的部分, 在弹出的菜单中选择[ `新建` ]->[ `组织单位` ]

![创建组织](https://res.imgl.net/hexo/yufw/Windows-Server-2008-R2-x64-2019-07-19-20-38-57.jpg "创建组织")

在弹出的对话框中我们随便输入个名字, 我这里以[ `技术部` ]为例, 如下图:

![创建组织](https://res.imgl.net/hexo/yufw/Windows-Server-2008-R2-x64-2019-07-19-20-39-32.png "创建组织")

创建组织这一步可已忽略

最后我们在技术部( `没有创建的可已直接在根部` )那里[ `右键` ]->[ `新建` ]->[ `用户` ], 然后可已根据需求录入信息, 我这里随便录了一个测试数据供参考

![创建用户](https://res.imgl.net/hexo/yufw/Windows-Server-2008-R2-x64-2019-07-19-20-52-33.png "创建用户")

然后下一步, 如下图, 这一步必须输入密码, 可已根据需求选择[ `用户下次登录时需修改密码` ]

![创建用户](https://res.imgl.net/hexo/yufw/Windows-Server-2008-R2-x64-2019-07-19-20-52-45.png "创建用户")

最后下一步完成

> 第五步

找一台客户机测试一下, 是否能够使用, 老样子, 我们需要配置一下客户机的ip地址, 在配置界面, 我们可以选择ip自动获取( `不建议` ), 但是dns需要我们手动配置, 如下图所示

![配置ip](https://res.imgl.net/hexo/yufw/Windows-7-2019-07-19-21-08-37.png "配置ip")

值得注意的是, 在配置dns时, 我们需要把域服务器的ip加入到dns中, 如上图

然后我们[ `右击` ]->[ `我的电脑` ]->[ `属性` ], 然后点击下图中的所示的[ `更改设置` ]->[ `更改` ]->[ `域` ], 输入我们刚才创建的 `域名` , 最后单击[ `确认` ]

![配置域](https://res.imgl.net/hexo/yufw/Windows-7-2019-07-19-21-02-42.jpg "配置域")

最后会提示我们输入我们刚才创建域用户, 如下图

![输入账户](https://res.imgl.net/hexo/yufw/Windows-7-2019-07-19-21-11-46.png "输入账户")

输入完成后我们点击确认, 最后系统友好的欢迎了我们, 如下图

![欢迎](https://res.imgl.net/hexo/yufw/Windows-7-2019-07-19-21-13-51.png "欢迎")

最后依次关闭, 然后提示 `重启` , 我们 `重启` 即可, 重启以后会要求我们 <kbd>Ctrl</kbd>+<kbd>Alt</kbd>+<kbd>Del</kbd> , 如下图 , 我们点击切换用户, 输入我刚才创建的 `zs` 和密码, 最后<kbd>Enter</kbd>登陆

![登陆域账户](https://res.imgl.net/hexo/yufw/Windows-7-2019-07-19-21-18-04.png "登陆域账户")

最后我们, 点击[ `开始` ]就能看到我们确实是用我们创建的域用户登陆了系统 , 自此以后系统默认要求我们登陆的用户就是我们创建的域用户了

![登陆域账户](https://res.imgl.net/hexo/yufw/Windows-7-2019-07-19-21-24-24.png "登陆域账户")


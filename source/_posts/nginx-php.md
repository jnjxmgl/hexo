---
title: nginx与多个版本php环境搭建
date: 2019-07-20 20:02:57
tags:

- nginx
- php
- fastcgi

categories: 

- nginx
- php
- windows
keywords:
- nginx
- nginx与多个版本php环境搭建
- php
- fastcgi

---

在配置环境以前, 有两个知识点我们需要了解, 一句话概括就是说: 你需要知道nginx和php是如何配合的, 进而如何将php动态网页转给客户端浏览器

* fastcgi
* nginx反向代理

> FastCGI

FastCGI是一个可伸缩地、高速地在HTTP server和动态脚本语言间通信的接口。多数流行的HTTP server都支持FastCGI，包括Apache、Nginx和lighttpd等。同时，FastCGI也被许多脚本语言支持，其中就有PHP。

FastCGI是从CGI发展改进而来的。

CGI工作原理和缺点：
每次HTTP服务器遇到动态程序时都需要重新启动脚本解析器来执行解析，然后将结果返回给HTTP服务器。

这在处理高并发访问时几乎是不可用的。另外传统的CGI接口方式安全性也很差，现在已经很少使用了。

FastCGI工作原理和优点：
FastCGI接口方式采用C/S结构，可以将HTTP服务器和脚本解析服务器分开，同时在脚本解析服务器上启动一个或者多个脚本解析守护进程。当HTTP服务器每次遇到动态程序时，可以将其直接交付给FastCGI进程来执行，然后将得到的结果返回给浏览器。

这种方式可以让HTTP服务器专一地处理静态请求或者将动态脚本服务器的结果返回给客户端，这在很大程度上提高了整个应用系统的性能。

另外fastCGI程序与CGI程序与服务器的交互方式也不同
CGI程序通过环境变量、命令行、标准输入输出进行交互，因此CGI程序进程必须与服务器进程在同一台物理计算机上，而fastCGI程序与服务器进程通过网络连接交互，因此fastCGI程序可以分布在不同的计算机上，这不但可以提高性能，同时也提高了系统的扩展能力。

> nginx反向代理

为什么我们要讲反向代理, 是因为Nginx不支持对外部程序的直接调用或者解析，所有的外部程序（包括PHP）必须通过FastCGI接口来调用, 而这个调用过程就是通过反向代理实现的。FastCGI接口在Linux下是socket（这个socket可以是文件socket，也可以是ip socket）。如下图:

{% asset_img fxdl.png 安装服务 %}

如图所示, php-cgi.exe在服务器内部是以服务的形式, 通过9000端口与nginx进行通讯的, 中间过程是反向代理, 最后通过nginx返给客户端

> 下载多个php版本与运行nginx

先下载windows版本的web服务器nginx程序到电脑上 （这一步不做演示）, 如下图中， 我这里下载的是最新版的 `1.17.1` , 把nginx拷贝解压到 `C:\nginx` , 下面我们需要进行一点自定义配置，修改 `conf/nginx.conf` 创建两个server块级配置，具体如下：

    server {
            listen       80;
            server_name  localhost;

            #charset koi8-r;

            #access_log  logs/host.access.log  main;

            location / {
                root   html;
                index  index.html index.htm;
            }

            #error_page  404              /404.html;

            # redirect server error pages to the static page /50x.html
            #
            error_page   500 502 503 504  /50x.html;
            location = /50x.html {
                root   html;
            }

            # proxy the PHP scripts to Apache listening on 127.0.0.1:80
            #
            #location ~ \.php$ {
            #    proxy_pass   http://127.0.0.1;
            #}

            # pass the PHP scripts to FastCGI server listening on 127.0.0.1:9000
            #
            location ~ \.php$ {
                root           html;
                fastcgi_pass   127.0.0.1:9000;
                fastcgi_index  index.php;
                fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;
                #fastcgi_param  SCRIPT_FILENAME  /scripts$fastcgi_script_name;
                include        fastcgi_params;
            }

            # deny access to .htaccess files, if Apache's document root
            # concurs with nginx's one
            #
            #location ~ /\.ht {
            #    deny  all;
            #}
        }

    server {
            listen       8080;
            server_name  localhost;

            #charset koi8-r;

            #access_log  logs/host.access.log  main;

            location / {
                root   html2;
                index  index.html index.htm;
            }

            #error_page  404              /404.html;

            # redirect server error pages to the static page /50x.html
            #
            error_page   500 502 503 504  /50x.html;
            location = /50x.html {
                root   html2;
            }

            # proxy the PHP scripts to Apache listening on 127.0.0.1:80
            #
            #location ~ \.php$ {
            #    proxy_pass   http://127.0.0.1;
            #}

            # pass the PHP scripts to FastCGI server listening on 127.0.0.1:9000
            #
            location ~ \.php$ {
                root           html2;
                fastcgi_pass   127.0.0.1:9001;
                fastcgi_index  index.php;
                fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;
                #fastcgi_param  SCRIPT_FILENAME  /scripts$fastcgi_script_name;
                include        fastcgi_params;
            }

            # deny access to .htaccess files, if Apache's document root
            # concurs with nginx's one
            #
            #location ~ /\.ht {
            #    deny  all;
            #}
        }

说明一下，其实我们最需要更改的是两个部分，在第一个和第二个server中，我们把

    # pass the PHP scripts to FastCGI server listening on 127.0.0.1:9000
    #
    location ~ \.php$ {
        root           html;
        fastcgi_pass   127.0.0.1:9000;
        fastcgi_index  index.php;
        fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;#注意这里修改了
        #fastcgi_param  SCRIPT_FILENAME  /scripts$fastcgi_script_name;
        include        fastcgi_params;
    }

这一块#去掉，不同的在第二个server中，首先我们再nginx.exe同级目录中创建html2，然后将第二个server中全部的的 `root html` -> `root html2` ，同样是把上面所示的那一部分中的#号去掉，并把 `9000` 改成 `9001` ， `80` 改为 `8080` 。

然后打开cmd或者powershell ，执行命令

{% asset_img Windows-Server-2008-R2-x64-2019-07-21-15-08-36.png 启动nginx %}

    cd 
    start nginx

或者直接执行

    nginx

第一种情况是以后台进程的方式运行的，我们关掉命令窗口即可，nginx进程不会停止掉，但是如过你使用的是第二种，是以前台进程的方式运行，这时候你不能随意的关闭这个命令窗口，但是如过你想停止掉nginx进程，只需要关闭命令窗口即可，具体那种方式适合你，这个自己斟酌选择。

我们打开浏览器，输入 `http://localhost` , 就可以看到如上图所示，nginx服务进程已经启动了。

{% asset_img qd.png nginx启动了 %}

下面开始配置PHP，因为是多个版本，这里我门下在php5.6和php7.2两大主流版本。这里我们去php的官网(https://windows.php.net/download/)去下载, 如下图所示

{% asset_img 2019-07-21.jpg 下载php7.2 %}

下载64位或者32位根据自身系统需要，另外细心的你可能发现我为什么没有 `Thread Safe` 版本，是因为这个版本不适合我们今天所讲的东西，我摘了一下官网的解释，自己体会，看不懂的话，也没有关系，后期自行补脑。

*官方解析：我选择哪个版本？*

*IIS*
*如果您使用PHP作为FastCGI与IIS，您应该使用PHP的非线程安全（NTS）版本。*

*Apache*
*请使用Apache Lounge提供的Apache版本。它们为x86和x64提供了Apache的VC14，VC15和VS16版本。我们使用他们的二进制文件来构建Apache SAPI。*

*使用Apache，您必须使用PHP的线程安全（TS）版本。*

*TS和NTS*
*TS指的是支持多线程的构建。NTS指的是仅单线程构建。TS二进制文件的用例涉及与作为模块加载到Web服务器中的多线程SAPI和PHP的交互。对于NTS二进制文件，广泛的用例是通过FastCGI协议与Web服务器交互，不使用多线程（但也使用CLI）。*

至于5.6版本，我们需要到(https://windows.php.net/downloads/releases/archives/)这里去下在，可已通过浏览器搜索关键字 `php-5.6.40-nts-Win32-VC11-x64` ，定位我们要下载的版本。

{% asset_img 2019-07-21_5.6.jpg 下载php5.6 %}

> 配置多个php版本

下载完成以后我们分别解压到 `C盘` 的 `C:\php-5.6.40-nts-Win32-VC11-x64` 和 `C:\php-7.3.7-nts-Win32-VC15-x64` , 然后进入分别进入两个php版本相应的目录，找到下图所示的程序名称，[ `右键` ]->[ `发送到` ]->[ `桌面快捷方式` ]，并将两个快捷方式重命名，最后[ `右键` ]->[ `属性` ]->[ `目标` ]这里追加 `-b 127.0.0.1:9000` ，另一个改为 `-b 127.0.0.1:9001` ，如下图所示，

{% asset_img Windows-Server-2008-R2-x64-2019-07-21-16-05-07.png 配置两个不同的真挺端口 %}

并且将同目录下的 `php.ini-development` 重命名为 `php.ini` ，最后双击两个快捷方式运行。

{% asset_img Windows-Server-2008-R2-x64-2019-07-21-15-42-23.png vc++11 %}

如果出现图上所示的错误提示，说明我们缺少相应的c++运行时，因为windows下的php运行库是通过Vc++进行开发编译的，具体要下载哪个版本，我们可已通过php文件夹上的名字来判断，x64我们就需要64位的vc++，然后根据VC11是vc++2012, VC15是vc++2015, 去微软官方或者百度搜索下进行下载，这里不做演示。

启动php-cgi以后，最终结果是如下图所示的样子

{% asset_img Windows-Server-2008-R2-x64-2019-07-21-15-55-49.jpg 启动php-cgi.exe %}

最后我们再html和html2中，分别创建index.php，内容：

``` php
<php?
    phpinfo();

```

然后保存。

最后我们在浏览器中输入 `http://localhost/index.php` 和 `http://localhost:8080/index.php` , 如果能看到如下面两个图所示，就说明我们配置成功了。

{% asset_img Windows-Server-2008-R2-x64-2019-07-21-17-04-53.png 验证5.6 %}

{% asset_img Windows-Server-2008-R2-x64-2019-07-21-17-05-03.png 验证7.2 %}

*注：本教程只讲述基本入门配置，另外需要优化的升级配置自行研究。*


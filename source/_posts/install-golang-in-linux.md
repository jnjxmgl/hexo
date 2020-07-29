---
title: 在linux系统中安装golang
date: 2020-07-29 10:35:39
tags:
- go
- linux
categories:
- linux
- go
keywords:
- 在linux系统中安装golang
- 在ubuntu中安装golang
- 在debian中安装golang
- 如何安装golang
---

Go（Golang）是谷歌开发的一种现代编程语言，专注于简单性和安全性。它已成为服务器端开发常用的语言。

首先，您需要下载 Golang。


    cd #回到家目录

    wget https://storage.googleapis.com/golang/go1.8.3.linux-amd64.tar.gz  #下载 


你也可以去官网下载最新的 https://golang.org/dl/

现在，您需要解压缩文件。

    tar zxf go1.8.3.linux-amd64.tar.gz

将解压后的`go`文件夹移动到`"/opt"`

    mv go /opt/
    
添加环境变量路径,在终端下面的命令

    mkdir /opt/gopkg
    export GOPATH="/opt/gopkg" 
    export GOROOT="/opt/go"

现在，您需要将它们添加到 `"PATH"` 变量中。

    export PATH=$PATH:$GOPATH/bin:$GOROOT/bin 


以上操作是临时性的 ,只对当前终端有效, 如果希望更改是永久性的，则需要将以下命令放入文件中`".profile"`

打开文件

    sudo nano ~/.profile #如过希望对所有用户永久可用,需要修改/etc/profile文件


在末尾插入以下内容


    export GOPATH="/opt/gopkg" 
    export GOROOT="/opt/go"
    export PATH=$PATH:$GOPATH/bin:$GOROOT/bin 

验证安装

go version

会输出如下信息

    go version go1.8.3 linux/amd64



创建名为 `"hello.go"`的文件

    cd /opt/gopkg
    nano hello.go

插入以下代码。

```go
package main
import "fmt"

    func main(){
            fmt.Printf("Hello World\n");
    }
```

您可以使用以下方法运行此测试脚本

    go run hello.go


终端将会输出`"Hello World"`
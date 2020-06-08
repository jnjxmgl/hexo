---
title: 在Ubuntu上创建Sudo用户
date: 2020-06-08 11:52:56
tags: 
- sudo
categories:
- linux
keywords:
- 在Ubuntu上创建Sudo用户
---


1. 添加一个新的用户帐户

使用`adduser`命令创建一个新的用户帐户。为新用户使用一个强密码。您可以输入用户信息的值，或按<kbd>ENTER</kbd>将这些字段保留为空白。

    # adduser example_user
    Adding user `example_user' ...
    Adding new group `example_user' (1001) ...
    Adding new user `example_user' (1001) with group `example_user' ...
    Creating home directory `/home/example_user' ...
    Copying files from `/etc/skel' ...
    New password:
    Retype new password:
    passwd: password updated successfully
    Changing the user information for example_user
    Enter the new value, or press ENTER for the default
            Full Name []: Example User
            Room Number []:
            Work Phone []:
            Home Phone []:
            Other []:
    Is the information correct? [Y/n] y

2. 将用户添加到sudo组

使用将新用户添加到sudo组`usermod`

    # usermod -aG sudo example_user

3. 测试

切换到新用户

    # su - example_user

验证您是的新用户`whoami`，然后测试的`sudo`访问`sudo whoami`，该访问应返回`root`。

    $ whoami
    example_user
    $ sudo whoami
    [sudo] password for example_user:
    root

4. 总结

新的用户帐户即可使用。最佳做法是，使用此sudo用户进行服务器管理。您应避免将root用户用于维护任务。
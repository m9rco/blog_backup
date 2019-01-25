title: But you should have one thing mind. 
description: 
categories:
- php
tags:
- phper
toc: true
author:
comments:
original:
permalink: 
---
随时更新一下值得记忆的一些踏过的坑

<!-- more -->

### Mac git 大小写问题

由于 Mac 下文件名大小写不敏感，造成 git 下如果改了名字，譬如小写改大些，推送到 linux 服务器的时候会没有效果，Github 上的也是小写。 
所以，如果在 Mac 上改文件名，需要用下面的命令

```bash
$ git mv --force myfile MyFile

#修改git配置，不忽略大小写
git config core.ignorecase false
```

### 删除git 远程的分支

```bash
$ git push --delete origin branch_name

```

### 推送出现一些同步的错误，可以加个-f

```bash
$ git push origin dev -f 

```

### 修改crontab 的编辑器

```bash
export EDITOR=/usr/bin/vim # crontab -e

```

### redis MONITOR 监控redis的所有的被执行的命令

```bash
//在程序之外用管道监控某一个命令。
redis-cli -h 172.16.71.70 -p 6379 MONITOR|grep medal:rank:9
1472647383.968024 [0 172.16.71.67:48460] "ZINCRBY" "medal:rank:9" "1.0000000000000000" "12436136"
1472647384.560867 [0 172.16.71.69:60301] "ZADD" "medal:rank:9" "108.0000000000000000" "12436136"
1472647384.561215 [0 172.16.71.69:60301] "ZCARD" "medal:rank:9"
1472647440.527100 [0 172.16.71.67:48566] "ZINCRBY" "medal:rank:9" "1.0000000000000000" "12436136"
1472647440.811201 [0 172.16.71.69:60301] "ZADD" "medal:rank:9" "109.0000000000000000" "12436136"
1472647440.811598 [0 172.16.71.69:60301] "ZCARD" "medal:rank:9"
1472647456.269238 [0 172.16.71.67:48586] "ZINCRBY" "medal:rank:9" "1.0000000000000000" "12436136"
1472647457.091923 [0 172.16.71.69:60301] "ZADD" "medal:rank:9" "110.0000000000000000" "12436136"
1472647457.092253 [0 172.16.71.69:60301] "ZCARD" "medal:rank:9"
1472647457.523799 [0 172.16.71.67:48593] "ZINCRBY" "medal:rank:9" "1.0000000000000000" "12436136"
1472647458.364086 [0 172.16.71.69:60301] "ZADD" "medal:rank:9" "111.0000000000000000" "12436136"
1472647458.364470 [0 172.16.71.69:60301] "ZCARD" "medal:rank:9"
1472647473.428126 [0 172.16.71.67:48605] "ZCARD" "medal:rank:9"
1472647473.451694 [0 172.16.71.67:48605] "ZCARD" "medal:rank:9"
1472647473.451863 [0 172.16.71.67:48605] "ZREVRANK" "medal:rank:9" "12436136"
1472647473.452154 [0 172.16.71.67:48605] "ZREVRANGE" "medal:rank:9" "0" "9"
1472647516.470815 [0 172.16.71.67:48690] "ZINCRBY" "medal:rank:9" "1.0000000000000000" "12436136"
1472647516.663979 [0 172.16.71.69:60301] "ZADD" "medal:rank:9" "112.0000000000000000" "12436136"
1472647516.664325 [0 172.16.71.69:60301] "ZCARD" "medal:rank:9"

```

### 监控文件的实时数据 tail -f


```bsh
//当文件有数据写入时，能实时的输出
tail -f app.log
logs tail -f app.log
[2016-08-25 18:57:37] slim-app.INFO: Slim-Skeleton '/' route [] {"uid":"9aeff67"}
[2016-08-25 18:57:37] slim-app.INFO: Slim-Skeleton '/' route [] {"uid":"a117fed"}
[2016-08-25 18:57:42] slim-app.INFO: Slim-Skeleton '/' route [] {"uid":"c6edb36"}
[2016-08-25 18:57:42] slim-app.INFO: Slim-Skeleton '/' route [] {"uid":"a29a035"}
[2016-08-25 19:02:00] slim-app.INFO: Slim-Skeleton '/' route [] {"uid":"a11dfb4"}
[2016-08-25 19:02:59] slim-app.INFO: Slim-Skeleton '/' route [] {"uid":"95e2320"}
[2016-08-25 19:03:07] slim-app.INFO: Slim-Skeleton '/' route [] {"uid":"3a5aa35"}
```

### set_error_handler PHP中用来捕获自定义的错误信息

```bash
public function aaa()
{
    function customError($errno, $errstr, $errfile, $errline)
    {
        echo "<b>Custom error:</b> [$errno] $errstr<br />";
        echo " Error on line $errline in $errfile<br />";
        echo "Ending Script";
        die();
    }
    //set error handler， 第二个参数是可以设置需要捕获的错误类型
    set_error_handler("customError", E_ALL | E_WARNING);
    //$a 没定义，应该会有一个错误：
    var_dump($a);
}

```

看下打印输出，就能按照我们的方式输出打印错误：


```
<b>Custom error:</b> [8] Undefined variable: a<br /> Error on line 169 in /data/app/live/include/controller/TmpCtrl.php<br />Ending Script%
```


### php cli 命令

```bash
php -i
查看phpinfo
php -v
显示PHP版本
php -m
查看PHP安装了哪些扩展模块，可修改php.ini添加删除扩展模块。
编译PHP时内置的扩展，无法通过修改php.ini删除
php -S
启动一个内置的Web服务器，用于开发环境内进行程序的调试。
php -S 0.0.0.0:9000
内置的Web服务器是一个全功能的Http服务器，在开发模式下可以取代apache,nginx+php-fpm，但不可用于线上生产环境。
可以使用-t参数指定document_root，如果不指定表示使用当前目录作为document_root
php -S 0.0.0.0:9000 -t /data/webroot/
php -c
指定加载php.ini的绝对路径
php -c /home/htf/my_php.ini
php -l
检测一个php代码文件是否有语法错误，如 php -l test.php
php -r
执行一段php代码，如
php -r "echo 'hello world';"
php --ini
显示当前加载的php.ini绝对路径
php --re swoole
显示某个扩展提供了哪些类和函数。
php --ri swoole
显示扩展的phpinfo信息。与phpinfo的作用相同，不同之处是这里仅显示指定扩展的phpinfo
php --rf file_get_contents
显示某个PHP函数的信息，一般用于检测函数是否存在
```

### Linux下查看一个端口被哪个占用进程

```
netstat -apn|grep 7782
tcp        0      0 :::80                       :::*                        LISTEN      19408/java 
#那么进程号就是`19408`
再通过`ps -ef | grep 19408` 就知道这个进程是啥了。
+


```
















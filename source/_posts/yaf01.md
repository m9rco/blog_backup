---
title: Yaf---写在前面
date: 7/28/2016 11:22:37 PM 
description: 
categories:
- Frame
tags: Yaf
toc: true
author:
comments:
original:
permalink: 
---
Yaf有着和Zend Framework相似的API, 相似的理念, 而同时又保持着对Bingo的兼容, 以此来提高开发效率, 规范开发习惯. 本着对性能的追求, Yaf把框架中不易变的部分抽象出来,采用PHP扩展实现(c语言),以此来保证性能.在作者自己做的简单测试中, Yaf和原生的PHP在同样功能下, 性能损失小于10%, 而和ZendFramework的对比中, Yaf的性能是Zend Framework的50-60倍.
<!--more-->
> 天下武功无坚不破，唯快不破

1. 用C语言开发的PHP框架, 相比原生的PHP, 几乎不会带来额外的性能开销.
2. 所有的框架类, 不需要编译, 在PHP启动的时候加载, 并常驻内存.
3. 更短的内存周转周期, 提高内存利用率, 降低内存占用率.
4. 灵巧的自动加载. 支持全局和局部两种加载规则, 方便类库共享.
5. 高性能的视图引擎.
6. 高度灵活可扩展的框架, 支持自定义视图引擎, 支持插件, 支持自定义路由等等.
7. 内建多种路由, 可以兼容目前常见的各种路由协议.
8. 强大而又高度灵活的配置文件支持. 并支持缓存配置文件, 避免复杂的配置结构带来的性能损失.
9. 在框架本身,对危险的操作习惯做了禁止.
10. 更快的执行速度, 更少的内存占用

###  Yaf的安装 Windows 
Yaf只支持PHP5.2及以上的版本
Yaf需要SPL的支持. SPL在PHP5中是默认启用的扩展模块
Yaf需要PCRE的支持. PCRE在PHP5中是默认启用的扩展模块
在 Windows 系统下安装
PHP 5.2+
1. 打开yaf在php官网上的目录：http://pecl.php.net/package/yaf
2. 目前yaf的最新版为3.0.0,仅支付php7,建议选择2.3.5版本
3. 我这里选择2.3.5后面的win图标+DLL字样的链接，进入页面下载php_yaf.dll
4. 在打开的页面根据自己的环境来选择对应的版本，我这里选择的是php5.6 Thread Safe (TS)x86(php5.6版本 安全线程 32位操作系统)
5. 点击后自动下载了一个压缩包：php_yaf-2.3.5-5.6-ts-vc11-x86.zip
6. 把压缩包中的php_yaf.dll复制出来，打到你的php目录，打开目录下的ext文件夹，粘贴进去
7. 再打开您的PHP配置文件php.ini，加入 'extension=php_yaf.dll',重启web服务器,就OK了

![](/blog-img/yaf2.bmp "phpini路径")
![](/blog-img/yaf.jpg "phpinfo显示成功螃蟹图标再现")

----------
###  Yaf的安装 Linux 

下载Yaf的最新版本, 解压缩以后, 进入Yaf的源码目录, 依次执行(其中PHP_BIN是PHP的bin目录):
>` cd /usr/local/src `#进入软件包存放目录
` tar zxvf yaf-2.3.5.tgz `#解压
` cd yaf-2.3.5 `#进入安装目录
` /usr/local/php/bin/phpize `#用phpize生成configure配置文件
` ./configure --with-php-config=/usr/local/php/bin/php-config ` #配置
` make ` 
` make install `
安装完成之后，出现下面的安装路径
` /usr/local/php/lib/php/extensions/no-debug-non-zts-20100525/ `
配置php支持
` vim /usr/local/php/etc/php.ini ` #编辑配置文件，在最后一行添加以下内容
` extension="yaf.so" `
` :wq! ` #保存退出
 重启服务
` sudo service nginx restart `
` sudo /etc/init.d/php-fpm restart `
查看
`php -m ` 看到神秘的yaf 就说明安装成功了

![](/blog-img/yaf3.bmp)
![](/blog-img/yaf4.bmp)

`Yaf_Request_Abstract`的`getPost`, `getQuery`等方法, 并没有对应的`setter`方法. 并且这些方法是直接从PHP内部的$_POST, $_GET等大变量的原身变量只读的查询值, 所以就有一个问题:通过在PHP脚本中对这些变量的修改, 并不能反映到 `getPost/getQuery` 等方法上
    
### yaf 的常量

常量(启用命名空间后的常量名) | 说明
---|---
YAF_VERSION(Yaf\VERSION)| Yaf框架的三位版本信息
YAF_ENVIRON(Yaf\ENVIRON)| Yaf的环境常量, 指明了要读取的配置的节, 默认的是product
YAF_ERR_DISPATCH_FAILED(Yaf\ERR\DISPATCH_FAILED)|    Yaf的错误代码常量, 表示分发失败, 值为514
YAF_ERR_NOTFOUND_MODULE(Yaf\ERR\NOTFOUD\MODULE)| Yaf的错误代码常量, 表示找不到指定的模块, 值为515
YAF_ERR_NOTFOUND_CONTROLLER(Yaf\ERR\NOTFOUD\CONTROLLER)| Yaf的错误代码常量, 表示找不到指定的Controller, 值为516
YAF_ERR_NOTFOUND_ACTION(Yaf\ERR\NOTFOUD\ACTION)| Yaf的错误代码常量, 表示找不到指定的Action, 值为517
YAF_ERR_NOTFOUND_VIEW(Yaf\ERR\NOTFOUD\VIEW)| Yaf的错误代码常量, 表示找不到指定的视图文件, 值为518
YAF_ERR_CALL_FAILED(Yaf\ERR\CALL_FAILED)|    Yaf的错误代码常量, 表示调用失败, 值为519
YAF_ERR_AUTOLOAD_FAILED(Yaf\ERR\AUTOLOAD_FAILED) |   Yaf的错误代码常量, 表示自动加载类失败, 值为520
YAF_ERR_TYPE_ERROR(Yaf\ERR\TYPE_ERROR)|  Yaf的错误代码常量, 表示关键逻辑的参数错误, 值为521
    
### yaf 的配置项

选项名称 | 默认值 | 可修改范围  | 更新记录
---|---|---|---
yaf.environ| product| PHP_INI_ALL| 环境名称, 当用INI作为Yaf的配置文件时, 这个指明了Yaf将要在INI配置中读取的节的名字
yaf.library| NULL|    PHP_INI_ALL |全局类库的目录路径
yaf.cache_config|    0|  PHP_INI_SYSTEM | 是否缓存配置文件(只针对INI配置文件生效), 打开此选项可在复杂配置的情况下提高性能
yaf.name_suffix |1|   PHP_INI_ALL| 在处理Controller, Action, Plugin, Model的时候, 类名中关键信息是否是后缀式, 比如UserModel, 而在前缀模式下则是ModelUser
yaf.name_separator|  "" | PHP_INI_ALL| 在处理Controller, Action, Plugin, Model的时候, 前缀和名字之间的分隔符, 默认为空, 也就是UserPlugin, 加入设置为"_", 则判断的依据就会变成:"User_Plugin", 这个主要是为了兼容ST已有的命名规范
yaf.forward_limit|   5 |  PHP_INI_ALL| forward最大嵌套深度
yaf.use_namespace|   0 |  PHP_INI_SYSTEM | 开启的情况下, Yaf将会使用命名空间方式注册自己的类, 比如Yaf_Application将会变成Yaf\Application
yaf.use_spl_autoload |   0 |  PHP_INI_ALL| 开启的情况下, Yaf在加载不成功的情况下, 会继续让PHP的自动加载函数加载, 从性能考虑, 除非特殊情况, 否则保持这个选项关闭

>在开启yaf.cache_config的情况下, Yaf会使用INI文件路径作为Key, 这就有一个陷阱, 就是如果在一台服务器上同时运行俩个应用, 那么它们必须不能使用同一个路径名下的INI配置文件, 否则就会出现Application Path混乱的问题. 所以, 尽量不要使用相对路径.

### 快速开始

    ├──public
    │    ├── index.php  入口文件
    │    ├── .htaccess  重写规则 
    │    ├── css
    │    ├── img
    │    ├── js
    ├──conf
    │    ├── application.ini 配置文件  
    ├──application
    │    ├── Controllers
    │            ├── Index.php 默认控制器 
    │    ├── views 
    │            ├── Index 控制器名
    │                  ├── index.phtml 默认视图
    ├──modules 其他模块
    ├──library 本地类库
    ├──models  model目录
    ├──plugins 插件目录

### 入口文件

入口文件是所有请求的入口, 一般都借助于rewrite规则, 把所有的请求都重定向到这个入口文件.

一个经典的入口文件public/index.php

    <?php
    define("APP_PATH",  realpath(dirname(__FILE__) . '/../')); /* 指向public的上一级 */
    $app  = new Yaf_Application(APP_PATH . "/conf/application.ini");
    $app->run();   

### 重写规则

除非我们使用基于query string的路由协议(Yaf_Route_Simple, Yaf_Route_Supervar), 否则我们就需要使用WebServer提供的Rewrite规则, 把所有这个应用的请求, 都定向到上面提到的入口文件.

Apache的Rewrite (httpd.conf)


    #.htaccess, 当然也可以写在httpd.conf
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule .* index.php

    


Nginx的Rewrite (nginx.conf)


    server {
      listen ****;
      server_name  domain.com;
      root   document_root;
      index  index.php index.html index.htm;

      if (!-e $request_filename) {
        rewrite ^/(.*)  /index.php/$1 last;
      }
}

    


Lighttpd的Rewrite (lighttpd.conf)


    $HTTP["host"] =~ "(www.)?domain.com$" {
      url.rewrite = (
         "^/(.+)/?$"  => "/index.php/$1",
      )
    }

        




SAE的Rewrite (config.yaml)


    name: your_app_name
    version: 1
    handle:
        - rewrite: if(!is_dir() && !is_file() && path ~ "^(.*)$" ) goto "/index.php"
        


>[注意]    注意每种Server要启用Rewrite都需要特别设置, 如果对此有疑问.. RTFM
配置文件

在Yaf中, 配置文件支持继承, 支持分节. 并对PHP的常量进行支持. 你不用担心配置文件太大造成解析性能问题, 因为Yaf会在第一个运行的时候载入配置文件, 把格式化后的内容保持在内存中. 直到配置文件有了修改, 才会再次载入.

一个简单的配置文件application/conf/application.ini


    [product]
    ;支持直接写PHP中的已定义常量
    application.directory=APP_PATH "/application/" 

     


>控制器

在Yaf中, 默认的模块/控制器/动作, 都是以Index命名的, 当然,这是可通过配置文件修改的.
对于默认模块, 控制器的目录是在application目录下的controllers目录下, Action的命名规则是"名字+Action"

 默认控制器application/controllers/Index.php


    <?php
    class IndexController extends Yaf_Controller_Abstract {
       public function indexAction() {//默认Action
           $this->getView()->assign("content", "Hello World");
       }
    }
    ?>

    


>视图文件

Yaf支持简单的视图引擎, 并且支持用户自定义自己的视图引擎, 比如Smarty.对于默认模块, 视图文件的路径是在application目录下的views目录中以小写的action名的目录中.

一个默认Action的视图application/views/index/index.phtml


    <html>
     <head>
       <title>Hello World</title>
     </head>
     <body>
      <?php echo $content;?>
     </body>
    </html>

> 运行在浏览器输入

http://www.yourhostname.com/application/index.php

    
看到了Hello World输出吧?





    





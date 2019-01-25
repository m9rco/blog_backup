---
title: Yaf---异常和错误 | 路由和分发
date: 7/31/2016 11:00:43 PM 
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
Yaf实现了一套错误和异常捕获机制, 主要是对常见的错误处理和异常捕获方法做了一个简单抽象, 方便应用组织自己的错误统一处理逻辑。前题是需要配置过或是在程序中启用
<!--more-->


### 使用示例   



Yaf实现了一套错误和异常捕获机制, 主要是对常见的错误处理和异常捕获方法做了一个简单抽象, 方便应用组织自己的错误统一处理逻辑。前题是需要配置过或是在程序中启用


-  配置


      application.dispatcher.throwException=1
      application.dispatcher.catchException=1


- 在程序中启用

>`Yaf_Dispatcher::throwException(true)`

在`application.dispatcher.catchException`(配置文件, 或者可通过`Yaf_Dispatcher::catchException(true))`开启的情况下, 当Yaf遇到未捕获异常的时候, 就会把运行权限, 交给当前模块的`Error Controller`的`Error Action`动作, 而异常或作为请求的一个参数, 传递给`Error Action`.

新建一个Error Controller

      <?php
      class ErrorController extends Yaf_Controller_Abstract
      {
          public function errorAction($exception)
          {
              assert($exception);
              $this->getView()->assign("code", $exception->getCode());
              $this->getView()->assign("message", $exception->getMessage());
              $this->getView()->assign("line", $exception->getLine());
          }
      }
      ?>


新建一个Error显示模板文件

      <html>
      <head>
      <meta charset="utf-8">
      <title>Error Page <{$code}></title>
          <style>
                body{background-color:#f0c040}
                h2{color:#fafafa}
          </style>
      </head>
          <body>
                <h2>Error Page</h2>
                    <p>Error Code:<{$code}></p>
                    <p>Error Message:<{$message}></p>
                    <p>Error Line:<{$line}></p>
          </body>
      </html>

在Bootstrap.php中新建一个error_handler方法


      public static function error_handler($errno, $errstr, $errfile, $errline)
      {
          if (error_reporting() === 0) return;
          throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
      }


在Bootstrap.php中初始化ErrorHandler

      public function _initErrorHandler(Yaf_Dispatcher $dispatcher)
      {
            $dispatcher->setErrorHandler(array(get_class($this),'error_handler'));
      }


这样当有有程序异常时会转到ErrorController

> 路由和分发

简单的理解

就我的理解来说，路由分发过程的执行动作是，获取用户请求的URl，根据路由规则解析这个URL，得到module、controller、action、param、query，根据获得的module和controller去载入控制器，执行对应的action方法。插件钩子路由器也有插件钩子,就是routerStartup和routerShutdown,他们在路由解析前后分别被调用.本文档使看构建 

> 设置路由的方法

- 添加配置 


      routes.regex4.type="regex"
      routes.regex4.match="#^/news/([^/])/([^/])#"
      routes.regex4.route.controller=news
      routes.regex4.route.action=detail
      routes.regex4.map.1=id
      routesregex4map2=sort

-  在Bootstapphp中添加路由配置


      <?php
        class Bootstrap extends Yaf_Bootstrap_Abstract{

          public function _initRoute(Yaf_Dispatcher $dispatcher) {

          $router = Yaf_Dispatcher::getInstance()->getRouter();

          $router->addConfig(Yaf_Registry::get("config")->routes);

          }
        }

- 添加接收的控制器


      <?php
      class NewsController extends Yaf_Controller_Abstract {

            public function init()
            {

                 Yaf_Dispatcher::getInstance()->disableView();

            }
            public function detailAction($id = 0,$sort = '')
            {

                print_r($this->getRequest()->getParams());

                echo 'News Detail:'.$id.',sort:'.$sort;

            }
      }
      ?>

- 访问  url: yourhost/news/78/createtime

当访问这个url，yaf先根据我们的路由规则解析出默认的module,news控制器,detailAction,第一个参数id,第二个参数，sort。

我们来分析一下解析流程：

      YafApplication::app()>bootstrap()>getDispatcher>dispatch();


1.在yaf_dispatcher_route中，完成路由解析，得到module=''，controller=news，action=detail

2.在yaf_dispatcher_fix_default中，通过其处理得到module=index，controller=news，action=detail

3.在2中完成之后，通过如果有hook机制，就会执行插件钩子：routerShutdown

4.在yaf_internal_autoload中完成自动加载类文件，application/controllers/News.php

5执行detailAction

在Bootstrapphp中配置路由规则

上面就是一个简单的通过正则的方式来设置路由的示例，我们还可以直接在Bootstrap.php添加我们的路由规则：


          public function _initRoute(Yaf_Dispatcher $dispatcher) {

                $router = Yaf_Dispatcher::getInstance()->getRouter();

                $router->addConfig(Yaf_Registry::get("config")->routes);

                //在刚才的示例里添加上下面两行

                $route = new Yaf_Route_Simple("m", "c", "a");

                $router->addRoute("simple", $route);
          ｝

测试一下

我们就可以尝试用 yourhost?c=news&a=detail 访问你的newsController,detailAction了。


### Yaf_Route_Simple

上面是`Yaf_Route_Simple`的一个示例

Yaf_Route_Simple是基于请求中的query string来做路由的, 在初始化一个Yaf_Route_Simple路由协议的时候, 我们需要给出3个参数, 这3个参数分别代表在query string中Module, Controller, Action的变量名，它也可以直接在配置信息里设置

      routes.simple.type="simple"
      routes.simple.controller=c
      routes.simple.module=m
      routes.simple.action=a

更多关于路由的信息可以参见官方文档：http://www.laruence.com/manual/yaf.routes.static.html
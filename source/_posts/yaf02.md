---
title: Yaf---加载规则插件使用
date: 7/29/2016 10:22:43 PM 
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
Yaf在自启动的时候, 会通过SPL注册一个自己的Autoloader, 出于性能的考虑, 对于框架相关的MVC类, Yaf Autoloader只以目录映射的方式尝试一次.
<!--more-->

类型 | 后缀 | 映射路径 
---|---|---
控制器 | Controller | 默认模块下为{项目路径}/controllers/, 否则为{项目路径}/modules/{模块名}/controllers/
数据模型 | Model | {项目路径}/models/
插件 | Plugin | {项目路径}/plugins/

###  一个简单的自我理解

    <?php
    class IndexController extends Yaf_Controller_Abstract {
        public function indexAction() {//默认Action

        $mod = new TserModel(); //自动加载model下面的test.php文件

        $mod->query(); //调用TestModel里的query方法

        $user = new UserPlugin(); //自动加载plugins下面的user.php文件

        $this->getView()->assign("title", "Hello Yaf");

        $this->getView()->assign("content", "Hello Yaf Content");
    }

### 类的自动加载规则

而类的加载规则, 都是一样的: Yaf规定类名中必须包含路径信息, 也就是以下划线"_"分割的目录信息. Yaf将依照类名中的目录信息, 完成自动加载. 如下的例子, 在没有申明本地类的情况下:

    public function indexAction() {
      
      $upload = new upload_aliyun();

       //这个就会按下划线分割目录来寻找文件，所以他会寻找 \library\upload\aliyun.php
    }

先这么简单理解，还有一个registerLocalNamespace的内容，后续再来说一说，怕混了。

### 手动载入

`Yaf_Loader::import`

导入一个PHP文件, 因为Yaf_Loader::import只是专注于一次包含, 所以要比传统的require_once性能好一些
示例：
    <?php
      //绝对路径
      Yaf_Loader::import("/usr/local/foo.php);
      //相对路径, 会在APPLICATION_PATH."/library"下加载
      Yaf_loader::import("plugins/User.php");
    ?>

![](/blog-img/yafa.bmp "phpinfo显示成功螃蟹图标再现")

### 使用Boostrap

Bootstrap, 也叫做引导程序. 它是Yaf提供的一个全局配置的入口, 在Bootstrap中, 你可以做很多全局自定义的工作.

使用Bootstrap

在一个Yaf_Application被实例化之后, 运行(Yaf_Application::run)之前, 可选的我们可以运行Yaf_Application::bootstrap
改写index.php文件如下：

      <?php
      define("APP_PATH", realpath(dirname(__FILE__)));
      
      $app = new Yaf_Application(APP_PATH . "/conf/application.ini");
      
      $app->bootstrap()->run();

      当bootstrap被调用的时刻, Yaf_Application就会默认的在APPLICATION_PATH下, 
      寻找Bootstrap.php,而这个文件中, 必须定义一个Bootstrap类, 
      而这个类也必须继承自Yaf_Bootstrap_Abstract.实例化成功之后, 
      所有在Bootstrap类中定义的, 以_init开头的方法, 都会被依次调用, 
      而这些方法都可以接受一个Yaf_Dispatcher实例作为参数.也可以通过在配置文件中修改application.bootstrap来变更Bootstrap类的位置.

> 简单的示例Bootstrap.php

      <?php
        class Bootstrap extends Yaf_Bootstrap_Abstract {
          public function _initConfig(){
            $config = Yaf_Application::app()->getConfig();
            Yaf_Registry::set("config", $config);
          }
        public function _initDefaultName(Yaf_Dispatcher $dispatcher) {
         $dispatcher->setDefaultModule("Index")->setDefaultController("Index")->setDefaultAction("index");
         }
      }


![](/blog-img/yafb.bmp "phpinfo显示成功螃蟹图标再现")

### 插件使用

Yaf支持用户定义插件来扩展Yaf的功能, 这些插件都是一些类. 它们都必须继承自Yaf_Plugin_Abstract. 插件要发挥功效, 也必须现实的在Yaf中进行注册, 然后在适当的实际, Yaf就会调用它.

> Yaf 支持的Hook

名称 | 触发时机 | 说明 
---|---|---
routerStartup | 在路由之前触发 | 这个是7个事件中, 最早的一个. 但是一些全局自定的工作, 还是应该放在Bootstrap中去完成
routerShutdown | 路由结束之后触发 | 此时路由一定正确完成, 否则这个事件不会触发
dispatchLoopStartup | 分发循环开始之前被触发 | 
preDispatch |  分发之前触发 | 如果在一个请求处理过程中, 发生了forward, 则这个事件会被触发多次
postDispatch | 分发结束之后触发 | 此时动作已经执行结束, 视图也已经渲染完成. 和preDispatch类似, 此事件也可能触发多次
dispatchLoopShutdown |  分发循环结束之后触发 | 此时表示所有的业务逻辑都已经运行完成, 但是响应还没有发送

>　定义插件　

插件类是用户编写的, 但是它需要继承自Yaf_Plugin_Abstract. 对于插件来说, 上一节提到的7个Hook, 它不需要全部关心, 它只需要在插件类中定义和上面事件同名的方法, 那么这个方法就会在该事件触发的时候被调用.
而插件方法, 可以接受俩个参数, Yaf_Request_Abstract实例和Yaf_Response_Abstract实例. 一个插件类
例子如下:

`plugins/User.php`
    
    <?php
      class UserPlugin extends Yaf_Plugin_Abstract {
       public function routerStartup(Yaf_Request_Abstract $request, Yaf_Response_Abstract $respons
      }
       public function routerShutdown(Yaf_Request_Abstract $request, Yaf_Response_Abstract $respo
      }
    }
![](/blog-img/yafc.bmp "phpinfo显示成功螃蟹图标再现")

> 注册插件

插件要生效, 还需要向Yaf_Dispatcher注册, 那么一般的插件的注册都会放在Bootstra中进行. 一个注册插件的例子如下:
   
      <?php
      class Bootstrap extends Yaf_Bootstrap_Abstract{
        public function _initPlugin(Yaf_Dispatcher $dispatcher) 
        {
          $user = new UserPlugin();
          $dispatcher->registerPlugin($user);
        }
      }

![](/blog-img/yafd.bmp "phpinfo显示成功螃蟹图标再现")

>目录

  一般的, 插件应该放置在APPLICATION_PATH下的plugins目录, 这样在自动加载的时候, 加载器通过类名,发现这是个插件类, 就会在这个目录下查找.当然, 插件也可以放在任何你想防止的地方, 只要你能把这个类加载进来就可以

### 获取参数

`Yaf_Request_Http`

代表了一个实际的Http请求, 一般的不用自己实例化它, Yaf_Application在run以后会自动根据当前请求实例它，在控制器内可以使用$this->getRequest()来获取请求信息。更多Yaf_Request_Http类的内容可参见文档：

http://www.laruence.com/manual/yaf.class.request.html#yaf.class.request.http

> 使用示例

    <?php
    class IndexController extends Yaf_Controller_Abstract {

      public function indexAction($name='', $value='') {

       print_r($this->getRequest()->getQuery());
    }

扩展  ` Yaf_Request_Http `，比如加上过滤，数据处理等。先在`library`定义一个`request`的类,再在`Bootstrap.php`里设置`Request`
文件示例：`library/Request.php`

   
![](/blog-img/yafe.bmp "phpinfo显示成功螃蟹图标再现")

      <?php
      class Bootstrap extends Yaf_Bootstrap_Abstract{
              public function _initRequest(Yaf_Dispatcher $dispatcher)
          {
            $dispatcher->setRequest(new Request());
          }
      }

  然后在控制器中可以使用$this->getRequest()->getQuery()来获取参数

      <?php
      class IndexController extends Yaf_Controller_Abstract {
        public function indexAction() {
           print_r($this->getRequest()->getQuery());
      }

关于更多的该类的使用方法，可以参考：
http://www.laruence.com/manual/yaf.class.request.html
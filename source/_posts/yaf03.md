---
title: Yaf---Session | 模板 | 模型  | Cli模式
date: 7/30/2016 09:22:43 PM 
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
Yaf_Session是Yaf对Session的包装, 实现了Iterator, ArrayAccess, Countable接口, 方便使用.关于Yaf_Session的文档介绍：http://www.laruence.com/manual/yaf.class.session.html
<!--more-->

### Session

>使用示例   

![](/blog-img/yaf_1.bmp "phpinfo显示成功螃蟹图标再现")

### 模板

The Yaf_View_Simple class
官方文档：http://www.laruence.com/manual/yaf.class.view.html

Yaf_View_Simple是Yaf自带的视图引擎, 它追求性能, 所以并没有提供类似Smarty那样的多样功能, 和复杂的语法.
对于Yaf_View_Simple的视图模板, 就是普通的PHP脚本, 对于通过`Yaf_View_Interface::assgin`的模板变量,可在视图模板中直接通过变量名使用.

使用 `$this->getView()->assign() `在控制器中定义变量

      <?php
      class IndexController extends Yaf_Controller_Abstract {
        public function indexAction() {
          $mod = new UserModel();
          $list = $mod->where('id',1)->get();
          $this->getView()->assign("list", $list);
          $this->getView()->assign("title", "Smarty Hello World");
          $this->getView()->assign("content", "Hello World");
      }

在模板文件中使用php脚本来输出

      <html>
        <head>
          <meta charset="utf-8">
          <title><?=$title;?></title>
          </head>
          <body>
               <?=$content;?>
          <?php foreach($list as $val):?>
              <p><?=$val['username']?></p>
          <?php endforeach;?>
        </body>
      </html>

### 关闭自动加载模板

Yaf框架默认是开启自动加载模板的，如要关闭自动加载，可在`Bootstrap.php`里设置全局关闭，如：

      <?php
      class Bootstrap extends Yaf_Bootstrap_Abstract
        {
        public function _initConfig(){

          Yaf_Registry::set('config', Yaf_Application::app()->getConfig());
          Yaf_Dispatcher::getInstance()->autoRender(FALSE); // 关闭自动加载模板
        
        }
      }

单独关闭模板加载，可以需要关闭的控制器内利用`Yaf_Dispatcher::getInstance()->disableView()`作：
      
      <?php
      class IndexController extends Yaf_Controller_Abstract {
          /**
          * Controller的init方法会被自动首先调用
          */
          public function init() {
          /**
          * 如果是Ajax请求, 则关闭HTML输出
          */
          if ($this->getRequest()->isXmlHttpRequest()) {
               Yaf_Dispatcher::getInstance()->disableView();
          }
        }
      }
      ?>

###  手动调用指定模板

在控制器里手动调用的方式有2种：

>一、调用当前`$this->_module`目录下的模版，下面是手动调用`view/index/`目录下`hello.phtml`模板

      <?php
          class IndexController extends Yaf_Controller_Abstract
          {
            public function indexAction()
            {
              $this->getView()->assign("content", "Hello World");
              $this->display('hello');
            }
         }

>二、随意调用`view`目录下的模板，下面是调用`view/test/world.phtml`模板
     
      <?php
          class IndexController extends Yaf_Controller_Abstract
          {
              public function indexAction()
              {
                  $this->getView()->assign("content", "Hello World");
                  $this->getView()->display('test/world.phtml');
              }
          }

### 模型

>还有不少同学问, 为什么Yaf没有ORM, 这里有俩方面的考虑:首先, `Yaf并不是万能的, 它只是解决了应用中, 最基本的一个问题, 就是框架带来的额外的性能开销, `然而这本部分的开销和你的应用实际的开销相比, 往往是很小的.但是, Yaf却代表着一种精神, 就是追求简单, 追求高效, 追求:”简单可依赖”, 所以Yaf专注于实现最核心的功能, 提供最稳定的实现.相比ORM, 如果要实现的很方便, 那必然会很复杂, 在当时的情况下, 实现ORM有可能会引入不稳定性第二, 也是最重要的一点是PHP已经提供了对DB的一个轻度封装的PDO, 我认为直接使用PDO, 会更加简单, 更加高效, 我不希望提供一个复杂的ORM包装, 鼓励大家去抛弃简单的PDO而使用ORM. 所以, 最初的时候, Yaf并不包含ORM.诚然, ORM可以提高开发效率, 尤其对于一些简单应用, 所以我想在后续的Yaf的版本中, 会考虑加入ORM, 但是那也绝对会是一个简单的ORM, 类似于Yaf的内建视图引擎: `Yaf_View_Simple`, 简单可依赖.

显然，目前yaf是没有内置的操作数据库类了，那只能自己diy了，yaf的model规则是，类名以Model为后缀，放在放置在`models`文件夹下面

先在`application.ini`配置文件里添加数据库配置信息:

      db.type=mysql
      db.host=localhost
      db.database=test
      db.username=root
      db.password=123
      db.charset = utf8
      db.log = false
      db.collation=utf8_unicode_ci
      db.prefix =

在models文件夹下面新建一个base.php文件:

![](/blog-img/yaff.bmp "phpinfo显示成功螃蟹图标再现")

![](/blog-img/yaf_2.bmp "哟，还不错哦")

>载入第三方的ORM

上面只是一个简单的model实现方法，大家可以再自行完善。在一些项目中，ORM可以提高开发效率,我这里也尝试着载入lavarel框架中所使用的Eloquent ORM。
loquent ORM操作介绍：http://www.golaravel.com/laravel/docs/4.2/eloquent/
因为下载有点慢，我就直接从laravel5.1的包里面直接复制出的eloquent。

- 将文件夹放置到library下面,如下所示

![](/blog-img/yaf_3.bmp "哟，还不错哦")


- 在Bootstarpphp初始化eloquent


          <?php
          Yaf_loader::import("/vendor/autoload.php");
          use Illuminate\Container\Container;
          use Illuminate\Database\Capsule\Manager as Capsule;
          class Bootstrap extends Yaf_Bootstrap_Abstract{
          private $config;
          public function _initConfig() {
          $this->config = Yaf_Application::app()->getConfig();
          Yaf_Registry::set("config", $this->config);
          }
         //载入数据库ORM
         public function _initDatabase()
         {
          $database = array(
            'driver' => $this->config->db->type,
            'host' => $this->config->db->host,
            'database' => $this->config->db->database,
            'username' => $this->config->db->username,
            'password' => $this->config->db->password,
            'charset' => $this->config->db->charset,
            'collation' => $this->config->db->collation,
            'prefix' => $this->config->db->prefix,
          );
          $capsule = new Capsule;
          // 创建链接
          $capsule->addConnection($database);
          // 设置全局静态可访问
          $capsule->setAsGlobal();
          // 启动Eloquent
          $capsule->bootEloquent();
      }


-  在models文件夹下新建UsersModel的Users.php:


      <?php
            use Illuminate\Database\Eloquent\Model as Mymodel;
            class UsersModel extends Mymodel{
                  protected $table = 'user';
            }
            ?>
            在控制器中调用：
            <?php
            class IndexController extends Yaf_Controller_Abstract {
                  public function indexAction() {
                  Yaf_Dispatcher::getInstance()->disableView();
                  $mod = new UsersModel();
                  $data = $mod->find(1)->toArray();
                  print_r($data);
            }


- 更多关于Eloquent ORM的操作介绍可移步：

http://www.golaravel.com/laravel/docs/4.2/eloquent/



### 命令行模式

官方文档地址：http://yaf.laruence.com/manual/yaf.incli.times.html

感觉文档写得有点简单，不好理解，这里聊下我是怎么用的yaf命令行。

>方法一

在yaf中用到命令行大多是为了跑Crontab，首先，为了更好的与web区分(配置文件,argc、argv判断等等).重新创建个入口文件是比较好的做法

      <?php

        define("APP_PATH", realpath(dirname(__FILE__)));

        $app = new Yaf_Application(APP_PATH . "/conf/application.ini");

        $app->getDispatcher()->dispatch(new Yaf_Request_Simple());

然后再新建一个接收命令和操作的控制器Crontab.php:

      <?php

      class CrontabController extends Yaf_Controller_Abstract {

            public function init(){
            Yaf_Dispatcher::getInstance()->disableView();
            }
            public function indexAction($username = ''){
            //to do a crontab
            echo 'we get the name is : '.$username;
            }

        }
      ?>

接下来，我们在命令行中调用。在命令行中切换到你的项目目录，就是cli.php所在目录，然后输入如下命令：
`php cli.php request_uri="/crontab/index"`是不是在命令行看到了输出的字符串。
`request_uri="/crontab/index"` 中的路径便是Controller的路由路径.在例子里指向`/controllers/Crontab.php` 中的 `indexAction()`

>方法二

还有一种方法，通过`Yaf_Application::execute(..)`去实现。

先看一下这个函数的定义：

      public void Yaf_Application::execute ( callable $entry , string $... )
      This method is typically used to run Yaf_Application in a crontab work. Make the crontab work
      can also use the autoloader and Bootstrap mechanism.

第一参数需要定义一个回调函数,也可以是一个类中的某个函数。

示例：`$application->execute(“main”, $argc, $argv);`或

      $application->execute(array(“Class”,”Method”), $argc, $argv);

后面的参数为一个可变列表，值为你希望传入的参数。如些，我们将刚才新建的cli.php文件改写成：

      <?php
      define("APP_PATH", realpath(dirname(__FILE__)));
      $app = new Yaf_Application(APP_PATH . "/conf/application.ini");
      $app->bootstrap()->execute(array('CrontabController','indexAction'),'wulei');

其中如果你需要用bootstrap的初始化的，可以保留，如果不需要的话，也可以把bootstrap去掉。

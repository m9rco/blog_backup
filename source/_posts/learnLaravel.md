title:  Laravel 简短学习 just write the code in the PHP way
date: 2017-05-31 13:58:41
description:
categories:
- Laravel
tags:
- phper
toc:
author:
comments:
original:
permalink:
---
　　** 自用笔记：**本文属于自用笔记，不做详解，仅供参考。

<!-- more -->

### [artisan](https://docs.golaravel.com/docs/5.1/artisan/)


```
英  /,ɑːtɪ'zæn; 'ɑːtɪzæn/
美  /'ɑrtəzn/
n. 工匠，技工
```

### 介绍

---


`Artisan` 是 `Laravel` 的命令行接口的名称，它提供了许多实用的命令来帮助你开发 `Laravel` 应用，它由强大的 `Symfony Console` 组件所驱动。

可以使用 `list` 命令来列出所有可用的 `Artisan` 命令：

```php
php artisan list
```



> Available commands:

命令|explain|说明
---|---|---
clear-compiled|Remove the compiled class file | 清除编译后的类文件
down          |Put the application into maintenance mode|使应用程序进入维修模式
  env     |            Display the current framework environment|显示当前框架环境
  help    |            Displays help for a command|显示命令行的帮助
  list    |            Lists commands|列出命令
  migrate |            Run the database migrations|运行数据库迁移
  optimize|            Optimize the framework for better performance|为了更好的框架去优化性能
  serve |              Serve the application on the PHP development server|在php开发服务器中服务这个应用
  tinker|              Interact with your application|在你的应用中交互
  up    |              Bring the application out of maintenance mode|退出应用程序的维护模式

 ```
 app
  app:name            Set the application namespace 设置应用程序命名空间
 auth
  auth:clear-resets   Flush expired password reset tokens 清除过期的密码重置密钥
 cache
  cache:clear         Flush the application cache 清除应用程序缓存
  cache:table         Create a migration for the cache database table 创建一个缓存数据库表的迁移
 config
  config:cache        Create a cache file for faster configuration loading 创建一个加载配置的缓存文件
  config:clear        Remove the configuration cache file 删除配置的缓存文件
 db
  db:seed             Seed the database with records 发送数据库的详细记录
 event
  event:generate      Generate the missing events and listeners based on  registration  在记录上生成错过的事件和基础程序
 key
  key:generate        Set the application key 设置程序密钥
 make
  make:auth           Scaffold basic login and registration views and routes
  make:console        Create a new Artisan command 生成一个Artisan命令
  make:controller     Create a new controller class 生成一个资源控制类
  make:event          Create a new event class  生成一个事件类
  make:job            Create a new job class
  make:listener       Create a new event listener class
  make:middleware     Create a new middleware class 生成一个中间件
  make:migration      Create a new migration file 生成一个迁移文件
  make:model          Create a new Eloquent model class 生成一个Eloquent 模型类
  make:policy         Create a new policy class
  make:provider       Create a new service provider class 生成一个服务提供商的类
  make:request        Create a new form request class 生成一个表单消息类
  make:seeder         Create a new seeder class
  make:test           Create a new test class
 migrate
  migrate:install     Create the migration repository 创建一个迁移库文件
  migrate:refresh     Reset and re-run all migrations 复位并重新运行所有的迁移
  migrate:reset       Rollback all database migrations 回滚全部数据库迁移
  migrate:rollback    Rollback the last database migration 回滚最后一个数据库迁移
  migrate:status      Show the status of each migration 显示列表的迁移 上/下
 queue
  queue:failed        List all of the failed queue jobs 列出全部失败的队列工作
  queue:failed-table  Create a migration for the failed queue jobs database table     创建一个迁移的失败的队列数据库工作表
  queue:flush         Flush all of the failed queue jobs                 清除全部失败的队列工作
  queue:forget        Delete a failed queue job 删除一个失败的队列工作
  queue:listen        Listen to a given queue 监听一个确定的队列工作
  queue:restart       Restart queue worker daemons after their current job 重启现在正在运行的所有队列工作
  queue:retry         Retry a failed queue job 重试一个失败的队列工作
  queue:table         Create a migration for the queue jobs database table 创建一个迁移的队列数据库工作表
  queue:work          Process the next job on a queue 进行下一个队列任务
 route
  route:cache         Create a route cache file for faster route registration 为了更快的路由登记，创建一个路由缓存文件
  route:clear         Remove the route cache file 清除路由缓存文件
  route:list          List all registered routes  列出全部的注册路由
 schedule
  schedule:run        Run the scheduled commands 运行预定命令
 session
  session:table       Create a migration for the session database table                 创建一个迁移的SESSION数据库工作表
 vendor
  vendor:publish      Publish any publishable assets from vendor packages 发表一些可以发布的有用的资源来自提供商的插件包
 view
  view:clear          Clear all compiled view files
 ```

每个命令也包含了「帮助」界面，它会显示并概述命令可使的参数及选项。只要在命令前面加上 help 即可显示帮助界面：

```
php artisan help migrate
```


```php
php artisan make:auth
```

### 编写命令
---
除了使用 `Artisan` 本身所提供的命令之外，`Laravel` 也允许你自定义 `Artisan` 命令。


自定义命令默认存储在 `app/Console/Commands` 目录中，当然，只要在 `composer.json` 文件中的配置了自动加载，你可以自由选择想要放置的地方。

若要创建新的命令，你可以使用 `make:console Artisan `命令生成命令文件：

```
php artisan make:console SendEmails
```
上面的这个命令会生成 `app/Console/Commands/SendEmails.php` 类，--command 参数可以用来指定调用名称：

```
php artisan make:console SendEmails --command=emails:send
```

### 命令结构

---

一旦生成这个命令，应先填写类的 `signature` 和 `description` 这两个属性，它们会被显示在  `list` 界面中。
命令运行时 `handle` 方法会被调用，请将程序逻辑放置在此方法中。
接下来讲解一个发送邮件的例子。
为了更好的代码重用性，还有可读性，建议把处理业务逻辑的代码抽到一个功能类里。
`Command` 类构造器允许注入需要的依赖，`Laravel` 的 服务容器 将会自动把功能类 `DripEmailer` 解析到构造器中

### [Route](http://d.laravel-china.org/docs/5.1/routing)
---
你可以在 `app/Http/routes.php` 文件中定义应用程序的大多数路由，该文件将会被 `App\Providers\RouteServiceProvider` 类加载。最基本的 `Laravel` 路由仅接受 `URI` 和一个闭包

### [view-Blade](http://d.laravel-china.org/docs/5.1/blade)
---
`Blade` 是 `Laravel` 所提供的一个简单且强大的模板引擎。相较于其它知名的 `PHP` 模板引擎，`Blade` 并不会限制你必须得在视图中使用 `PHP` 代码。所有 `Blade` 视图都会被编译缓存成普通的 PHP 代码，一直到它们被更改为止。这代表 `Blade` 基本不会对你的应用程序生成负担。

`Blade` 视图文件使用 `.blade.php` 做为扩展名，通常保存于 `resources/views` 文件夹内。

`@extends('layouts.app')`

这表示此视图的基视图是 `resources/views/layouts/app.blade.php` 。这个函数还隐含了一个小知识：在使用名称查找视图的时候，可以使用 `.` 来代替 `/` 或 `\`.

`@Section('content') ... @endsection`
这两个标识符之前的代码，会被放到基视图的 `@yield('content')` 中进行输出。

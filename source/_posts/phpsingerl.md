---
title: PHP 单例模式
date: 2016-05-10 10:20:12
description: 
categories:
- Skill
tags: Design Pattern
toc: true
author:
comments:
original:
permalink: 
---
 数学与逻辑学中，singleton定义为“有且仅有一个元素的集合”
在它的核心结构中只包含一个被称为单例的特殊类。通过单例模式可以保证系统中一个类只有一个实例，节省数据库开销
<!--more-->

```php
单例模式是设计模式中最简单的形式之一。这一模式的目的是使得类的一个对象成为系统中的唯一实例。要实现这一点，可以从客户端对其进行实例化开始。因此需要用一种只允许生成对象类的唯一实例的机制，“阻止”所有想要生成对象的访问
/**
 *     singleton Pattern 单例设计模式  3私1公
 */
 class DB 
 {
    private static $_instance;//保存类实例的私有静态成员变量
    //定义一个私有的构造函数，确保单例类不能通过new关键字实例化，只能被其自身实例化
    private final function __construct()//fai nuo最终 的  也就是后面的子类不能覆盖此方法
    {
        echo 'test __construct';                                        
/*      final        -- 用于类、方法前。
        final类   -- 不可被继承。
        final方法 -- 不可被覆盖。        */
    }   
    //定义私有的__clone()方法，确保单例类不能被复制或克隆
    private function __clone() {}
    public static function getInstance() 
    {
        //检测类是否被实例化
        if (!(self::$_instance instanceof self)) //in s tens 奥复            
        {    //在类里调用类的属性 要加$
            //（1）判断一个对象是否是某个类的实例，
            //（2）判断一个对象是否实现了某个接口。
            self::$_instance = new DB();
        }

        return self::$_instance;
    }
}
//调用单例类
DB::getInstance();
$db1=DB::getInstance();
$db2=DB::getInstance();
var_dump($db1);
var_dump($db2);<?php
/**
 *     singleton Pattern 单例设计模式  3私1公
 */
 class DB 
 {
    private static $_instance;//保存类实例的私有静态成员变量
    //定义一个私有的构造函数，确保单例类不能通过new关键字实例化，只能被其自身实例化
    private final function __construct()//fai nuo最终 的  也就是后面的子类不能覆盖此方法
    {
        echo 'test __construct';
    }   
    //定义私有的__clone()方法，确保单例类不能被复制或克隆
    private function __clone() {}
    public static function getInstance() 
    {
        //检测类是否被实例化
        if (!(self::$_instance instanceof self)) //in s tens 奥复            
        {    //在类里调用类的属性 要加$
    //（1）判断一个对象是否是某个类的实例，（2）判断一个对象是否实现了某个接口。
            self::$_instance = new DB();
        }

        return self::$_instance;
    }
}
//调用单例类
DB::getInstance();

$db1=DB::getInstance();
$db2=DB::getInstance();

var_dump($db1);
var_dump($db2);
```

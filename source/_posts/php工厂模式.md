---
title: PHP plant
date: 2016-05-07 10:20:12
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
 顾名思义，工厂是可以加工零件的，PHP程序中的工厂模式也有相同的功能，可以方便的使用一个静态的工厂方法来实例化某一个类，那么这样做的好处是什么呢？初学PHP的设计模式，实例化一个类会给它一些参数以便在其构析的时候可以根据不同的参数反馈出我们需要的结果。
<!--more-->

```php
举例说明，以下是一个User类，非常简单：
/**
*     factory pattern 工厂模式
*/
    interface abstracted        
    {
        public function realCreate();
    }
    //女人类
    class Woman
    {
        public function action()
        {
            echo '这是女人';
        }
    }
    //男人类
    class Man
    {
        public function action()
        {
            echo '这是男人';
        }
    }
    //创建女人
    class WomanCreator implements abstracted 
    {
        public $chromosome;//染色体
        public function realCreate(){
            if ($this->chromosome == "xx") 
            {
                return new Woman();
            }
        }
    }
    //创建男人
    class ManCreator implements abstracted 
    {
        public $chromosome;
        public function realCreate(){
            if ($this->chromosome == "xy" || $this->chromosome == "xyy") 
            {
                return new Man();
            }
        }
    }
    //人类工厂
    class PersonFactory
    {
        public function create($what)
        {
            $create = $what."Creator";  //womanCreator
            return  new $create();
        }
    }
    $create = new PersonFactory();
    $instance = $create->create('woman');
    $instance->chromosome = "xx";
    $instance->realCreate()->action();
?>
```

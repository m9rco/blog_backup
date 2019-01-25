---
title: PHP Builder 建造者模式
date: 2017-02-22 10:20:12
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

- 建造者设计模式的母的是消除其他对象的复杂创建过程

使用建造者设计模式不仅是最佳的做法，而且在某个对象的构造和配置方法改变时尽可能地减少重复的代码

<!--more-->

```php
/**
 * Builder  建造者模式
 * -------------------------------------
 * ** 来自说明 **
 *
 * 建造者设计模式的母的是消除其他对象的复杂创建过程，
 * 使用建造者设计模式不仅是最佳的做法
 * 而且在某个对象的构造和配置方法改变时尽可能地减少重复的代码
 * 
 * ===================================== 
 * ** 应用场景 **
 *
 * 数据库接口类 | 优化基类
 * 
 * -------------------------------------
 * 
 * @version ${Id}$
 * @author Shaowei Pu <54268491@qq.com>
 */
```

---

```php
<?php


// 现在是这样一个情况，您是学生个人档案录入员，你写了这样一个类
class Entering {

	public $info = [];
	public function setName( $name ){
		$this->info['name'] = $name;
	}

	public function setOld( $old ){
		$this->info['old'] = $old;
	}

	public function setGender( $gender ){
		$this->info['gander']  = $gender;
	}
}
// 然后你看到了你们Boss 在许多年前，你还是一个乳臭未干的小孩时写的录入类 
$worker = new Entering;
$worker->setName('jacky');
$worker->setOld('22');
$worker->setGender('男');
// 然后这样就可以了,一个学生的完整信息就这么弄出来了
var_dump($worker->info);
/* 
+----------------------------------------------------------------------
| array (size=3)
|   'name' 	 => string 'jacky' (length=5)
|   'old' 	 => string '22' (length=2)
|   'gander' => string '男' (length=3)
+----------------------------------------------------------------------
*/

// 但是爱折腾的你从来不会放弃任何机会，自从学习了建造者模式的你 总有种蠢蠢欲动，于是乎就有了下面的类
class EnteringBuilder{
	
	protected $_baseObject = null;
	protected $_newInfo    = []  ;

	public function __construct( array $info ){
		$this->_baseObject = new Entering;
		$this->_newInfo  = $info;
	}

	public function build(){
		$this->_baseObject->setName  (  $this->_newInfo['name']);
		$this->_baseObject->setOld 	 (  $this->_newInfo['old']);
		$this->_baseObject->setGender(  $this->_newInfo['gander']);
	}

	public function getInfo(){
		return $this->_baseObject->info;
	}
}

// 然后这样
$new_worker = new EnteringBuilder([
			'name' 	 => 'lucy',
			'old' 	 =>  22,
			'gander' => '女',
	]);
$new_worker->build();
// 然后这样就可以了,一个学生的完整信息就这么弄出来了
var_dump($new_worker->getInfo());
/* 
+----------------------------------------------------------------------
|array (size=3)
|  'name' => string 'lucy' (length=4)
|  'old' => int 22
|  'gander' => string '女' (length=3)
+----------------------------------------------------------------------
*/



```

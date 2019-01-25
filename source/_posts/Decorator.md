---
title: PHP Decorator 装饰器模式
date: 2017-02-23 10:20:12
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

- 如果已有对象的部分内容或功能性发生改变，但是不需要修改原始的结构

迭代新的应用需求

<!--more-->

```php

/**
 * Decorator 装饰器模式
 * -------------------------------------
 * ** 来自说明 **
 *
 * 如果已有对象的部分内容或功能性发生改变，但是不需要修改原始的结构
 *
 * 刚开始楼主觉得这个他么怎么这么像 【 适配器模式 】
 * 然后又把适配器模式的理论复制了过来给各位观众老爷看
 * 
 * |-> 适配器模式： * 通过适配器模式能够使用新的代码和功能性来帮助更新原有的系统。
 *
 * 前面演练过了，适配器还是需要继承原对象来实现，但是我们最开始学习面向对象编程时候
 * 如果对象开始要求启用过多的子类，那么相应的代码就会牺牲编程人员的理解力和可维护性
 * 通常，我们会竭力保证用于一个对象的父-子类关系不超过3个
 * 
 * 
 * ===================================== 
 * ** 应用场景 **
 *
 * 迭代新的应用需求
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
// 现在你是一个农场饲养员，主要负责养猪方面工作

/**
 * 饲养类
 */
class feeding{

	/**
	 * [$mess_tin 饭盒]
	 * @var array
	 */
	public $mess_tin = [];

	/**
	 * [feed 喂食物]
	 * @author 		Shaowei Pu <pushaowei@sporte.cn>
	 * @CreateTime	2017-02-23T19:47:18+0800
	 * @param                               [type] $food [description]
	 * @return                              [type]       [description]
	 */
	public function feed( $food ){
		$this->mess_tin[] = $food;
	}
	/**
	 * [getHow 吃了哪些东西]
	 * @author 		Shaowei Pu <pushaowei@sporte.cn>
	 * @CreateTime	2017-02-23T19:51:41+0800
	 * @return                              [type] [description]
	 */
	public function getHow(){
		return $this->mess_tin;
	}
}

// 以前你通过这样的方式可以喂猪
$you = new feeding;

// 然后老板给了这些食物
$bread = [		
		'apple',
		'tangerine',
		'banana',
	];

foreach ($bread as $key => $value) {
	$you->feed($value);
}

// 看看吃了些啥
var_dump( $you->getHow() );
/* 
+----------------------------------------------------------------------
|array (size=3)
|  0 => string 'apple' (length=5)
|  1 => string 'tangerine' (length=9)
|  2 => string 'banana' (length=6)
+----------------------------------------------------------------------
*/

// 但是有天高级饲养说这喂的这些食物都是小写的 这不行，给猪必须要大写的
// 然后你看了下饲养类，听隔壁兄弟这个类不仅仅用在猪身上
// 也有养羊事业部，羊牛事业部，同样用的这个类
// 你不能瞎几把乱改
// 学了适配器的你马上想到了 使用【适配器模式】可以解决问题
// 但是立马老大就说了不准乱继承。一个父类最多继承 3 个子类
// 于是聪明的你想到了这样一个方法

/**
 * 养猪事业部专用
 */
class feddingDecorator{

	/**
	 * [$_feeding 基类容器]
	 * @var array
	 */
	private $_feeding = [];

	/**
	 * [__construct 基类入变量]
	 * @author 		Shaowei Pu <pushaowei@sporte.cn>
	 * @CreateTime	2017-02-23T20:00:58+0800
	 * @param                               feeding $feeding [description]
	 */
	public function __construct( feeding $feeding )
	{
		$this->_feeding = $feeding;
	}

	/**
	 * [expertFeed 高级食物转换器]
	 * @author 		Shaowei Pu <pushaowei@sporte.cn>
	 * @CreateTime	2017-02-23T20:04:42+0800
	 * @return                              [type] [description]
	 */
	public function expertFeed()
	{
		array_walk($this->_feeding->mess_tin, function( &$value ){
			$value = strtoupper( $value );
		});
	}
}

// 然后这样,前期您还是放心喂

$me = new feeding;
foreach ($bread as $key => $value) {
	$me->feed($value);
}

// 然后你喂完了就开始用的养猪专业部使用的高科技了
$stm = new feddingDecorator( $me );
$stm->expertFeed();
// 看看吃了啥
var_dump($me-> getHow());

/* 
+----------------------------------------------------------------------
|array (size=3)
| 0 => string 'APPLE' (length=5)
| 1 => string 'TANGERINE' (length=9)
| 2 => string 'BANANA' (length=6)
+----------------------------------------------------------------------
*/

// 掌声经久不息






```

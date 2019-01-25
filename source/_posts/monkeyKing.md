---
title: 猴子选大王算法
date: 2016-06-05 12:47:24
description: 
categories:
- Skill
tags: 算法
toc: true
author:
comments:
original:
permalink: 
---
 有M个monkey ，转成一圈，第一个开始数数，数到第N个出圈，下一个再从1开始数，再数到第N个出圈，直到圈里只剩最后一个就是大王 【单项循环数据链表】
<!--more-->

```php

	<?php 
	
	class MonkeyKing
	{ 	
		var $next;
    	var $name;
    public function __construct($name)
    {
        $this->name = $name;
    }
    public static function whoIsKing($count, $num)
    {
        /************* 构造单向循环链表 ******************/
        // 构造单向循环链表
        $current = $first = new MonkeyKing(1);
        for($i=2; $i<=$count; $i++)
        {
            $current->next = new MonkeyKing($i);
            $current = $current->next;
        }
        // 最后一个指向第一个
        $current->next = $first;
        // 指向第一个
        $current = $first;
        /*************** 开始数数 *********************/
        // 定义一个数字
        $cn = 1;
        while($current !== $current->next)
        {
            $cn++;  // 数数
            if($cn == $num)
            {
                $current->next = $current->next->next;
                $cn = 1;
            }
            $current = $current->next;
        }
        // 返回猴子王的名字
        return $current->name;
    }
	}
	// 共10个猴子每3个出圈
	echo MonkeyKing::whoIsKing(10,3);
```
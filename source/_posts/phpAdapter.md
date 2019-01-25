---
title: PHP 适配器模式(Adapter Design Pattern)
date: 2017-02-21 10:20:12
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

- 适配器设计模式知识将某个对象的借口是配为另一个对象所期望的接口

通过适配器模式能够使用新的代码和功能性来帮助更新原有的系统，简单的说需要转化一个对象的接口用于另一个对象中时，在不改变原对象的基础上可以采用适配器模式

<!--more-->

```php
/**
 * Adapter  适配器模式
 * -------------------------------------
 * ** 来自说明 **
 * 
 * 通过适配器模式能够使用新的代码和功能性来帮助更新原有的系统。
 * 简单的说需要转化一个对象的接口用于另一个对象中时，
 * 在不改变原对象的基础上可以采用适配器模式
 * 
 * ===================================== 
 * ** 应用场景 **
 *
 * 对于项目中比较旧的架构底层的基类做接口更改时使用 
 * 
 * -------------------------------------
 * 
 * @version ${Id}$
 * @author Shaowei Pu <542684913@QQ.com>
 */
```

---

```php
<?php

// 你现在是大巍施工队分配到伊朗的挖矿工头。

/**
 * 大巍施工队总部
 */
class headquarters  
{
    protected $_worker;
    public function __construct($worker){
        $this->_worker = $worker;
    }
    public function getWorker(){
        if( !empty($this->_worker) ){
            return '力大无穷'.$this->_worker.'个挖矿师傅';
        }
    }
}
/**
 * 分包出去干活的
 */
class assignWorker
{
    protected $_workerObject;
    public function __construct(headquarters $_workerObject ){
        $this->_workerObject = $_workerObject;
    }
    public function assign(){
        echo $this->_workerObject->getWorker(); // 将错误信息输出至控制台
    }
}

/**
 * 现在你要20个力大无穷的搬砖师傅，然后找下总部，总部就给你了
 */
$Iran   = new assignWorker(new headquarters(20));
$Iran->assign();
echo "<hr/>";

// 日复一日，年复一年，你又被调到黎巴嫩挖矿，这边不同于你在伊朗的日子了，这里你要挖取钻石矿
// 但是这个钻石矿要几个砖家配合挖矿师傅才行，可是公司现在不能提供给你其他部门，因为咱们是百年大企业，主单位不能随便加部门进去
// 大巍国际的人才培养很利索，要什么有什么，这可难不倒你。所以你想了这样一个办法
// 
/**
 * 黎巴嫩专用适配器
 */
class Adapter extends headquarters
{
    public function __construct($worker){
        parent::__construct($worker);
        $this->getallheaders();
    }
    public function getallheaders(){
        $this->_worker ='勇敢过人的'.($this->_worker/2).'个砖家在加上'.$this->_worker;
    }
}
// 这下你要的的人就出来了
$Lebanon = new assignWorker(new Adapter(20));
$Lebanon->assign();

```

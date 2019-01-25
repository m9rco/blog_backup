---
title: [后端Jquery] - 轻量级无依赖 composer 超小巧的页面抓取分析类
date: 2017-04-19 10:20:12
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

有时候我们需要抓取一个页面的一些信息来完成接口，用完`curl`得到`body`后想获取某个标签的时候。 一看到正则委屈么，委屈么

<!--more-->

```php
> 有时候我们需要抓取一个页面的一些信息来完成接口，用完`curl`得到`body`后想获取某个标签的时候。 一看到正则委屈么，委屈么

---

### php 的 DOM 模块

> PHP自带扩展 http://php.net/dom

```php
<?php
/**
 * @author 		Shaowei Pu <542684913@qq.cn>
 * @CreateTime	2017-04-17T19:25:59+0800
 */

$doc = new DOMDocument();

$html = <<<HTML_SECTION
<html><head><title>Sunyanzi's Test</title></head>
<body>
  <h1>Hello World</h1>
  <a href="http://segmentfault.com/" id="onlylink">Hey Welcome</a>
</body></html>
HTML_SECTION;

$doc->loadHTML( $html );

$h1Elements = $doc->getElementsByTagName( 'h1' );
foreach( $h1Elements as $h1Node ){
    echo $h1Node->nodeValue;
} 
echo $doc->getElementById( 'onlylink' )->getAttribute( 'href' );

$xpath = new DOMXPath( $doc );
// also prints "http://segmentfault.com/" ... locate via h1 ... 

echo $xpath->evaluate('string(//h1[text()="Hello World"]/following-sibling::a/@href)'); 
```

基本上， 等到你熟练掌握 XPath 之后 ， 你会发现 DOM 比正则要灵活得多 ...

---

### PhpQuery

> 使用PhpQuery 完全可以省略curl抓取页面的那一步，写法完全参照Jquery

https://github.com/TobiaszCudnik/phpquery



```php
/**
 * @author 		Shaowei Pu <542684913@qq.cn>
 * @CreateTime	2017-04-17T19:25:59+0800
 */
      \phpQuery::newDocumentFile('https://v.qq.com/x/cover/o5neekjf0pl6e0r.html');  
		 libxml_use_internal_errors(true);
		// 腾讯视频的真实URL 
		$url = pq('link[rel="canonical"]')[0]->attr('href');


```

---

### 总结
1. web 采集多样性，如果不是抱着学习正则表达式的态度，应当灵活使用类库
2 偷懒万岁！



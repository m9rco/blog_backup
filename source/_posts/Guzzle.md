title: 利用Guzzle刷豆瓣热评引发的联想
date: 2017-04-12 12:47:24
description: 
categories:
- php
tags:
- phper
toc: true
author:
comments:
original:
permalink: 
---

#### Guzzle是一个PHP的HTTP客户端，用来轻而易举地发送请求，并集成到我们的WEB服务上。

- 接口简单：构建查询语句、POST请求、分流上传下载大文件、使用HTTP cookies、上传JSON数据等等 。
- 发送同步或异步的请求均使用相同的接口。
- 使用PSR-7接口来请求、响应、分流，允许你使用其他兼容的PSR-7类库与Guzzle共同开发。
- 抽象了底层的HTTP传输，允许你改变环境以及其他的代码，如：对cURL与PHP的流或socket并非重度依赖，非阻塞事件循环。
- 中间件系统允许你创建构成客户端行为。

<!-- more -->


## 所需包

```
    "require": {
    	"guzzlehttp/guzzle": "6.2.*"
    }
```

## 源码

```
<?php
include_once dirname(__FILE__).'/vendor/autoload.php';

use \GuzzleHttp\Client;
use \GuzzleHttp\Cookie\CookieJar;
use \GuzzleHttp\Exception\RequestException;

/**
 * 豆瓣租房刷留言
 */

class DouBanBrush{
    public $jar;
    public $clock = 10;
    const SLEEP   = 1200;   // 睡20 分钟

    /**
     * [__autoload 初始化]
     * @author     Shaowei Pu <542684913@qq.com>
     * @CreateTime  2017-04-12T10:39:28+0800
     * @param                               [type] $account  [description]
     * @param                               [type] $password [description]
     * @return                              [type]           [description]
     */
    public function __construct( $account, $password ){
        $this->jar = new CookieJar;
        try{
            if( $this->login( $account, $password ) == '200'){
                echo "----------【 START 】----------\n";
                    $this->send();
                echo "----------【  END  】----------\n";
            }else{
              echo "登录失败～！";
            }
        }catch (RequestException $e) {
            var_dump( $e->getRequest());
            if ($e->hasResponse()) {
                var_dump( $e->getResponse());
            }
        }
    }
    /**
     * [login 登录]
     * @author     Shaowei Pu <542684913>
     * @CreateTime  2017-04-12T10:42:16+0800
     * @return                              [type] [description]
     */
    public function login( $account , $password ){
      // 清楚空间内cookie
      // $this->jar->clear();
      return ( new Client([ 'cookies'  =>  true ]) )->request(
            'POST', 
            'https://accounts.douban.com/j/popup/login/basic',
            [
              'version' => 1.1 ,
              'cookies'         => $this->jar,
              'headers'         => [
                                  'Accept'       => 'application/json',
                                  'Referer'      => '登录来源页'
              ],
              'form_params'     => [
                                'source'           => 'group',
                                'referer'          => '提交接口',
                                'name'             => $account,
                                'password'         => $password,
                                'captcha_id'       => '',
                                'captcha_solution' => ''
                ]
          ])->getStatusCode();
    }
    /**
     * [send 发送内容]
     * @author     Shaowei Pu <542684913@qq.com>
     * @CreateTime  2017-04-12T10:43:17+0800
     * @return                              [type] [description]
     */
    public function send() {
        // 获得 ck
        $this->reload();        
        $ck    = 'ntxB';
        array_map(function( $val ) use  (& $ck ){  $val['Name'] == 'ck' && $ck = $val['Value']; }, $this->jar->toArray());
        // 计时器
        while ( $this->clock > 0 ) {
          $send_content =  ( new Client([ 'cookies'  =>  true ]) )->request(
            'POST', 
            '提交接口',
            [
              'version'         => 1.1,
              'cookies'         => $this->jar,
              'headers'         => [
                                  'Accept'       => 'application/json',
                                  'Referer'      => '来源页'
              ],
              'form_params'     => [
                                'ck'                => $ck,
                                'rv_comment'        => '自己顶一下～！',
                                'start'             => 0,
                                'submit_btn'        =>'加上去'
                                ]
              ])->getBody()->getContents();
              echo date('Y-m-d H:i:s').' '.$this->clock."\n";
              sleep( self::SLEEP );
              --$this->clock;
        }
    }
    /**
     * [reload 刷新页面]
     * @author     Shaowei Pu <542684913@qq.com>
     * @CreateTime  2017-04-12T13:35:58+0800
     * @return                              [type] [description]
     */
    public function reload(){
      (new Client([ 'cookies'  =>  true ])) ->request('GET', '访问页',[
          'cookies'         => $this->jar,
          'headers'         => [
                'Accept'       => 'application/json',
                'Referer'      => '来源页'
       ]]);
    }
}

new DouBanBrush('账号','密码');

```

## 还没做的事

- 验证码识别
- 优化效率

## 致敬

像 @娃娃脾气 大佬致敬
















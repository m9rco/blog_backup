title: swoole 学习第一章 Task进程与Timer进程
date: 2016-09-10 18:29:00
description:
categories:
- publish
tags:
- Swoole
toc: true
author:
comments:
original:
permalink:
---
**Swoole** 据说是可以php革命的一个东西，更有屌丝说看见swoole如见php的未来一般,作者不才看官方教程实在迷糊，特意花大洋买了视频来看，希望与大家共同进步

<!-- more -->

![swoole](https://psv.oss-cn-shanghai.aliyuncs.com/swoole.png)

**Swoole** 据说是可以php革命的一个东西，更有屌丝说看见swoole如见php的未来一般,作者不才看官方教程实在迷糊，特意花大洋买了视频来看，希望与大家共同进步

 * 补坑环节
 * 介绍swoole进程的基本知识
 * 介绍task进程的原理，使用方法和常见问题的解决方法
 * 介绍如何使用定时器，定时器使用的一些小技巧和调试方法
 * 装逼环节


### 补坑环节
-----
> 什么是进程，所谓进程其实就是操作系统中一个正在运行的程序，我们在一个终端当中，通过php，运行一个php文件，这个时候就相当于我们创建了一个进程，这个进程会在系统中驻存，申请属于它自己的内存空间系统资源并且运行相应的程序

那么我们将这个模型做一下简化，对于一个进程来说，它的核心内容分为两个部分，一个是它的内存，这个内存是这进程创建之初从系统分配的，它所有创建的变量都会存储在这一片内存环境当中

一个是它的上下文环境我们知道进程是运行在操作系统的，那么对于程序来说，它的运行依赖操作系统分配给它的资源，操作系统的一些状态，以及它自己的一些状态，这些都构成了这个进程的上下文环境

在操作系统中可以运行多个进程的，对于一个进程来说，它`可以通过一个系统函数创建自己的子进程`，那么当我们在一个进程中创建出若干个子进程的时候那么可以看到如图，我们以两个方框代表父进程创建出来的子进程，那么子进程和父进程一样，拥有自己的内存空间和上下文环境

需要注意的是，在创建出来的新的子进程当中，它会复制自己的父进程的内存空间和上下文环境，也就是说子进程自己的内存空间和父进程的内存空间是独立的，相互没有任何影响的，如果修改子进程当中的某个变量，它不会影响自己的父进程，我们在父进程中创建一个变量`$temp` ,那么它的两个子进程当中也有`$temp`

![swoole](https://image20160910.oss-cn-beijing.aliyuncs.com/clipboard.png)

- [x] 子进程会复制父进程的内存空间和上下文环境
- [x] 修改某个子进程的内存空间，不会修改父进程或其他子进程中的内存空间
- [x] Swoole本身也是一个多进程的模型，它有多个`worker`进程和自己`master`进程,那么多个`worker`进程中创建的变量之间是不能通用的

### 共享内存

上面我们说了，进程当中的变量是不能通用的，那我怎么实现两个进程之间的通信呢？如图我们看到有一个子进程1和子进程2 他们拥有不同的内存空间和上下文环境，那么我们想实现它们之间的通信的话就可以用我们的`共享内存`，共享内存在操作系统中比较一个特殊的内存，它并不依赖于进程而存在，并不属于任何进程，我们可以调用系统提供的系统函数，来创建一片`共享内存`并指定它的`索引`，通过索引任何一个进程都可以在这片共享内存中申请内存空间，并在其中储存对应的值

![swoole](https://image20160910.oss-cn-beijing.aliyuncs.com/QQ%E5%9B%BE%E7%89%8720160910134602.png)

- [x] 共享内存不属于任何一个进程
- [x] 在共享内存中分配的内存空间可以被任何进程访问，只要这个进程拥有这片共享内存的`索引`
- [x] 即使进程关闭，共享内存任然可以继续保留在操作系统当中

举例来说，当子进程1 通过索引在共享内存中分配了一片内存并将它命名为 `$a`并赋值为`vg`,那么子进程2中同样可以去访问这个`$a`并且 得到`vg`这个值，它也可以修改这个值，同样子进程1可以通过变量得到这个修改，这样它们就实现了两个子进程之间的通信啦

可以通过几个命令来看一下

```bash
[pushaowei@localhost ~]# ipcs -m

------ Shared Memory Segments --------
key        shmid      owner      perms      bytes      nattch     status
0x00000000 0          root       600        524288     21         dest
```
当程序  生成了一片共享内存过后，`key`值是我们创建共享内存中所申明的。`shmid`就是这个共享内存的`索引`，我们可以通过这个id来访问指定的 内存空间,`owner`是创建内存的用户，`perms` 是它的访问权限，`bytes`是它的大小

------

## Swoole 的结构

在swoole 没出现之前，php写web开发的时候需要依赖nginx 这样的web应用服务器并且依赖`fpm`的解析的 ,`fpm`大家都知道它同样是一个多进程的php解析器，当一个新的请求过来的时候`fpm`会创建一个新的进程去处理这个请求，这样的话系统的开销是用于创建和销毁进程，导致整个程序的效应效率并不是十分的高，那么在swoole当中，swoole采用和fpm完全不用的架构，如图所示，整个swoole扩展可以分为三层

- [ ] 第一层，Master进程，这个是swoole的主进程,这个进程是用于处理swoole的核心事件驱动的，那么在这个进程当中可以看到它拥有若干个`Reactor`[线程]，swoole所有对于事件的监听都会在这些线程中实现，比如来自客户端的连接，本地通信用的管道，以及异步操作用到的描述符
- [ ] Manager进程，创建管理更下一层的Worker进程以及Task Worker 进程，它仅仅做分配
- [ ] Worker进程以及Task worker 进程，worker 进程属于swoole的主逻辑进程，用户处理客户端的一系列请求，再往下一层是taskworker进程这一进城是swoole提供的异步工作进程，这些进程主要用于处理一些耗时较长的同步任务，

![swoole](https://image20160910.oss-cn-beijing.aliyuncs.com/9110B9CBB4484E6D86B4E217CD409925.jpg)

在swoole 当中进程与进程之间的通信是通过管道来实现的， 在master进程当中当`Reactor`接收到了来自客户端的数据的时候，这些数据会通过管道发送给`Worker`进程由`Worker`进程进行处理，那么 `Worker`进程需要投递任务到`Task Worker`进程当中的时候也是通过管道来实现数据投递，我们可以通过设置swoole的配置参数来使得`task`与 `worker` 进程之间的通信走系统的`消息队列`

当客户端的一个新的连接过来时，会被 `Main Reactor` 线程接收到，然后将这个连接读写操作的监听，注册到对应的`Reactor`线程当中，并通知`Worker`进程处理对应的`OnClient`，也就是接收到连接的回调，当`Worker`进程出现意外，或出现一定的请求次数关闭后，`Manager`进程会重新发起一个新的`Worker`进程，保证系统当中的`Worker`进程的数目是固定的，这样一来就完成了整个swoole扩展的结构


------

## Task 进程以及Task Worker进程

task 进程是独立于worker进程当中的一个工作进程，用于处理一些耗时较长的逻辑，这些逻辑如果在task 进程当中处理时并不会影响worker 进程处理来自客户端的请求，由此大大提高了swoole处理并发的能力

![swoole](https://image20160910.oss-cn-beijing.aliyuncs.com/62394E88F88B4D4D81D7DB0147C04738.jpg)

如图可以看到在`worker`进程到中，我们调用对应的`task()`方法发送数据通知到`task worker` 进程，`task worker`进程会在`onTask()`回调中 接收到这些数据，并进行处理，处理完成之后通过调用`finsh()`函数或者直接`return`返回消息给`worker`进程，worker进程在`onFinsh()`进程收到这些消息并进行处理

- [x] 两个进程之间是通过Unix Sock 管道通信(也可配置通过消息队列通信);

> Task Worker 的使用

使用linux nc 工具可以模拟客户端连接
```bash
[pushaowei@localhost www]# nc 127.0.0.1 9501
```
开启 task

```php
$serv->set(array(
    'task_worker_num' => 8
));
```

```php
<?php
class Server
{
    private $serv;
    public function __construct() {
        $this->serv = new swoole_server("0.0.0.0", 9501);
        $this->serv->set(array(
            'worker_num' => 8,
            'daemonize' => false,
            'max_request' => 10000,
            'dispatch_mode' => 2,
            'debug_mode'=> 1,
            'task_worker_num' => 8
        ));
        $this->serv->on('Start', array($this, 'onStart'));
        $this->serv->on('Connect', array($this, 'onConnect'));
        $this->serv->on('Receive', array($this, 'onReceive'));
        $this->serv->on('Close', array($this, 'onClose'));
        // bind callback
        $this->serv->on('Task', array($this, 'onTask'));
        $this->serv->on('Finish', array($this, 'onFinish'));
        $this->serv->start();
    }
    public function onStart( $serv ) { //创建连接要经历的
        echo "Start\n";
    }
    public function onConnect( $serv, $fd, $from_id ) { //客户端有连接过来会发起的，有几个连接就有几个fd
        echo "Client {$fd} connect\n";
    }
    public function onReceive( swoole_server $serv, $fd, $from_id, $data ) {
        //客户端发送来的数据就跑到这里来了，fd就是第几个连接上的
        echo "来自客户端的请求 本次的fd为 {$fd}:{$data}\n";
        // 如果我们收到了来自客户端的数据，需要创建一个任务，我们可以通过这样的方式
        //首先我们创建一个数组用于存放需要传递给task的数据
        $param =[
            'task' =>'task_1', //task任务
            'param' => $data, //收到来自客户端的数据传递给它
            'fd' => $fd //客户端的描述符也给它传递过去
        ];
        $serv->task( json_encode( $param ) );
        //通过serv的task方法将数据传递出去，task传递的时候只能传递一个字符串，需要用json或者序列化将这个数据处理
    }
    public function onClose( $serv, $fd, $from_id ) {
        echo "Client {$fd} close connection\n";
    }
    public function onTask($serv,$task_id,$from_id, $data) {

        //onTask回调中会收到onReceive发来的这个任务，
        echo "这次发送过来的task_id 为 {$task_id} 我们的from_id 为 {$from_id}\n";
        $data = json_decode($data,true); //解析发送来的数据
        echo "Receive Task :{$data['task']}\n";
        var_dump($data['param']); //打印客户端发来的数据

        $serv->send( $data['fd'], "hello task task_id == {$task_id}"); //这是发给客户端的

        //处理完成之后调用send函数通过接收到的描述符给客户端发送数据
        return "Task {$task_id}'s result";
        //然后return 返回给worker 进程 告诉他们想说的
    }
    public function onFinish($serv,$task_id, $data) {
        //onFinish收到上面回来的消息就可以将这个data 打印出来
        echo "Task {$task_id} finish\n";
        echo "Result: {$data}\n";
    }
}
$server = new Server();
```

### Task 常见问题

- task 传递数据最好小于8K，如果数据大于8k 超过swoole buff 空间后这些数据会被swoole 写入临时文件`/tmp`进行传递，那么在onTask接收到实际的任务的时候，他会去读取这个文件将这个数据读出来
- task 传递对象可以通过序列化传递一个对象的拷贝，task中对对象的改变不会反映到worker进程中数据库连接网络连接对象不可传递
- task onFinsh 回调会发回调用task方法的worker 进程

### mysql 连接池

先来整个数据库
```sql
create database test charset 'utf8';
use test;
 create table vg(
    -> id int unsigned auto_increment,
    -> value char(10) not null default'',
    -> primary key(id)
    -> )engine=InnoDb;
```
上源码
```php
<?php

/**
 1. Class MySqlPlool by:pushaowei
 */
class MySqlPlool{

	private $serv;
	private $pdo;
	public function __construct(){
		$this->serv = new swoole_server('127.0.0.1',9502);
		$this->serv ->set([
			'worker_num' =>8,
			'daemonize' =>false,
			'max_request' =>10000,
			'dispatch_mode' =>3,
			'debug_mode' =>1 ,
			'task_worker_num' =>8
			]);
		$this->serv->on('WorkerStart', array($this, 'onWorkerStart'));
		$this->serv->on('Connect', array($this, 'onConnect'));
		$this->serv->on('Receive', array($this, 'onReceive'));
		$this->serv->on('Close', array($this, 'onClose'));
		// bind callback
		$this->serv->on('Task', array($this, 'onTask'));
		$this->serv->on('Finish', array($this, 'onFinish'));
		$this->serv->start();
	}

	/**
	 * [onConnect 创建连接]
	 * @param  [type] $serv    [description]
	 * @param  [type] $fd      [description]
	 * @param  [type] $from_id [description]
	 * @return [type]          [description]
	 */
	public function onConnect($serv, $fd ,$from_id ){
		echo "Client {$fd} connect \n";
	}

	/**
	 * [onWorkerStart 创建pdo连接,woker进程创建之初被调用]
	 * @param  [type] $serv      [description]
	 * @param  [type] $worker_id [description]
	 * @return [type]            [description]
	 */
	public function onWorkerStart($serv, $worker_id){
		echo "onWorkerStart\n";
		$this->pdo = new PDO("mysql:dbname=test;host=127.0.0.1",'root','123456');
		$this->pdo -> exec('SET NAMES utf8');//设置通信编码
		$this->pdo -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

	}
	/**
	 * [onReceive 这里收到客户端的请求]
	 * @param  swoole_server $serv    [description]
	 * @param  [type]        $fd      [description]
	 * @param  [type]        $from_id [description]
	 * @param  [type]        $data    [description]
	 * @return [type]                 [description]
	 */
	public function  onReceive (swoole_server $serv ,$fd , $from_id ,$data ){
		$task= [
			'sql' =>'insert into vg  values (?,?)',
			'params' => ['',$fd],
			'fd' => $fd //描述符
		];
		$serv ->task(json_encode($task));
	}
	/**
	 * [onTask 处理sql,接收客户端的$data]
	 * @param  [type] $serv    [description]
	 * @param  [type] $task_id [description]
	 * @param  [type] $from_id [description]
	 * @param  [type] $data    [description]
	 * @return [type]          [description]
	 */
	public function onTask($serv,$task_id,$from_id,$data){
		echo '已经跑onTask这里来啦';

		try{
			$data = json_decode($data,true);

			$stement = $this->pdo->prepare($data['sql']);
			$stement ->execute($data['params']);
			$serv->send($data['fd'],'insert succed'); //将返回结果给客户端
			return true;
		}catch(PDOException $e)
		{
			var_dump($e->getMessage());
			return false;
		}
	}
	/**
	 * [onFinish description]
	 * @param  [type] $serv    [description]
	 * @param  [type] $task_id [description]
	 * @param  [type] $data    [description]
	 * @return [type]          [description]
	 */
	public function onFinish($serv,$task_id,$data){
		var_dump("resut:" .$data);
	}

	/**
	 * @param $serv
	 * @param $fd
	 * @param $from_id
	 */
	public function onClose( $serv, $fd, $from_id ) {
		echo "Client {$fd} close connection \n";
	}
}
new MySqlPlool();
```
说说都发生了什么，通过N个task进程来维持数据库操作，每个task进程中都有一个pdo实例然后通过数据库连接来实现一个异步的数据库操作，`onWorkerStart`这个会在worker进程创建之初被回调，它并不区分自己是`worker`进程还是`task worker `进程,我们需要通过代码进行控制，
```php
public function onWorkerStart($serv, $worker_id){
	echo "onWorkerStart\n";
	//
	//让它只有是taskworker的时候才创建pdo连接
	if($serv -> taskworker){
		$this->pdo = new PDO("mysql:dbname=test;host=127.0.0.1",'root','123456');
		$this->pdo -> exec('SET NAMES utf8');//设置通信编码
		$this->pdo -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	}
	else
	 echo "我们只让taskworker连";
}
```
运行结果
```bash
[pushaowei@localhost www]# php server.php
我们只让task worker 连接
我们只让task worker 连接
我们只让task worker 连接
我们只让task worker 连接
我们只让task worker 连接
我们只让task worker 连接
taskWorkerStart
taskWorkerStart
我们只让task worker 连接
taskWorkerStart
我们只让task worker 连接
taskWorkerStart
taskWorkerStart
taskWorkerStart
taskWorkerStart
taskWorkerStart
```
出现的worker 与前面咱们设定的值对应
```php
$this->serv ->set([
	'worker_num' =>8,
	'daemonize' =>false,
	'max_request' =>10000,
	'dispatch_mode' =>3,
	'debug_mode' =>1 ,
	'task_worker_num' =>8
	]);
```
-----
**Timer定时器**
Timer定时器 是Swoole提供的一个内置功能，这个功能提供精度更高的毫秒级的定时器

- 基于Reactor 线程(在task worker 中使用系统定时器);
- 基于epoll的timeout 机制实现
- 为了提高timer的检索效率，在swoole中实现了一个堆来存放timer，这个堆是一个最小堆，它的存放的索引是每个timer定时器的 距离下一次相应剩余的时间，这个时间越小这个timer就在堆中所放的位置就会离堆顶越近，每次遍历的时候都会从堆顶往下检索，每一次下沉索引都会检测到剩余时间越长的timer，当最上面的timer可以运行的时候我们只需要遍历少量的timer都可以将所有的timer从这个堆中取出来，提高了 检索的效率

### timer-使用
一共有两种定时器
```php
int swoole_timer_tick(int $ms, mixed $callback, mixed $param = null);
int swoole_timer_after(int $after_time_ms, mixed $callback_function);
```
`tick ` 是创建一个永久的定时器，这个定时器会在swoole 一直运行，并在指定的毫秒间隔每隔一段时间执行一次，并调用指定的`$callback`函数，
`after` 是指定一个临时的一次性的定时器，这个定时器会在`$after_time_ms`指定毫秒数后调用这个`$callback_function`方法
```php
public function onWorkerStart($serv, $worker_id){
	//当worker id =0 的时候我们才创建这个tick
	if($worker_id == 0)
	{
		swoole_timer_tick(1000, function($timer_id,$parmas){
			echo "QQ:542684913\n";
			echo "{$parmas} \n";
		}, "hello");
	}
}
```
### timer 常见问题
可以通过tick方法的第三个参数传递，也可以使用use 闭包来传递一个参数进去，onTimer 是在调用tick方法的进程中回调，因此可以直接使用在Worker进程中申明的对象（局部变量无法访问）；tick方法会返回timer_id 可以使用swoole_timer_clear清除指定的定时器

### timer实例

Swoole Crontab

`Crontab`是linux上的一个定时程序，它的实现最小为分钟，我们可以设置`swoole crontab`实现一个更精确的定时
原理：使用` tick `方法，每1s 检查一次`crontab` 任务表，如果发现有需要执行的任务，就通知worker进程处理任务,
步骤
- 解析`crontab`文件，并存入DB
```php
?php
/**
 * Created by PhpStorm.
 * User: ClownFish 542684913@qq.com
 * Date: 14-12-27
 * Time: 上午11:59
 */
class ParseCrontab
{
    static public $error;
    /**
     *  解析crontab的定时格式，linux只支持到分钟/，这个类支持到秒
     * @param string $crontab_string :
     *
     *      0     1    2    3    4    5
     *      *     *    *    *    *    *
     *      -     -    -    -    -    -
     *      |     |    |    |    |    |
     *      |     |    |    |    |    +----- day of week (0 - 6) (Sunday=0)
     *      |     |    |    |    +----- month (1 - 12)
     *      |     |    |    +------- day of month (1 - 31)
     *      |     |    +--------- hour (0 - 23)
     *      |     +----------- min (0 - 59)
     *      +------------- sec (0-59)
     * @param int $start_time timestamp [default=current timestamp]
     * @return int unix timestamp - 下一分钟内执行是否需要执行任务，如果需要，则把需要在那几秒执行返回
     * @throws InvalidArgumentException 错误信息
     */
    static public function parse($crontab_string, $start_time = null)
    {
        if (is_array($crontab_string)) {
            return self::_parse_array($crontab_string, $start_time);
        }
        if (!preg_match('/^((\*(\/[0-9]+)?)|[0-9\-\,\/]+)\s+((\*(\/[0-9]+)?)|[0-9\-\,\/]+)\s+((\*(\/[0-9]+)?)|[0-9\-\,\/]+)\s+((\*(\/[0-9]+)?)|[0-9\-\,\/]+)\s+((\*(\/[0-9]+)?)|[0-9\-\,\/]+)\s+((\*(\/[0-9]+)?)|[0-9\-\,\/]+)$/i', trim($crontab_string))) {
            if (!preg_match('/^((\*(\/[0-9]+)?)|[0-9\-\,\/]+)\s+((\*(\/[0-9]+)?)|[0-9\-\,\/]+)\s+((\*(\/[0-9]+)?)|[0-9\-\,\/]+)\s+((\*(\/[0-9]+)?)|[0-9\-\,\/]+)\s+((\*(\/[0-9]+)?)|[0-9\-\,\/]+)$/i', trim($crontab_string))) {
                self::$error = "Invalid cron string: " . $crontab_string;
                return false;
            }
        }
        if ($start_time && !is_numeric($start_time)) {
            self::$error = "\$start_time must be a valid unix timestamp ($start_time given)";
            return false;
        }
        $cron = preg_split("/[\s]+/i", trim($crontab_string));
        $start = empty($start_time) ? time() : $start_time;
        if (count($cron) == 6) {
            $date = array(
                'second' => self::_parse_cron_number($cron[0], 0, 59),
                'minutes' => self::_parse_cron_number($cron[1], 0, 59),
                'hours' => self::_parse_cron_number($cron[2], 0, 23),
                'day' => self::_parse_cron_number($cron[3], 1, 31),
                'month' => self::_parse_cron_number($cron[4], 1, 12),
                'week' => self::_parse_cron_number($cron[5], 0, 6),
            );
        } elseif (count($cron) == 5) {
            $date = array(
                'second' => array(1 => 1),
                'minutes' => self::_parse_cron_number($cron[0], 0, 59),
                'hours' => self::_parse_cron_number($cron[1], 0, 23),
                'day' => self::_parse_cron_number($cron[2], 1, 31),
                'month' => self::_parse_cron_number($cron[3], 1, 12),
                'week' => self::_parse_cron_number($cron[4], 0, 6),
            );
        }
        if (
            in_array(intval(date('i', $start)), $date['minutes']) &&
            in_array(intval(date('G', $start)), $date['hours']) &&
            in_array(intval(date('j', $start)), $date['day']) &&
            in_array(intval(date('w', $start)), $date['week']) &&
            in_array(intval(date('n', $start)), $date['month'])
        ) {
            return $date['second'];
        }
        return null;
    }
    /**
     * 解析单个配置的含义
     * @param $s
     * @param $min
     * @param $max
     * @return array
     */
    static protected function _parse_cron_number($s, $min, $max)
    {
        $result = array();
        $v1 = explode(",", $s);
        foreach ($v1 as $v2) {
            $v3 = explode("/", $v2);
            $step = empty($v3[1]) ? 1 : $v3[1];
            $v4 = explode("-", $v3[0]);
            $_min = count($v4) == 2 ? $v4[0] : ($v3[0] == "*" ? $min : $v3[0]);
            $_max = count($v4) == 2 ? $v4[1] : ($v3[0] == "*" ? $max : $v3[0]);
            for ($i = $_min; $i <= $_max; $i += $step) {
                $result[$i] = intval($i);
            }
        }
        ksort($result);
        return $result;
    }
    static protected function _parse_array($crontab_array, $start_time)
    {
        $result = array();
        foreach ($crontab_array as $val) {
            if(count(explode(":",$val)) == 2){
                $val = $val.":01";
            }
            $time = strtotime($val);
            if ($time >= $start_time && $time < $start_time + 60) {
                $result[$time] = $time;
            }
        }
        return $result;
    }
}
```
- 在tick的回调中，检查所有的crontab任务，找到满足当前时序的任务，并执行



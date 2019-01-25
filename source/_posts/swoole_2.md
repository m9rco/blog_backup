title: swoole 学习第二章 Event Io 与 process
date: 2016-09-11 17:02:00
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
 * 介绍异步非阻塞io、进程的相关知识
 * 介绍Event Loop 异步io的使用、常见问题和解决方案和实例
 * 介绍Process 如何使用对象，Process通信使用实例
 * 装逼环节

<!-- more -->

![swoole](https://psv.oss-cn-shanghai.aliyuncs.com/swoole.png)

> * 介绍异步非阻塞io、进程的相关知识
> * 介绍Event Loop 异步io的使用、常见问题和解决方案和实例
> * 介绍Process 如何使用对象，Process通信使用实例
> * 装逼环节

### 进程
刚刚才说了，子进程当复制一个父进程的时候会复制它的内存以及它的上下文环境，除了这些之外，子进程会复制父进程的io句柄(fd描述符)
![swoole](https://image20160910.oss-cn-beijing.aliyuncs.com/clipboard.png)

- [x] 子进程会复制父进程的IO句柄(我们打开的一个文件，以及创建的一个socked连接，这些都属于句柄，比如我在父进程内打开了一个文件fopen拥有一个fd描述符。那么子进程中同样拥有这个句柄，并且可以对同一个文件进行读写操作，这样的话多个进程对一个文件进行读写操作的话就会对文件造成混乱，这个时候我们就需要一个文件锁的东西，fd描述符);

### 进程之间的通信方式 -- 管道

![swoole](https://image20160910.oss-cn-beijing.aliyuncs.com/QQ%E5%9B%BE%E7%89%8720160911120948.png)

我们在父进程创建一个管道的时候，这个管道会创建一组，就是两个描述符，一个描述符用来读一个描述符用来写，当父进程创建了一个管道的时候，那么它相对应的子进程也拥有相同的两个描述符。

父进程通过对描述符当中写内容的时候子进程就可以通过读描述符来得到管道中的内容这样就实现了两个进程之间的通信，

- [x] 管道是一组（2个）特殊的描述符
- [x] 管道需要在fork函数调用前创建
- [x] 如果一端主动关闭管道，另一端的读写操作会直接返回0

### 进程之间的通信方式 -- 消息队列
![swoole](https://image20160910.oss-cn-beijing.aliyuncs.com/QQ%E5%9B%BE%E7%89%8720160911121836.png)
消息队列是独立于两个进程之外的这样一个方式，它跟之前说的共享内存挺像，它是独立于进程之外的一片特殊空间，

- [x] 指定一个key 值来创建一个消息队列
- [x] 在消息队列中传递的数据有大小限制 65535  (int) 的默值
- [x] 消息队列不像管道类似TCP传递而更像udp这样的流式传递，我发给你一个数据包，另一个进程去读，读的时候也是一个一个去读
- [x] 消息队列会一直保留直到被主动关闭

### 序章-IO多路复用
![swoole](https://image20160910.oss-cn-beijing.aliyuncs.com/QQ%E5%9B%BE%E7%89%8720160911122616.png)

如图所示，有5个fd(描述符)注册在这个epoll函数里，它就会不停的去监听这5个描述符，比如某一个描述符有来自客户端的数据了，某一个描述符可以准备开始往客户端写数据了，或者某一个描述符被关闭了，诸如此类事件发生了，epoll 函数才会效应，并返回有这些事件发生的`socket`集合，让客户端再一个一个去处理，所以你会发现它并不是异步的，epoll 它的优点是可以处理大量的`socket`连接，

- [x] epoll函数会监听注册在自己名下的描述符
- [x] 当有socket感兴趣的事件发生时，epoll函数才会效应，并返回有事件发生的socket集合
- [x] epoll的本质是阻塞IO，它的优点在于能同时处理大量的socket连接

### Event Loop
![swoole](https://image20160910.oss-cn-beijing.aliyuncs.com/QQ%E5%9B%BE%E7%89%8720160911123803.png)
实际上swoole 提供的`epoll`上层的封装，并且提供了一个线程，当使用swoole evente一些列函数去发起创建一个事件循环的时候，swoole会在底层启动一个`reactor`线程
，这个线程中会运行一个`epoll`实例并且它会去我们需要去注册描述符到这个epoll实例中并为它建立`read `与`write`的监听

- [x] Event Loop 实际上就是一个Reactor线程，其中运行了一个epoll 实例
- [x] 可通过接口添加socket 描述符到epoll监听中，并指定事件响应的回调函数
- [x] 因为它是新起的线程去运行的，Event Loop 不可用于FPM 环境中

Event Loop实例

命令行聊天室

主要应用点：

- 异步读取来自服务器的数据
- 异步读取来自终端的输入
- 手动退出聊天室
增加

```php

bool swoole_event_add(int $sock, mixed $read_callback, mixed $write_callback = null, int $event_flag = null);

```
修改，比如之前增加了一个描述符在里面并为它绑定了一个回调，那么后面我想修改它比如我这个时候不想让它继续监听写事件了或者想把它的监听关掉，那么都可以通过这个函数重新设定它，重新设定的时候注意一下如果我们穿进去的$fd之前是没有add的话会报错

```php
bool swoole_event_set($fd, mixed $read_callback, mixed $write_callback, int $flag);
```
当我们某个描述符不需要的时候可以通过`del`方法将它删除

```php
bool swoole_event_del(int $sock);
```
当我们整个事件都不想要的话我们可以通过exit退出整个事件轮询，把epoll这个实例关掉，这个只能在client 中调用

```php
bool swoole_event_del(int $sock);
```
读事件是在我们加入的读回调中执行的，当我们需要异步的将某个socket中写的时候swoole 也提供了一个event_write函数,这个write就会把这个消息的发送变成异步的，当我们发送缓冲区满了的之后swoole就会将数据发送到发送队列里来监听它可写，底层会自动执行写的事件，我们不需要再代码中再去关注缓存的问题

实例-命令行聊天室

```php
<?php
/**
 * 一个简单的命令行聊天室
 * User: pushaowei
 * Date: 2016/9/11 0011
 * Time: 12:53
 */
class server{
    private $serv;
    public function __construct(){
        header("content-type:text/html;charset=utf8");
        $this->serv = new swoole_server("0.0.0.0",9501);
        $this->serv ->set (['worker_num' => 1]);
        $this->serv ->on('Start',[$this,'onStart']);
        $this->serv ->on('Connect',[$this,'onConnect']);
        $this->serv ->on('Receive',[$this,'onReceive']);
        $this->serv ->on('Close',[$this,'onClose']);
        $this->serv ->start();
    }

    /**
     * start
     * @param $serv
     */
    public function onStart($serv){
        echo "咱们连接已经建立成功啦\n";
    }

    /**
     * 建立连接
     * @param $serv
     * @param $fd
     * @param $form_id
     */
    public function onConnect($serv,$fd,$form_id){
        echo "Client {$fd} connect\n";
    }

    /**
     * 服务端关闭提示
     * @param $serv
     * @param $fd
     * @param $form_id
     */
    public function onClose($serv,$fd,$form_id){
        echo "Client {$fd} close connection \n";
    }

    /**
     * 当我们收到客户端的消息时简单的广播出去
     * @param swoole_server $serv
     * @param $fd
     * @param $form_id
     * @param $data
     */
    public function onReceive(swoole_server $serv,$fd,$form_id,$data){
        echo "Get Message From Client {$fd} : {$data}\n";
        foreach($serv->connections as $v){
            if($fd != $v){
                $serv->send($v,$data);
            }
        }
    }
}

$server = new Server();

```
作者还没学习客户端怎么玩所以依然是使用瑞士军刀`nc`工具代替

```php

[pushaowei@localhost www]$ nc 127.0.0.1 9501

```
然后作者觉得 ，老是用工具就不好玩了，于是又写了一份客户端的连接供大家玩

```php

<?php
/**
 * 比较简陋的客户端.
 * User: pushaowei
 * Date: 2016/9/11 0011
 * Time: 13:39
 */
//通过stream方法生成了一个具体的描述符，通过tcp方式连接了服务器
$socket = stream_socket_client("tcp://127.0.0.1:9501",$errno,$errstr,30);
/*
STDIN    标准的输入设备
STDOUT    标准的输出设备
STDERR    标准的错误设备
可以在PHP脚本里使用这三个常量，以接受用户的输入，或者显示处理和计算的结果。
现在就有小明和二狗两个人在这个聊天室里聊天
*/
/**
 * 读监听，当客户端小明发送到服务器的数据后这里会被读到，然后转发给二狗
 */
function onRead(){
    global $socket;
    $buffer = stream_socket_recvfrom($socket,1024);
    if(!$buffer){
        echo "Server closed\n";
        swoole_event_del($socket);
    }
    echo "\n刚刚有人说:{$buffer}\n";
    fwrite(STDERR,"Enter Msg:");
}
/**
 * 发送数据
 */
function onWrite(){
    global $socket;
    echo "on Write\n";
}
/**
 * 发送操作
 */
function onInput(){
    global $socket;
    $msg = trim(fgets(STDIN));
    //如果键入 exit 的话就选择退出
    if($msg == 'exit'){
        swoole_event_exit();
        exit();
    }
    swoole_event_write($socket,$msg);
    fwrite(STDOUT,"Enter Msg:");
}
//给$socket描述符设置了两个方法一个读一个写
swoole_event_add($socket,'onRead','onWrite');

//监听了标准输入，设置了input的函数，当它监听到来自键盘的输入后它来获取输入了啥内容，然后发送给客户端
swoole_event_add(STDIN,'onInput');

//登录聊天室蹦出来的
fwrite(STDOUT,"Enter Msg:");

```

 - 异步读取来自服务器的数据
 - 异步读取来自终端的输入
 - 手动退出聊天室

**Event Loop 的常见问题**

Q:为什么开启Event loop 的程序会一直运行不停止
A:开始Event Loop 后程序会启动一个线程并一直阻塞在`epoll`的监听上，它是一个whlie的循环不断监听这个事件直到我们调用exit，因此不会退出，

Q:如何关闭 Event Loop ？
A:调用swoole_event_exit函数即可关闭事件循环(swoole_server中此函数无用，这个只能用在client中)这个rectaor 不能关闭

### Swoole_Process相关

这个process主要呢就是来替代PHP的pcntl扩展。

-  swoole_process提供了基于unixsock的进程间通信，使用很简单只需调用write/read或者push/pop即可
-  swoole_process支持重定向标准输入和输出，在子进程内echo不会打印屏幕，而是写入管道，读键盘输入可以重定向为管道读取数据
-  swoole_process允许用于fpm/apache的Web请求中
配合swoole_event模块，创建的PHP子进程可以异步的事件驱动模式
-  swoole_process提供了exec接口，创建的进程可以执行其他程序，与原PHP父进程之间可以方便的通信

一个swoole_process对象除了它本身是一个进程之外，它还有三个比较重要的内容
![swoole](http://image20160910.oss-cn-beijing.aliyuncs.com/QQ%E5%9B%BE%E7%89%8720160911123803.png)

所有的swoole_process通过参数指定它都会创建一个管道，子进程到父进程的通信管道，通过管道我们就可以实现进程之间的通信，每个swoole_process的进程空间是独立的

- 基于C语言封装的进程管理模块， 方便php的多进程通信
- 内置管道和消息队列的通信接口，可通过参数或API开启或关闭，很容易就进行进程间的通信
- 提供自定义的信号管理

创建子进程

```php

int swoole_process::__construct(mixed $function, $redirect_stdin_stdout = false, $create_pipe = true);

//$function，子进程创建成功后要执行的函数,就是函数创建之后将要做什么

//$redirect_stdin_stdout，重定向子进程的标准输入和输出。 启用此选项后，在进程内echo将不是打印屏幕，而是写入到管道。读取键盘输入将变为从管道中读取数据。 默认为阻塞读取。

//$create_pipe，是否创建管道，启用$redirect_stdin_stdout后，此选项将忽略用户参数，强制为true 如果子进程内没有进程间通信，可以设置为false

```

启动进程

```php

int swoole_process->start();

//创建成功返回子进程的PID，创建失败返回false。可使用swoole_errno和swoole_strerror得到错误码和错误信息。
$process->pid 属性为子进程的PID
$process->pipe 属性为管道的文件描述符

```

来个实例玩玩

```php

<?php
/**
 * swoole_process.
 * User: pushaowei
 * Date: 2016/9/11 0011
 * Time: 14:59
 */
class BaseProcess{
    private $process;
    /**
     * BaseProcess constructor.
     */
    public function __construct(){
        $this->process = new swoole_process([$this,'run'],false,true);
        //$this -> proccess -> daemon(true,true);
        $this->process ->start();

        swoole_event_add($this->process->pipe,function($pipe){
            $data = $this->process->read();
            echo"RECV ".$data.PHP_EOL;
        });
    }
    /**
     * @param $worker
     */
    public function run($worker){
        swoole_timer_tick(1000, function ($timer_id) {
            static $num = 0;
            $num += + 1;
            $this->process->write("Hello");
            var_dump($num);
            if ($num == 10) {
                //输出十次就退出
                swoole_timer_clear($timer_id);
            }
        });
    }
}
new BaseProcess();
//监听到进程退出了
swoole_process::signal(SIGCHLD,function($sig){
    //必须为false
    while($ret = swoole_process::wait(false)){
    echo "PID = {$ret['pid']}\n";
    }
});

```
> proccess 实例消息队列式


```php

<?php
/**
 * swoole_process. 消息队列式
 * User: pushaowei
 * Date: 2016/9/11 0011
 * Time: 14:59
 */
class BaseProcess{
    private $process;
    /**
     * BaseProcess constructor.
     */
    public function __construct(){
        $this->process = new swoole_process([$this,'run'],false,true);
        //创建一个消息队列并制定key值为123
        if(!$this->process->useQueue(123)){
            var_dump(swoole_strerror(swoole_error()));
            exit;
        }
        $this -> process->start();
        while(true){
            $data = $this ->process->pop();
            echo "RECV :".$data.PHP_EOL;
        }
    }
    /**
     * @param $worker
     */
    public function run($worker){
        swoole_timer_tick(1000, function ($timer_id) {
            static $num = 0;
            $num += + 1;
            $this->process->write("Hello");
            var_dump($num);
            if ($num == 10) {
                //输出十次就退出
                swoole_timer_clear($timer_id);
            }
        });
    }
}
new BaseProcess();
//监听到进程退出了
swoole_process::signal(SIGCHLD,function($sig){
    //必须为false
    while($ret = swoole_process::wait(false)){
    echo "PID = {$ret['pid']}\n";
    }
});

```

#### 动态进程池

- 使用tick 函数定时投递任务
- 动态进程池，根据任务执行的多条动态调整内存池的大小

使用特性

-  tick定时任务
-  swoole_process 管道通信
-  Event loop 事件循环

> 看下源码应该就直观一点了


```php

<?php
/**
 * swoole_process. 消息队列式
 * User: pushaowei
 * Date: 2016/9/11 0011
 * Time: 14:59
 */
class BaseProcess{
    private $process;
    private $process_list = []; //对应的子进程的数组
    private $process_use = []; //标记进程是否使用当中
    private $min_worker_num = 3; //最小的worker
    private $max_worker_num = 6; //最大的worker
    private $current_num; //当前worker 标记
    /**
     * BaseProcess constructor.
     */
    public function __construct(){
        $this->process = new swoole_process([$this,'run'],false,2);//启动的一个父进程
        $this->process -> start();
        swoole_process::wait();
    }
    /**
     * @param $worker
     * 任务进程池
     */
    public function run($worker){
        //这里可以执行sql
        $this->current_num = $this ->min_worker_num;
        for($i=0 ;$i< $this->current_num;$i++){
            $process = new swoole_process([$this,'task_num'],false,2);
            $pid = $process->start();
            $this->process_list[$pid] = $process;
            $this->process_use[$pid] = 0;
        }
        foreach($this->process_list as $v){
            swoole_event_add($v->pipe,function($pipe) use ($v){
                $data = $v -> read();
                var_dump($data);
                $this->process_use[$data] = 0;
            });
        }
        /**
         * 每一秒钟去发一次任务
         */
        swoole_timer_tick(1000,function($timer_id){
            static $num = 0;
            $num += 1;
            $flag = true;
            //它去看看哪一个进程是没有被使用的
            foreach($this->process_use as $k => $v ){
                if($v == 0){
                    $flag =false;
                    $this->process_use[$k] = 1; //并且把它标记成1 在给它发个任务
                    $this->process_list[$k] ->write($num ."hello");
                    break;
                }
            }
            //如果所有的worker子进程都再忙着了，再当前进程池还没满的情况下启动一个新的进程池
            if($flag && $this->current_num < $this->max_worker_num){
                $process = new swoole_process([$this,'task_num'],false,2);
                $pid = $process ->start();
                $this->process_list[$pid] = $process ;
                $this->process_use[$pid] = 1;
                $this->process_list[$pid] ->write($num."Hello");
            }
            var_dump($num);//如果执行完十次任务后 关闭当前定时器关闭当前子进程
            if($num == 10){
                foreach($this->process_list as $v){
                    $v -> write('exit');
                }
                swoole_timer_clear($timer_id);
                $this->process->exit();
            }

        });
    }

    public function task_num($worker){
        //当读到父进程发送来的任务时
        swoole_event_add($worker->pipe,function($pipe) use ($worker){
            $data = $worker->read();
            //当某个子进程收到任务的时候，它会打印自己的进程号，和它所接到的任务的消息
            var_dump($worker->pid.":".$data);
            if($data =='exit'){
                $worker->exit();exit;
                sleep(5);
                $worker -> write("",$worker->pid);
            }
        });
    }
}
new BaseProcess();
//监听到进程退出了
swoole_process::signal(SIGCHLD,function($sig){
    //必须为false
    while($ret = swoole_process::wait(false)){
    echo "PID = {$ret['pid']}\n";
    }
});

```

`process` 连接池与 `task `连接池有什么优缺点

`task worker` 它的数目相对来说是固定的，

`process` 是不太稳定的，因为它是动态加子进程的，通过定时器发任务的，它的任务耗时比较长，动态扩展进程池，处理更多的任务;

管道是两个描述符。读和写，当父进程创建这个管道后，然后在创建两个子进程，父进程中比如有两个管道，管1，管2，那么子进程也拥有两个管道，一个读一个写，读的那个只能用来读，写的那个只能用来写




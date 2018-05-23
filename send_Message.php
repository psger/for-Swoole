<?php

$serv = new swoole_server("0.0.0.0", 9501);
$serv->set(array(
    'worker_num' => 2,
    'task_worker_num' => 2,
));
//使用sendMessage必须注册onPipeMessage事件回调函数
$serv->on('pipeMessage', function($serv, $src_worker_id, $data) {
    echo "#{$serv->worker_id} message from #$src_worker_id: $data\n";
});
$serv->on('task', function ($serv, $task_id, $from_id, $data){
    var_dump($task_id, $from_id, $data);
});
$serv->on('finish', function ($serv, $fd, $from_id){

});
$serv->on('receive', function (swoole_server $serv, $fd, $from_id, $data) {
    if (trim($data) == 'task')
    {
        $serv->task("async task coming");
    }
    else
    {
        $worker_id = 1 - $serv->worker_id;
        $serv->sendMessage("hello task process", $worker_id);
    }
});

$serv->start();

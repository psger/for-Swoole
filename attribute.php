<?php
$serv = new swoole_server('127.0.0.1',9501);
$serv->set(array(
  'worker_num'=>4,
  'max_request'=>3,
  'dispatch_mode'=>3,

));

echo $serv->setting['worker_num'];
print_r($serv->setting);

$serv->on('receive', function ($serv, $fd, $from_id, $data) {
    $serv->send($fd, "Server: ".$data);
});
//启动服务器
$serv->start();

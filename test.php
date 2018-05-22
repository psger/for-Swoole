<?php
$server = new \swoole_server("127.0.0.1",8088,SWOOLE_PROCESS,SWOOLE_SOCK_TCP);

$server->on('connect',function($serv,$fd){
  echo "Client:connected.\n";
});

$server->on('receive',function($serv,$fd,$from_id,$data){
    switch ($data) {
      case 1:
        foreach ($serv->connections as $temFD) {
          $serv->send($temFD,"client{$fd} say : 1 for apple\n");
        }
        break;
      case 2:
      {
        $serv->send($fd,"2 for boy\n");
        break;
      }
      default:
      {
        $serv->send($fd,"Others is default");
      }
    }
});

$server->on('close',function($serv,$fd){
  echo "Client:Close.\n";
});

$server->start();

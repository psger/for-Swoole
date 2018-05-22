<?php 
$serv = new swoole_server("127.0.0.1",9502,SWOOLE_PROCESS,SWOOLE_SOCK_UDP);

//监听数据接收
$serv->on('Packet',function($serv,$data,$clientInfo){
	$serv->sendto($clientInfo['address'],$clientInfo['port'],"Server".$data);
	var_dump($clientInfo);
});

//启动服务器
$serv->start();

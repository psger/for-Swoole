<?php

$ws = new swoole_websocket_server("0.0.0.0",9502);

//监听websocket连接打开事件
$ws->on('open',function($ws,$request){
	var_dump($request->fd);
	echo "~~~~~";
	var_dump($request->get);
	echo "~~~~~";
	var_dump($request->server);
	$ws->push($request->fd,"hello,welcome\n");
});

//监听websocket消息事件
$ws->on('message',function($ws,$fram){
	echo "Message: {$frame->data}\n";
	$ws->push($frame->fd,"server:{$frame->data}");
});

//监听websocket连接关闭事件
$ws->on('close',function($ws,$fd){
	echo "client-{$fd} is closed\n";
});


$ws->start();

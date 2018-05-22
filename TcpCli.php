<?php
$client = new swoole_client(SWOOLE_SOCK_TCP);

//连接到服务器
if(!$client->connect('127.0.0.1',9501,0.5)){
  die("connect failed");
}

//向服务器发送数据
if(!$client->send("hello swoole")){
  die("send failed");
}

//从服务器接受数据
$data = $client->recv();
if(!$data){
  die("recv failed");
}

echo "$data";

$client->close();

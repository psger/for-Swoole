<?php
//创建闭包
$closure = function ($name){
  return sprintf('hello %s',$name);
};

echo $closure('Josh');

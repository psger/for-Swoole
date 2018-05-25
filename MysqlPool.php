<?php

/**
 * achieve MYSQLPoll
 */
class MYSQLPoll
{
  private $serv;
  private $pdo;

  function __construct()
  {
    $this->serv = new swoole_server("0.0.0.0",9501);//
    $this->serv->set(array(
      'worker_num'      => 8,
      'daemonize'       => false,
      'max_request'     => 10000,
      'dispatch_mode'   => 3,
      'debug_mode'      => 1,
      'task_worker_num' => 8
    ));


    //事件绑定
    $this->serv->on('WorkerStart', array($this, 'onWorkerStart'));
    $this->serv->on('Connect', array($this, 'onConnect'));
    $this->serv->on('Receive', array($this, 'onReceive'));
    $this->serv->on('Close', array($this, 'onClose'));
    $this->serv->on('Task', array($this, 'onTask'));
    $this->serv->on('Finish', array($this, 'onFinish'));
    $this->serv->start();
  }

  public function onWorkerStart( $serv , $worker_id) {
        echo "onWorkerStart\n";
        // 判定是否为Task Worker进程
        if( $worker_id >= $serv->setting['worker_num'] ) {
        	$this->pdo = new PDO(
        		"mysql:host=localhost;port=3306;dbname=test",
        		"root",
        		"passenger",
        		array(
	                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8';",
	                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
	                PDO::ATTR_PERSISTENT => true
            	)
            );
        }
    }

  public function onConnect( $serv, $fd, $from_id){
    echo "Client {$fd} connect\n";
  }

  public function onReceive( $serv, $fd, $from_id, $data){
    $sql = array(
      'sql' => 'Insert into test values( pid = ?, name = ?)',
      'param' => array(
        0,
        "'name'"
      ),
      'fd' => $fd
    );
    $serv->task( json_encode($sql));//投递给异步任务
  }

  public function onClose( $serv, $fd){
    echo "Client {$fd} close connect\n";
  }

  public function onTask( $serv, $task_id, $from_id, $data){//接受任务并且执行
    try{
      $sql = json_decode($data,true);

      $statement = $this->pdo->prepare($sql['sql']);
      $statement->execute($sql['param']);

      $serv->send( $sql['fd'],"Insert");
      return true;
    } catch( PDOExecute $e){
      var_dump($e);
      return false;
    }
  }


  public function onFinish( $serv, $task_id, $data){

  }

}

new MYSQLPoll();

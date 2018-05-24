<?php

register_shutdown_function('handleFatal');

function handleFatal()
{
  $error = error_get_last();

  // Array
  // (
  //     [type] => 8
  //     [message] => Undefined variable: hello
  //     [file] => /home/passenger/swoole/shutdown_function.php
  //     [line] => 3
  // )

  if(isset($error['type']))
  {
    switch ($error['type'])
    {
      case E_ERROR :
      case E_PARSE :
      case E_CORE_ERROR :
      case E_COMPILE_ERROR :
          $message = $error['message'];
          $file    = $error['file'];
          $line    = $error['line'];
          $log     = "$message($file:$line)\nStack trace:\n";
          $trace   = debug_backtrace();
          foreach ($trace as $i => $t)
            {
                if (!isset($t['file']))
                {
                    $t['file'] = 'unknown';
                }
                if (!isset($t['line']))
                {
                    $t['line'] = 0;
                }
                if (!isset($t['function']))
                {
                    $t['function'] = 'unknown';
                }
                $log .= "#$i {$t['file']}({$t['line']}): ";
                if (isset($t['object']) and is_object($t['object']))
                {
                    $log .= get_class($t['object']) . '->';
                }
                $log .= "{$t['function']}()\n";
            }
            if (isset($_SERVER['REQUEST_URI']))
              {
                  $log .= '[QUERY] ' . $_SERVER['REQUEST_URI'];
              }
              error_log($log);
              $serv->send($this->currentFd,$log);
            default:
              break;
    }
  }
}

<?php

namespace Test\Helpers;

trait TcpServerStub {

  /**
   * @var resource
   */
  static protected $server;

  /**
   * @var type
   */
  static protected $client;

  public function serverStart($port) {
    if (static::$server) {
      return;
    }

    static::$server = stream_socket_server('tcp://127.0.0.1:' . (int) $port, $errno, $errstr);
    stream_set_blocking(static::$server, false);

    declare(ticks=1);
    register_tick_function(function () { static::serverAccept(); });
  }

  static public function serverAccept() {
    $read = [static::$server];
    if (!stream_select($read, $write, $except, 0, 2000)) {
      return;
    }
    if (in_array(static::$server, $read)) {
      static::$client = stream_socket_accept(static::$server);
    }
  }

  static public function serverWrite($content) {
    stream_socket_sendto(static::$client, $content);
  }

  static public function serverStop() {
    if (static::$server) {
      stream_socket_shutdown(static::$server, STREAM_SHUT_WR);
    }
  }

}

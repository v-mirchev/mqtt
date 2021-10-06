<?php

namespace Test\Helpers;

trait TcpServerStub {

  /**
   * @var resource
   */
  protected $server;

  /**
   * @var type
   */
  protected $client;

  public function serverStart($port) {
    if ($this->server) {
      return;
    }

    $this->server = stream_socket_server('tcp://127.0.0.1:' . (int) $port, $errno, $errstr);
    stream_set_blocking($this->server, false);

    declare(ticks=1);
    register_tick_function([$this, 'serverAccept']);
  }

  public function serverAccept() {
    $read = [$this->server];
    if (!stream_select($read, $write, $except, 0, 2000)) {
      return;
    }
    if (in_array($this->server, $read)) {
      $this->client = stream_socket_accept($this->server);
    }
  }

  public function serverWrite($content) {
    stream_socket_sendto($this->client, $content);
  }

  public function serverStop() {
    if ($this->server) {
      stream_socket_shutdown($this->server, STREAM_SHUT_WR);
    }
  }

}

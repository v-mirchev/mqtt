<?php declare(strict_types = 1); declare(ticks = 1);

namespace Mqtt\Connection;

class Socket {

  /**
   * @var \Mqtt\Entity\Configuration\Server
   */
  protected $server;

  /**
   * @var resource
   */
  protected $socket;

  /**
   * @var string
   */
  protected $char;

  /**
   * @var int
   */
  protected $timeout;

  /**
   * @var string
   */
  protected $url;

  /**
   * @var reource
   */
  protected $socketContext;

  /**
   * @param \Mqtt\Entity\Configuration\Server $server
   */
  public function __construct(
    \Mqtt\Entity\Configuration\Server $server
  ) {
    $this->server = $server;

    $this->socket = null;
    $this->socketContext = null;
    $this->url = '';
  }

  /**
   * @param string $host
   * @param int $port
   * @param string $certificateFile
   */
  public function connect() : void {
    if ($this->server->certificateFile) {
      $this->socketContext = stream_context_create([ 'ssl' => [
        'verify_peer_name' => true,
        'cafile' => $this->server->certificateFile,
      ]]);
      $this->url = 'tls://' . $this->server->host . ':' . $this->server->port;
    } else {
      $this->socketContext = null;
      $this->url = 'tcp://' . $this->server->host . ':' . $this->server->port;
    }

    $this->reconnect();
  }

  public function reconnect() : void {
    if ($this->isConnected() || $this->isReseted()) {
      $this->disconnect();
    }

    $this->socket = $this->socketContext ?
      @stream_socket_client($this->url, $errno, $errstr, $this->server->connectTimeout, STREAM_CLIENT_CONNECT, $this->socketContext) :
      @stream_socket_client($this->url, $errno, $errstr, $this->server->connectTimeout, STREAM_CLIENT_CONNECT);

    if (!$this->socket) {
      throw new \Exception('Stream socket create: (' . $errno . ') ' . $errstr);
    }

    stream_set_timeout($this->socket, $this->server->timeout);
    stream_set_blocking($this->socket, false);
    stream_set_read_buffer($this->socket, 0);
    stream_set_write_buffer($this->socket, 0);
  }

  public function disconnect() : void {
    if ($this->isConnected()) {
      stream_socket_shutdown($this->socket, STREAM_SHUT_WR);
      $this->socket = null;
    }
  }

  /**
   * @return bool
   */
  public function isAlive() : bool {
    return $this->isConnected() && !$this->isReseted();
  }

  /**
   * @return bool
   */
  public function isConnected() : bool {
    return !empty($this->socket);
  }

  /**
   * @return bool
   */
  public function isReseted() : bool {
    return !is_null($this->socket) && feof($this->socket);
  }

  /**
   * @return string
   */
  public function getChar() {
    return $this->char;
  }

  public function read() : void {
    $this->char = null;

    $readStreams = [$this->socket];
    $streamChanged = @stream_select($readStreams, $writeStreams, $exceptionalStreams, 0, $this->server->selectTimeout);
    if ($streamChanged) {
      $char = stream_socket_recvfrom($this->socket, 1);
      if ($char === false) {
        throw new \Exception('Could not read from socket');
      } elseif (strlen($char) > 0) {
        $this->char = $char;
      }
    }

    if (!$this->isAlive()) {
      throw new \Exception('Socket down');
    }
  }

  /**
   * @param string $content
   * @param int $bytesCount
   */
  public function write(string $content) : void {
    if (!$this->isAlive()) {
      throw new \Exception('Socket down');
    }

    $contentLength = strlen($content);
    for ($totalBytesWritten = 0; $totalBytesWritten < $contentLength; $totalBytesWritten += $bytesWritten) {
      $bytesWritten = @stream_socket_sendto($this->socket, substr($content, $totalBytesWritten));
      if ($bytesWritten === false) {
        throw new \Exception('Could not write all bytes to socket');
      }
    }
  }

}

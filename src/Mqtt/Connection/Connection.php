<?php declare(ticks = 1);

namespace Mqtt\Connection;

class Connection implements \Mqtt\Connection\IConnection {

  /**
   * @var \Mqtt\Connection\Socket
   */
  protected $socket;

  /**
   * @var \Mqtt\Protocol\Binary\Frame
   */
  protected $frame;

  /**
   * @var \Mqtt\Connection\IHandler
   */
  protected $protocol;

  /**
   * @var bool
   */
  protected $isDisconnecting;

  /**
   * @param \Mqtt\Connection\Socket $socket
   * @param \Mqtt\Protocol\Binary\Frame $frame
   */
  public function __construct(
    \Mqtt\Connection\Socket $socket,
    \Mqtt\Protocol\Binary\Frame $frame
  ) {
    $this->socket = $socket;
    $this->frame = clone $frame;
  }

  /**
   * @param \Mqtt\Connection\IHandler $protocol
   * @return $this
   */
  public function setProtocol(\Mqtt\Connection\IHandler $protocol) : void {
    $this->protocol = $protocol;
  }

  /**
   * @return \Iterator
   */
  public function stream() : \Iterator {
    while ($this->socket->isAlive()) {
      $this->protocol->onTick();
      $this->socket->read();
      $byte = $this->socket->getByte();
      if (!is_null($byte)) {
        yield $byte;
      }
    }
  }

  /**
   * @return void
   * @throws \Exception
   */
  public function establish() : void {
    $this->isDisconnecting = false;
    $this->socket->connect();
    $this->protocol->onConnect();

    try {
      while ($this->socket->isAlive()) {
        $this->protocol->read($this->stream());
      }
    } catch (\Exception $socketException) {
      if (!$this->isDisconnecting) {
        throw $socketException;
      }
    }

    $this->protocol->onDisconnect();
    $this->socket->disconnect();
  }

  /**
   * @param string $bytes
   * @return void
   */
  public function write(string $bytes) : void {
    $this->socket->write($bytes);
  }

  /**
   * @return void
   */
  public function disconnect() : void {
    $this->isDisconnecting = true;
    $this->socket->disconnect();
  }

}

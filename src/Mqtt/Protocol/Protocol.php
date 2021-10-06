<?php declare(ticks = 1);

namespace Mqtt\Protocol;

class Protocol implements \Mqtt\Protocol\IProtocol, \Mqtt\Connection\IHandler {

  /**
   * @var \Mqtt\Connection\IConnection
   */
  protected $connection;

  /**
   * @var \Mqtt\Protocol\Binary\Frame
   */
  protected $frame;

  /**
   * @var \Mqtt\Protocol\Packet\Factory
   */
  protected $packetFactory;

  /**
   * @var \Mqtt\Protocol\IHandler
   */
  protected $session;

  /**
   * @param \Mqtt\Connection\IConnection $connection
   * @param \Mqtt\Protocol\Binary\Frame $frame
   * @param \Mqtt\Protocol\Packet\Factory $packetFactory
   */
  public function __construct(
    \Mqtt\Connection\IConnection $connection,
    \Mqtt\Protocol\Binary\Frame $frame,
    \Mqtt\Protocol\Packet\Factory $packetFactory
  ) {
    $this->connection = $connection;
    $this->connection->setProtocol($this);

    $this->frame = $frame;
    $this->packetFactory = $packetFactory;
  }

  /**
   * @param \Mqtt\Protocol\IHandler $session
   */
  public function setSession(\Mqtt\Protocol\IHandler $session) {
    $this->session = $session;
  }

  public function connect() : void {
    $this->connection->establish();
  }

  public function disconnect() : void {
    $this->connection->disconnect();
  }

  /**
   * @param \Iterator $stream
   */
  public function read(\Iterator $stream) : void {
    $frame = $this->getNewFrame();
    $frame->fromStream($stream);

    $receivedPacket = $this->packetFactory->create($frame->getPacketType());
    $receivedPacket->decode($frame);
    unset($frame);

    $this->session->onPacketReceived($receivedPacket);
  }

  /**
   * @param \Mqtt\Protocol\IPacket $packet
   */
  public function writePacket(\Mqtt\Protocol\IPacket $packet) : void {
    $frame = $this->getNewFrame();
    $packet->encode($frame);
    $this->connection->write((string)$frame);
    unset($frame);
  }

  public function onConnect() : void {
    $this->session->onConnect();
  }

  public function onDisconnect() : void {
    $this->session->onDisconnect();
  }

  /**
   * @param int $type
   * @return \Mqtt\Protocol\IPacket
   */
  public function createPacket(int $type) : \Mqtt\Protocol\IPacket {
    return $this->packetFactory->create($type);
  }

  public function onTick() : void {
    $this->session->onTick();
  }

  public function getNewFrame() : \Mqtt\Protocol\Binary\Frame {
    return clone $this->frame;
  }

}

<?php

namespace Mqtt\Protocol\Packet;

class ConnAck implements \Mqtt\Protocol\IPacket {

  use \Mqtt\Protocol\Packet\TPacket;

  const CODES = [
    0 => 'Connection Accepted',
    1 => 'Connection Refused, unacceptable protocol version',
    2 => 'Connection Refused, identifier rejected',
    3 => 'Connection Refused, server unavailable',
    4 => 'Connection Refused, bad user name or password',
    5 => 'Connection Refused, not authorized',
  ];

  /**
   * @var \Mqtt\Protocol\Packet\Flags\ConnAck
   */
  protected $flags;

  /**
   * @param \Mqtt\Protocol\Binary\Flags\ConnAck $flags
   */
  public function __construct(
    \Mqtt\Protocol\Binary\Flags\ConnAck $flags
  ) {
    $this->flags = $flags;
  }

  /**
   * @param \Mqtt\Protocol\Binary\Frame $frame
   */
  public function decode(\Mqtt\Protocol\Binary\Frame $frame) {
    $this->flags->set($frame->getWord());
  }

  /**
   * @return int
   */
  public function getReturnCode() : int {
    return $this->flags->getReturnCode();
  }

  /**
   * @return string
   */
  public function getReturnCodeMessage() : string {
    return static::CODES[$this->flags->getReturnCode()];
  }

  /**
   * @return bool
   */
  public function isSessionPresent() : bool {
    return $this->flags->getSessionPresent();
  }

  /**
   * @param int $packetId
   * @return bool
   */
  public function is(int $packetId): bool {
    return \Mqtt\Protocol\IPacket::CONNACK === $packetId;
  }

}

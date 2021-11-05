<?php

namespace Mqtt\Protocol\Decoder\Packet\ControlPacket\Flags;

class ConnAck {

  /**
   * @var \Mqtt\Protocol\Binary\Data\Uint8
   */
  protected $flags;

  const BIT_SESSION_PRESENT = 0;
  const BIT_RESERVED_START = 1;
  const BIT_RESERVED_END = 7;

  public function __construct(\Mqtt\Protocol\Binary\Data\Uint8 $flags) {
    $this->flags = clone $flags;
  }

  public function decode(\Mqtt\Protocol\Binary\Data\IBuffer $buffer) {
    $this->flags->decode($buffer);
    if (
      $this->flags->bits()->getSub(static::BIT_RESERVED_START, static::BIT_RESERVED_END)->get() !==
      \Mqtt\Protocol\IPacketReservedBits::PAYLOAD_CONNACK_FLAGS_BIT_1_TO_7
    ) {
      throw new \Mqtt\Exception\ProtocolViolation(
        'Wrong CONNACK reserved bits',
        \Mqtt\Exception\ProtocolViolation::INCORRECT_PAYLOAD_RESERVED_BITS
      );
    }
  }

  /**
   * @return bool
   */
  public function getSessionPresent(): bool {
    return $this->flags->bits()->getBit(static::BIT_SESSION_PRESENT);
  }

  public function __clone() {
    $this->flags = clone $this->flags;
  }

}


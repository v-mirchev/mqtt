<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Encoder\Frame;

class ControlHeader implements \Mqtt\Protocol\Encoder\Frame\IStreamEncoder {

  const BIT_START_FLAGS = 0;
  const BIT_END_FLAGS = 3;
  const BIT_START_PACKET_TYPE = 4;
  const BIT_END_PACKET_TYPE = 7;

  /**
   * @var \Mqtt\Protocol\Binary\Data\Uint8
   */
  protected $controlHeader;

  /**
   * @var int
   */
  protected $packetType;

  /**
   * @var int
   */
  protected $flags;

  /**
   * @param \Mqtt\Protocol\Binary\Data\Uint8 $uint8
   */
  public function __construct(\Mqtt\Protocol\Binary\Data\Uint8 $uint8) {
    $this->controlHeader = clone $uint8;
  }

  public function __clone() {
    $this->controlHeader = clone $this->controlHeader;
    $this->packetType = 0;
    $this->flags = 0;
  }

  /**
   * @param int $packetType
   * @return $this
   */
  public function setPacketType(int $packetType) {
    $this->packetType = $packetType;
    return $this;
  }

  /**
   * @param int $flags
   * @return $this
   */
  public function setFlags(int $flags) {
    $this->flags = $flags;
    return $this;
  }

  /**
   * @param \Mqtt\Protocol\Binary\IBuffer $buffer
   */
  public function encode(\Mqtt\Protocol\Binary\IBuffer $buffer) : void {
    $this->controlHeader->bits()->setSub(static::BIT_START_PACKET_TYPE, static::BIT_END_PACKET_TYPE, $this->packetType);
    $this->controlHeader->bits()->setSub(static::BIT_START_FLAGS, static::BIT_END_FLAGS, $this->flags);
    $this->controlHeader->encode($buffer);
  }

}

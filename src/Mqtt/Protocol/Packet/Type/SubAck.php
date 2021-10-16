<?php

namespace Mqtt\Protocol\Packet\Type;

class SubAck implements \Mqtt\Protocol\Packet\IType {

  const CODE_SUCCESS_QOS0 = 0x00;
  const CODE_SUCCESS_QOS1 = 0x01;
  const CODE_SUCCESS_QOS2 = 0x02;
  const CODE_FAILURE = 0x80;

  const CODE_MESSAGES = [
    0x00 => 'Success - Maximum QoS 0',
    0x01 => 'Success - Maximum QoS 1',
    0x02 => 'Success - Maximum QoS 2',
    0x80 => 'Failure',
  ];

  use \Mqtt\Protocol\Packet\Type\TType;

  /**
   * @var int
   */
  protected $id;

  /**
   * @var int[]
   */
  protected $returnCodes;

  /**
   * @param \Mqtt\Protocol\Binary\Frame $frame
   */
  public function decode(\Mqtt\Protocol\Binary\Frame $frame) {
    $this->id = $frame->getWord();
    $this->returnCodes = $frame->getPayloadBytes();
 }

  /**
   * @return int
   */
  public function getId(): int {
    return $this->id;
  }

  /**
   * @return int[]
   */
  public function getReturnCodes(): array {
    return $this->returnCodes;
  }

  /**
   * @param int $packetId
   * @return bool
   */
  public function is(int $packetId): bool {
    return \Mqtt\Protocol\Packet\IType::SUBACK === $packetId;
  }

}
<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Decoder\Packet\ControlPacket;

trait TValidators {

  /**
   * @param \Mqtt\Protocol\Entity\Frame $frame
   * @param int $expectedPacketType
   * @return void
   * @throws \Mqtt\Exception\ProtocolViolation
   */
  public function assertPacketIs(\Mqtt\Protocol\Entity\Frame $frame, int $expectedPacketType): void {
    if ($frame->packetType !== $expectedPacketType) {
      throw new \Mqtt\Exception\ProtocolViolation(
        'Packet type <' . $frame->packetType . '> does not match expected <' . $expectedPacketType . '>',
        \Mqtt\Exception\ProtocolViolation::INCORRECT_PACKET_TYPE
      );
    }
  }

  /**
   * @param \Mqtt\Protocol\Entity\Frame $frame
   * @param int $expectedPacketType
   * @return void
   * @throws \Mqtt\Exception\ProtocolViolation
   */
  public function assertPacketFlags(\Mqtt\Protocol\Entity\Frame $frame, int $expectedPacketType): void {
    if ($frame->flags->get() !== $expectedPacketType) {
      throw new \Mqtt\Exception\ProtocolViolation(
        'Packet flags <' . $frame->flags->get() . '> received do not match reserved ones for packet type <' . $frame->packetType . '>',
        \Mqtt\Exception\ProtocolViolation::INCORRECT_CONTROL_HEADER_RESERVED_BITS
      );
    }
  }

  /**
   * @param \Mqtt\Protocol\Entity\Frame $frame
   * @return void
   * @throws \Mqtt\Exception\ProtocolViolation
   */
  public function assertPayloadConsumed(\Mqtt\Protocol\Entity\Frame $frame): void {
    if (!$frame->payload->isEmpty()) {
      throw new \Mqtt\Exception\ProtocolViolation(
        'Unknown payload data for packet type <' . $frame->packetType . '>',
        \Mqtt\Exception\ProtocolViolation::UNKNOWN_PAYLOAD_DATA
      );
    }
  }

  /**
   * @param int $returnCode
   * @return void
   * @throws \Mqtt\Exception\ProtocolViolation
   */
  public function validateSubscribeReturnCode(int $returnCode): void {
    if (!in_array($returnCode, [
      \Mqtt\Protocol\ISubscribeReturnCode::SUCCESS_MAX_QOS_AT_MOST_ONCE,
      \Mqtt\Protocol\ISubscribeReturnCode::SUCCESS_MAX_QOS_AT_LEAST_ONCE,
      \Mqtt\Protocol\ISubscribeReturnCode::SUCCESS_MAX_QOS_EXACTLY_ONCE,
      \Mqtt\Protocol\ISubscribeReturnCode::FAILURE,
    ] )) {
      throw new \Mqtt\Exception\ProtocolViolation(
        'Return codes received do not match SUBACK allowed ones',
        \Mqtt\Exception\ProtocolViolation::INCORRECT_SUBACK_RETURN_CODE
      );
    }
  }

}

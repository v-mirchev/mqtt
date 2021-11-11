<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Encoder\Packet\ControlPacket;

trait TValidators {

  /**
   * @param \Mqtt\Protocol\Entity\Packet\IPacket $packet
   * @param int $expectedPacketType
   * @return void
   * @throws \Mqtt\Exception\ProtocolViolation
   */
  public function assertPacketIs(\Mqtt\Protocol\Entity\Packet\IPacket $packet, int $expectedPacketType): void {
    if (! $packet->isA($expectedPacketType)) {
      throw new \Mqtt\Exception\ProtocolViolation(
        'Packet type received <' . $packet->getType() . '> is not expected <' . $expectedPacketType . '>',
        \Mqtt\Exception\ProtocolViolation::INCORRECT_PACKET_TYPE
      );
    }
  }


}

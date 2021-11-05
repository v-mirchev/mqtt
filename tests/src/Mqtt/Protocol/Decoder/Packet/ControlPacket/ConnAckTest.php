<?php

namespace Mqtt\Protocol\Decoder\Packet\ControlPacket;

/**
 * @Inject $container
 * @property \Psr\Container\ContainerInterface $___container
 */
class ConnAckTest extends \PHPUnit\Framework\TestCase {

  /**
   * @var \Mqtt\Protocol\Decoder\Packet\ControlPacket\ConnAck
   */
  protected $object;

  /**
   * @var \Mqtt\Protocol\Entity\Frame
   */
  protected $frame;

  protected function setUp() {
    $this->object = clone $this->___container->get(\Mqtt\Protocol\Decoder\Packet\ControlPacket\ConnAck::class);

    $this->frame = clone $this->___container->get(\Mqtt\Protocol\Entity\Frame::class);
    $this->frame->packetType = \Mqtt\Protocol\Packet\IType::CONNACK;
    $this->frame->flags = clone $this->___container->get(\Mqtt\Protocol\Binary\Data\Uint8::class);
    $this->frame->flags->set(\Mqtt\Protocol\IPacketReservedBits::FLAGS_CONNACK);
    $this->frame->payload = clone $this->___container->get(\Mqtt\Protocol\Binary\Data\IBuffer::class);
    $this->frame->payload->append(chr(0x0) . chr(0x0));
  }

  public function testInitialCleanState() {
    $object = clone $this->object;
    $this->assertEquals(\Mqtt\Protocol\Packet\IType::CONNACK, $object->get()->getType());
  }

  public function testDecodeFailsWhenIncorrectPacketTypeSetInFrame() {
    $this->frame->packetType = \Mqtt\Protocol\Packet\IType::CONNECT;
    $this->expectException(\Mqtt\Exception\ProtocolViolation::class);
    $this->expectExceptionCode(\Mqtt\Exception\ProtocolViolation::INCORRECT_PACKET_TYPE);
    $this->object->decode($this->frame);
  }

  public function testDecodeFailsWhenIncorrectValuesForReservedPacketFlags() {
    $this->frame->flags->set(0xFF);
    $this->expectException(\Mqtt\Exception\ProtocolViolation::class);
    $this->expectExceptionCode(\Mqtt\Exception\ProtocolViolation::INCORRECT_CONTROL_HEADER_RESERVED_BITS);
    $this->object->decode($this->frame);
  }

  public function testDecodeFailsWhenPaylodNotFullyDecoded() {
    $this->frame->payload->append(chr(0x0) . chr(0x0) . chr(0x5));
    $this->expectException(\Mqtt\Exception\ProtocolViolation::class);
    $this->expectExceptionCode(\Mqtt\Exception\ProtocolViolation::UNKNOWN_PAYLOAD_DATA);
    $this->object->decode($this->frame);
  }

  public function testDecodeFailsWhenIncorrectReturnCodeValue() {
    $this->frame->payload = clone $this->___container->get(\Mqtt\Protocol\Binary\Data\IBuffer::class);
    $this->frame->payload->append(chr(0x0) . chr(0xF));
    $this->expectException(\Exception::class);
    $this->expectExceptionCode(\Mqtt\Exception\ProtocolViolation::INCORRECT_CONNACK_RETURN_CODE);
    $this->object->decode($this->frame);
  }

  public function testDecodeFailsWhenIncorrectPayloadReservedBits() {
    $this->frame->payload = clone $this->___container->get(\Mqtt\Protocol\Binary\Data\IBuffer::class);
    $this->frame->payload->append(chr(0xF) . chr(0x1));
    $this->expectException(\Exception::class);
    $this->expectExceptionCode(\Mqtt\Exception\ProtocolViolation::INCORRECT_PAYLOAD_RESERVED_BITS);
    $this->object->decode($this->frame);
  }

  public function testProperlyDecodingSessionPresentFlag() {
    $this->frame->payload = clone $this->___container->get(\Mqtt\Protocol\Binary\Data\IBuffer::class);
    $this->frame->payload->append(chr(0x0) . chr(0x0));
    $this->object->decode($this->frame);
    $this->assertFalse($this->object->get()->isSessionPresent);

    $this->frame->payload = clone $this->___container->get(\Mqtt\Protocol\Binary\Data\IBuffer::class);
    $this->frame->payload->append(chr(0x1) . chr(0x0));
    $this->object->decode($this->frame);
    $this->assertTrue($this->object->get()->isSessionPresent);
  }

  public function returnCodeDataProvider() {
    return [
      [0, 0x0 ],
      [1, 0x1 ],
      [2, 0x2 ],
      [3, 0x3 ],
      [4, 0x4 ],
      [5, 0x5 ],
    ];
  }

  /**
   * @dataProvider returnCodeDataProvider
   */
  public function testProperlyDecodingConnectReturnCode(int $returnCode, int $encodedByte) {
    $this->frame->payload = clone $this->___container->get(\Mqtt\Protocol\Binary\Data\IBuffer::class);
    $this->frame->payload->append(chr(0x0) . chr($encodedByte));
    $this->object->decode($this->frame);
    $this->assertEquals($returnCode, $this->object->get()->code);
  }

  public function testProperlyDecodingReturnsConnAckPacketEntity() {
    $this->object->decode($this->frame);
    $this->assertInstanceOf(\Mqtt\Protocol\Entity\Packet\ConnAck::class, $this->object->get());
  }

}

<?php

namespace Mqtt\Protocol\Decoder\Packet;

/**
 * @Inject $container
 * @property \Psr\Container\ContainerInterface $___container
 */
class PingRespTest extends \PHPUnit\Framework\TestCase {

  /**
   * @var \Mqtt\Protocol\Decoder\Packet\PingResp
   */
  protected $object;

  /**
   * @var \Mqtt\Protocol\Entity\Frame
   */
  protected $frame;

  protected function setUp() {
    $this->object = clone $this->___container->get(\Mqtt\Protocol\Decoder\Packet\PingResp::class);

    $this->frame = clone $this->___container->get(\Mqtt\Protocol\Entity\Frame::class);
    $this->frame->packetType = \Mqtt\Protocol\Packet\IType::PINGRESP;
    $this->frame->flags = clone $this->___container->get(\Mqtt\Protocol\Binary\Data\Uint8::class);
    $this->frame->flags->set(\Mqtt\Protocol\IPacketReservedBits::FLAGS_PINGRESP);
    $this->frame->payload = clone $this->___container->get(\Mqtt\Protocol\Binary\Data\IBuffer::class);
  }

  public function testInitialCleanState() {
    $object = clone $this->object;
    $this->assertEquals(\Mqtt\Protocol\Packet\IType::PINGRESP, $object->get()->getType());
  }

  public function testDecodeFailsWhenWrongPacketTypeSetInFrame() {
    $this->frame->packetType = \Mqtt\Protocol\Packet\IType::CONNECT;
    $this->expectException(\Exception::class);
    $this->object->decode($this->frame);
  }

  public function testDecodeFailsWhenWrongValuesForReservedPacketFlags() {
    $this->frame->flags->set(0xFF);
    $this->expectException(\Exception::class);
    $this->object->decode($this->frame);
  }

  public function testDecodeFailsWhenPaylodNotFullyDecoded() {
    $this->frame->payload->append(chr(0x0) . chr(0x0) . chr(0x5));
    $this->expectException(\Mqtt\Exception\ProtocolViolation::class);
    $this->expectExceptionCode(\Mqtt\Exception\ProtocolViolation::UNKNOWN_PAYLOAD_DATA);
    $this->object->decode($this->frame);
  }

  public function testProperlyDecodingReturnsPingRespPacketEntity() {
    $this->object->decode($this->frame);
    $this->assertInstanceOf(\Mqtt\Protocol\Entity\Packet\PingResp::class, $this->object->get());
  }

}

<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Encoder\Packet\ControlPacket;

/**
 * @Inject $container
 * @property \Psr\Container\ContainerInterface $___container
 */
class PingReqTest extends \PHPUnit\Framework\TestCase {

  /**
   * @var \Mqtt\Protocol\Encoder\Packet\ControlPacket\PingReq
   */
  protected $object;

  /**
   * @var \Mqtt\Protocol\Entity\Packet\IPacket
   */
  protected $packet;

  protected function setUp() {
    $this->object = clone $this->___container->get(\Mqtt\Protocol\Encoder\Packet\ControlPacket\PingReq::class);
    $this->packet = clone $this->___container->get(\Mqtt\Protocol\Entity\Packet\PingReq::class);
  }

  public function testEncodingFailsWhenIncorrectPacketType() {
    $this->packet = clone $this->___container->get(\Mqtt\Protocol\Entity\Packet\PingResp::class);
    $this->expectException(\Mqtt\Exception\ProtocolViolation::class);
    $this->expectExceptionCode(\Mqtt\Exception\ProtocolViolation::INCORRECT_PACKET_TYPE);
    $this->object->encode($this->packet);
  }

  public function testEncodesFrameProperly() {
    $this->object->encode($this->packet);
    $this->assertEquals(\Mqtt\Protocol\IPacketType::PINGREQ, $this->object->get()->packetType);
    $this->assertEquals(\Mqtt\Protocol\IPacketReservedBits::FLAGS_PINGREQ, $this->object->get()->flags->get());
    $this->assertEquals('', (string) $this->object->get()->payload);
  }

}

<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Encoder\Packet\ControlPacket;

/**
 * @Inject $container
 * @property \Psr\Container\ContainerInterface $___container
 */
class SubscribeTest extends \PHPUnit\Framework\TestCase {

  use \Test\Helpers\Binary;

  /**
   * @var \Mqtt\Protocol\Encoder\Packet\ControlPacket\Subscribe
   */
  protected $object;

  /**
   * @var \Mqtt\Protocol\Entity\Packet\Subscribe
   */
  protected $packet;

  protected function setUp() {
    $this->object = clone $this->___container->get(\Mqtt\Protocol\Encoder\Packet\ControlPacket\Subscribe::class);
    $this->packet = clone $this->___container->get(\Mqtt\Protocol\Entity\Packet\Subscribe::class);
  }

  public function testEncodingFailsWhenIncorrectPacketType() {
    $this->packet = clone $this->___container->get(\Mqtt\Protocol\Entity\Packet\Connect::class);
    $this->expectException(\Mqtt\Exception\ProtocolViolation::class);
    $this->expectExceptionCode(\Mqtt\Exception\ProtocolViolation::INCORRECT_PACKET_TYPE);
    $this->object->encode($this->packet);
  }

  public function testEncodesFrameProperly() {
    $this->packet->setId(0x33);
    $this->packet->topics['T#1'] = 0x00;
    $this->packet->topics['T#2'] = 0x01;

    $this->object->encode($this->packet);
    $this->assertEquals(\Mqtt\Protocol\IPacketType::SUBSCRIBE, $this->object->get()->packetType);
    $this->assertEquals(\Mqtt\Protocol\IPacketReservedBits::FLAGS_SUBSCRIBE, $this->object->get()->flags->get());
    $this->assertEquals(
      $this->hex2string('0033' . '0003 542331 00' . '0003 542332 01'),
      (string) $this->object->get()->payload
    );
  }

}

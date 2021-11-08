<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Decoder\Packet;

/**
 * @Inject $container
 * @property \Psr\Container\ContainerInterface $___container
 */
class FactoryTest extends \PHPUnit\Framework\TestCase {

  /**
   * @var \Mqtt\Protocol\Decoder\Packet\Factory
   */
  protected $object;

  protected function setUp() {
    $this->object = clone $this->___container->get(\Mqtt\Protocol\Decoder\Packet\Factory::class);
  }

  public function testCreateFailsForUnknownPacketType() {
    $this->expectException(\Exception::class);
    $this->object->create(-1);
  }

  public function testCreatesProperPacketType() {
    $packet = $this->object->create(\Mqtt\Protocol\IPacketType::CONNACK);
    $this->assertInstanceOf(\Mqtt\Protocol\Decoder\Packet\ControlPacket\ConnAck::class, $packet);
  }

}

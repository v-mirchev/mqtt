<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Encoder\Packet;

/**
 * @Inject $container
 * @property \Psr\Container\ContainerInterface $___container
 */
class FactoryTest extends \PHPUnit\Framework\TestCase {

  /**
   * @var \Mqtt\Protocol\Encoder\Packet\Factory
   */
  protected $object;

  protected function setUp() {
    $this->object = clone $this->___container->get(\Mqtt\Protocol\Encoder\Packet\Factory::class);
  }

  public function testCreateFailsForUnknownPacketType() {
    $this->expectException(\Exception::class);
    $this->object->create(-1);
  }

  public function testCreatesProperPacketType() {
    $packetEncoder = $this->object->create(\Mqtt\Protocol\IPacketType::CONNECT);
    $this->assertInstanceOf(\Mqtt\Protocol\Encoder\Packet\ControlPacket\Connect::class, $packetEncoder);
  }

}

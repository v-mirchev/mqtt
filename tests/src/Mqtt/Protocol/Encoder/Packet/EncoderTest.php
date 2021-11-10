<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Encoder\Packet;

/**
 * @Inject $container
 * @property \Psr\Container\ContainerInterface $___container
 */
class EncoderTest extends \PHPUnit\Framework\TestCase {

  use \Test\Helpers\TestPassedAssert;
  use \Test\Helpers\Binary;

  /**
   * @var \Mqtt\Protocol\Encoder\Packet\Encoder
   */
  protected $object;

  protected function setUp() {
    $this->object = clone $this->___container->get(\Mqtt\Protocol\Encoder\Packet\Encoder::class);
  }

  public function testCloneIsCleanInstance() {
    $this->object->onCompleted(function () {
      $this->fail('Cloning must not call original callback');
    });

    $object = clone $this->object;
    $object->encode(new \Mqtt\Protocol\Entity\Packet\Disconnect());
    $this->pass();
  }

  public function testDecodesProperly() {
    $this->object->encode(new \Mqtt\Protocol\Entity\Packet\Disconnect());
    $this->assertEquals(\Mqtt\Protocol\IPacketType::DISCONNECT, $this->object->get()->packetType);
  }

  public function testCallBackCalledProperly() {
    $this->object->onCompleted(function (\Mqtt\Protocol\Entity\Frame $frame) {
      $this->assertEquals(\Mqtt\Protocol\IPacketType::DISCONNECT, $frame->packetType);
    });

    $this->object->encode(new \Mqtt\Protocol\Entity\Packet\Disconnect());
  }

}

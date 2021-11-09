<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Encoder\Packet\ControlPacket\Flags;

/**
 * @Inject $container
 * @property \Psr\Container\ContainerInterface $___container
 */
class PublishTest extends \PHPUnit\Framework\TestCase {

  /**
   * @var \Mqtt\Protocol\Encoder\Packet\ControlPacket\Flags\Publish
   */
  protected $object;

  /**
   * @var \Mqtt\Protocol\Binary\Data\Uint8
   */
  protected $flags;

  protected function setUp() {
    $this->object = clone $this->___container->get(\Mqtt\Protocol\Encoder\Packet\ControlPacket\Flags\Publish::class);
    $this->flags = clone $this->___container->get(\Mqtt\Protocol\Binary\Data\Uint8::class);
  }

  public function testCloneedInstanceInCleanState() {
    $object = clone $this->object;
    $object->encode($this->flags);
    $this->assertEquals(0x0, $this->flags->get());
  }

  public function testProperlyEncodesRetainFlag() {
    $this->object->retain = true;
    $this->object->encode($this->flags);
    $this->assertEquals(0b00000001, $this->flags->get());

    $this->object->retain = false;
    $this->object->encode($this->flags);
    $this->assertEquals(0b00000000, $this->flags->get());
  }

  public function testProperlyEncodesDupFlag() {
    $this->object->dup = true;
    $this->object->encode($this->flags);
    $this->assertEquals(0b00001000, $this->flags->get());

    $this->object->dup = false;
    $this->object->encode($this->flags);
    $this->assertEquals(0b00000000, $this->flags->get());
  }

  public function testProperlyEncodesQos() {
    $this->object->qos = 2;
    $this->object->encode($this->flags);
    $this->assertEquals(0b0000100, $this->flags->get());

    $this->object->qos = 1;
    $this->object->encode($this->flags);
    $this->assertEquals(0b0000010, $this->flags->get());

    $this->object->qos = 0;
    $this->object->encode($this->flags);
    $this->assertEquals(0b00000000, $this->flags->get());
  }

  public function testThrowExceotionOnIncorrectQosValue() {
    $this->object->qos = 3;
    $this->expectException(\Mqtt\Exception\ProtocolViolation::class);
    $this->expectExceptionCode(\Mqtt\Exception\ProtocolViolation::INCORRECT_QOS);
    $this->object->encode($this->flags);
  }

}

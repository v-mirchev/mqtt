<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Encoder\Packet\ControlPacket\Flags;

/**
 * @Inject $container
 * @property \Psr\Container\ContainerInterface $___container
 */
class ConnectTest extends \PHPUnit\Framework\TestCase {

  /**
   * @var \Mqtt\Protocol\Encoder\Packet\ControlPacket\Flags\Connect
   */
  protected $object;

  /**
   * @var \Mqtt\Protocol\Binary\Data\Uint8
   */
  protected $flags;

  protected function setUp() {
    $this->object = clone $this->___container->get(\Mqtt\Protocol\Encoder\Packet\ControlPacket\Flags\Connect::class);
    $this->flags = clone $this->___container->get(\Mqtt\Protocol\Binary\Data\Uint8::class);
  }

  public function testCloneedInstanceInCleanState() {
    $object = clone $this->object;
    $object->encode($this->flags);
    $this->assertEquals(0x0, $this->flags->get());
  }

  public function testProperlyEncodesCleanSessionFlag() {
    $this->object->useCleanSession = true;
    $this->object->encode($this->flags);
    $this->assertEquals(0b00000010, $this->flags->get());

    $this->object->useCleanSession = false;
    $this->object->encode($this->flags);
    $this->assertEquals(0b00000000, $this->flags->get());
  }

  public function testProperlyEncodesWillFlag() {
    $this->object->useWill = true;
    $this->object->encode($this->flags);
    $this->assertEquals(0b00000100, $this->flags->get());

    $this->object->useWill = false;
    $this->object->encode($this->flags);
    $this->assertEquals(0b00000000, $this->flags->get());
  }

  public function testProperlyEncodesWillQos() {
    $this->object->willQoS = 2;
    $this->object->encode($this->flags);
    $this->assertEquals(0b00010000, $this->flags->get());

    $this->object->willQoS = 1;
    $this->object->encode($this->flags);
    $this->assertEquals(0b00001000, $this->flags->get());

    $this->object->willQoS = 0;
    $this->object->encode($this->flags);
    $this->assertEquals(0b00000000, $this->flags->get());
  }

  public function testProperlyEncodesWillRetainFlag() {
    $this->object->willRetain = true;
    $this->object->encode($this->flags);
    $this->assertEquals(0b00100000, $this->flags->get());

    $this->object->willRetain = false;
    $this->object->encode($this->flags);
    $this->assertEquals(0b00000000, $this->flags->get());
  }

  public function testProperlyEncodesPasswordFlag() {
    $this->object->usePassword = true;
    $this->object->encode($this->flags);
    $this->assertEquals(0b01000000, $this->flags->get());

    $this->object->usePassword = false;
    $this->object->encode($this->flags);
    $this->assertEquals(0b00000000, $this->flags->get());
  }

  public function testProperlyEncodesUsernameFlag() {
    $this->object->useUsername = true;
    $this->object->encode($this->flags);
    $this->assertEquals(0b10000000, $this->flags->get());

    $this->object->useUsername = false;
    $this->object->encode($this->flags);
    $this->assertEquals(0b00000000, $this->flags->get());
  }

}

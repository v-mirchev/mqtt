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
   * @var \Mqtt\Protocol\Binary\IBuffer
   */
  protected $buffer;

  protected function setUp() {
    $this->object = clone $this->___container->get(\Mqtt\Protocol\Encoder\Packet\ControlPacket\Flags\Connect::class);
    $this->buffer = clone $this->___container->get(\Mqtt\Protocol\Binary\Buffer::class);
  }

  public function testCloneedInstanceInCleanState() {
    $object = clone $this->object;
    $object->encode($this->buffer);
    $this->assertEquals(chr(0x0), (string)$this->buffer);
  }

  public function testProperlyEncodesCleanSessionFlag() {
    $this->object->useCleanSession = true;
    $this->object->encode($this->buffer);
    $this->assertEquals(chr(0b00000010), $this->buffer->get());

    $this->object->useCleanSession = false;
    $this->object->encode($this->buffer);
    $this->assertEquals(chr(0b00000000), $this->buffer->get());
  }

  public function testProperlyEncodesWillFlag() {
    $this->object->useWill = true;
    $this->object->encode($this->buffer);
    $this->assertEquals(chr(0b00000100), $this->buffer->get());

    $this->object->useWill = false;
    $this->object->encode($this->buffer);
    $this->assertEquals(chr(0b00000000), $this->buffer->get());
  }

  public function testProperlyEncodesWillQos() {
    $this->object->willQoS = 2;
    $this->object->encode($this->buffer);
    $this->assertEquals(chr(0b00010000), $this->buffer->get());

    $this->object->willQoS = 1;
    $this->object->encode($this->buffer);
    $this->assertEquals(chr(0b00001000), $this->buffer->get());

    $this->object->willQoS = 0;
    $this->object->encode($this->buffer);
    $this->assertEquals(chr(0b00000000), $this->buffer->get());
  }

  public function testProperlyEncodesWillRetainFlag() {
    $this->object->willRetain = true;
    $this->object->encode($this->buffer);
    $this->assertEquals(chr(0b00100000), $this->buffer->get());

    $this->object->willRetain = false;
    $this->object->encode($this->buffer);
    $this->assertEquals(chr(0b00000000), $this->buffer->get());
  }

  public function testProperlyEncodesPasswordFlag() {
    $this->object->usePassword = true;
    $this->object->encode($this->buffer);
    $this->assertEquals(chr(0b01000000), $this->buffer->get());

    $this->object->usePassword = false;
    $this->object->encode($this->buffer);
    $this->assertEquals(chr(0b00000000), $this->buffer->get());
  }

  public function testProperlyEncodesUsernameFlag() {
    $this->object->useUsername = true;
    $this->object->encode($this->buffer);
    $this->assertEquals(chr(0b10000000), $this->buffer->get());

    $this->object->useUsername = false;
    $this->object->encode($this->buffer);
    $this->assertEquals(chr(0b00000000), $this->buffer->get());
  }

}

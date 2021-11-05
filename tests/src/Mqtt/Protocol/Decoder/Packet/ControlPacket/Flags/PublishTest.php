<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Decoder\Packet\ControlPacket\Flags;

/**
 * @Inject $container
 * @property \Psr\Container\ContainerInterface $___container
 */
class PublishTest extends \PHPUnit\Framework\TestCase {

  /**
   * @var \Mqtt\Protocol\Decoder\Packet\ControlPacket\Flags\Publish
   */
  protected $object;

  /**
   * @var \Mqtt\Protocol\Binary\Data\Uint8
   */
  protected $uint8;

  protected function setUp() {
    $this->object = clone $this->___container->get(\Mqtt\Protocol\Decoder\Packet\ControlPacket\Flags\Publish::class);
    $this->uint8 = clone $this->___container->get(\Mqtt\Protocol\Binary\Data\Uint8::class);
  }

  public function testInitialStateClean() {
    $this->assertFalse($this->object->isDuplicate());
    $this->assertFalse($this->object->isRetain());
    $this->assertEquals(0, $this->object->getQos());
  }

  public function testClonedStateClean() {
    $object = clone $this->object;
    $this->assertFalse($object->isDuplicate());
    $this->assertFalse($object->isRetain());
    $this->assertEquals(0, $object->getQos());
  }

  public function flagsDataProvider() {
    return [
      [0b0000, false, false, 0 ],
      [0b0001, true,  false, 0 ],
      [0b0010, false, false, 1 ],
      [0b0011, true,  false, 1 ],

      [0b0100, false, false, 2 ],
      [0b0101, true,  false, 2 ],

      [0b1000, false, true,  0 ],
      [0b1001, true,  true,  0 ],
      [0b1010, false, true,  1 ],
      [0b1011, true,  true,  1 ],

      [0b1100, false, true,  2 ],
      [0b1101, true,  true,  2 ],
    ];
  }

  /**
   * @dataProvider flagsDataProvider
   */
  public function testProperlyDecodingRetainDupQos(
    int $encodedFlags, bool $retain, bool $duplicate, int $qos
  ) {
    $this->uint8->set($encodedFlags);
    $this->object->decode($this->uint8);

    $this->assertEquals($qos, $this->object->getQos());
    $this->assertEquals($retain, $this->object->isRetain());
    $this->assertEquals($duplicate, $this->object->isDuplicate());
  }

  public function incorrectFlagsDataProvider() {
    return [
      [0b0110, ],
      [0b0111, ],
      [0b0110, ],
      [0b0111, ],

      [0b1110, ],
      [0b1111, ],
    ];
  }

  /**
   * @dataProvider incorrectFlagsDataProvider
   */
  public function testDecodingFailsIncorrectQosValue(int $encodedFlags) {
    $this->uint8->set($encodedFlags);
    $this->expectException(\Mqtt\Exception\ProtocolViolation::class);
    $this->expectExceptionCode(\Mqtt\Exception\ProtocolViolation::INCORRECT_QOS);
    $this->object->decode($this->uint8);
  }


}

<?php

namespace Mqtt\Protocol\Binary;

class FixedHeaderTest extends \PHPUnit\Framework\TestCase {

  use \Test\Helpers\Binary;

  /**
   * @var FixedHeader
   */
  protected $object;

  protected function setUp() {
    $this->object = new FixedHeader(new \Mqtt\Protocol\Binary\Operator\Byte());
  }

  public function testClonedIsCleanObject() {
    $object = clone $this->object;
    $this->assertEquals($this->toStringStream(0x00, 0x00), (string)$object);
    $this->assertEmpty($object->getRemainingLength());
  }

  public function firstByteDataProvider() {
    $object = new FixedHeader(new \Mqtt\Protocol\Binary\Operator\Byte());
    $flagPermutations = [
      [
        (clone $object)->
          setQoS(\Mqtt\Entity\IQoS::AT_MOST_ONCE)->
          setAsDup(false)->
          setAsRetain(false),
        0x0
      ],
      [
        (clone $object)->
          setQoS(\Mqtt\Entity\IQoS::AT_LEAST_ONCE)->
          setAsDup(false)->
          setAsRetain(false),
        0x2
      ],
      [
        (clone $object)->
          setQoS(\Mqtt\Entity\IQoS::EXACTLY_ONCE)->
          setAsDup(false)->
          setAsRetain(false),
        0x4
      ],
      [
        (clone $object)->
          setQoS(\Mqtt\Entity\IQoS::AT_MOST_ONCE)->
          setAsDup(true)->
          setAsRetain(false),
        0x8
      ],
      [
        (clone $object)->
          setQoS(\Mqtt\Entity\IQoS::AT_LEAST_ONCE)->
          setAsDup(true)->
          setAsRetain(false),
        0xA
      ],
      [
        (clone $object)->
          setQoS(\Mqtt\Entity\IQoS::EXACTLY_ONCE)->
          setAsDup(true)->
          setAsRetain(false),
        0xC
      ],

      [
        (clone $object)->
          setQoS(\Mqtt\Entity\IQoS::AT_MOST_ONCE)->
          setAsDup(false)->
          setAsRetain(true),
        0x1
      ],
      [
        (clone $object)->
          setQoS(\Mqtt\Entity\IQoS::AT_LEAST_ONCE)->
          setAsDup(false)->
          setAsRetain(true),
        0x3
      ],
      [
        (clone $object)->
          setQoS(\Mqtt\Entity\IQoS::EXACTLY_ONCE)->
          setAsDup(false)->
          setAsRetain(true),
        0x5
      ],
      [
        (clone $object)->
          setQoS(\Mqtt\Entity\IQoS::AT_MOST_ONCE)->
          setAsDup(true)->
          setAsRetain(true),
        0x9
      ],
      [
        (clone $object)->
          setQoS(\Mqtt\Entity\IQoS::AT_LEAST_ONCE)->
          setAsDup(true)->
          setAsRetain(true),
        0xB
      ],
      [
        (clone $object)->
          setQoS(\Mqtt\Entity\IQoS::EXACTLY_ONCE)->
          setAsDup(true)->
          setAsRetain(true),
        0xD
      ],
    ];

    $permutations = [];
    for (
      $packetType = \Mqtt\Protocol\Packet\IType::CONNECT;
      $packetType <= \Mqtt\Protocol\Packet\IType::DISCONNECT;
      $packetType ++) {
        $packetPermutation = [];
        foreach ($flagPermutations as $flagPermutation) {
          $flagPermutation[0]->setPacketType($packetType);
          $flagPermutation[1] += ($packetType << 4);
          $packetPermutation = $flagPermutation;
        }
      $permutations[] = $packetPermutation;
      return $permutations;
    }
    return $permutations;
  }

  /**
   * @dataProvider firstByteDataProvider
   */
  public function testFirstByteEncoding(FixedHeader $header, int $expected) {
    $header->setRemainingLength(0);
    $this->assertEquals($this->toStringStream($expected, 0x00), (string)$header);
  }

  public function remainingLengthTestDataProvider() {
    return [
      [0, 0x00,         [0x00, 0x00]],
      [53, 0x00,        [0x00, 0x35]],
      [127, 0x00,       [0x00, 0x7F]],
      [128, 0x00,       [0x00, 0x80, 0x01]],
      [4295, 0x00,      [0x00, 0xC7, 0x21]],
      [16383, 0x00,     [0x00, 0xFF, 0x7F]],
      [16384, 0x00,     [0x00, 0x80, 0x80, 0x01]],
      [2097151, 0x00,   [0x00, 0xFF, 0xFF, 0x7F]],
      [2097152, 0x00,   [0x00, 0x80, 0x80, 0x80, 0x01]],
      [20971520, 0x00,   [0x00, 0x80, 0x80, 0x80, 0x0a]],
      [268435455, 0x00, [0x00, 0xFF, 0xFF, 0xFF, 0x7F]],
    ];
  }

  /**
   * @dataProvider remainingLengthTestDataProvider
   */
  public function testSetRemainingLengthProperlySetsBits(
    int $remainingLength,
    int $fixedHeaderByte,
    array $encodedBytes
  ) {
    $this->object->decode($this->toArrayStream($fixedHeaderByte));
    $this->object->setRemainingLength($remainingLength);
    $this->assertEquals($this->toStringStream($encodedBytes), (string)$this->object);
  }

  public function testGetSetDup() {
    $this->object->setAsDup();
    $this->assertTrue($this->object->isDup());
    $this->object->setAsDup(false);
    $this->assertFalse($this->object->isDup());
  }

  public function testGetSetRetain() {
    $this->object->setAsRetain();
    $this->assertTrue($this->object->isRetain());
    $this->object->setAsRetain(false);
    $this->assertFalse($this->object->isRetain());
  }

  public function testGetSetQoS() {
    $this->object->setQoS(0);
    $this->assertEquals(0, $this->object->getQoS());
    $this->object->setQoS(1);
    $this->assertEquals(1, $this->object->getQoS());
    $this->object->setQoS(2);
    $this->assertEquals(2, $this->object->getQoS());
  }

  public function testGetSetPacketType() {
    $this->object->setPacketType(0);
    $this->assertEquals(0, $this->object->getPacketType());
    $this->object->setPacketType(1);
    $this->assertEquals(1, $this->object->getPacketType());
    $this->object->setPacketType(2);
    $this->assertEquals(2, $this->object->getPacketType());
    $this->object->setPacketType(3);
    $this->assertEquals(3, $this->object->getPacketType());
  }

}

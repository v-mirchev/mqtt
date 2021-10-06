<?php

namespace Mqtt\Protocol\Binary;

class FixedHeaderTest extends \PHPUnit\Framework\TestCase {

  use \Test\Helpers\Binary;

  /**
   * @var FixedHeader
   */
  protected $object;

  protected function setUp() {
    $this->object = new FixedHeader;
  }

  public function testCloneIsCleanObject() {
    $object = clone $this->object;
    $this->assertEquals($this->toStringStream(0x00, 0x00), (string)$object);
  }

  public function testSetAsDupProperlySetsBit() {
    $this->object->setAsDup(true);
    $this->assertEquals($this->toStringStream( 0x08, 0x00), (string)$this->object);
    $this->object->setAsDup(false);
    $this->assertEquals($this->toStringStream( 0x00, 0x00), (string)$this->object);
  }

  public function testIsDupProperlyReadsBit() {
    $this->object->fromStream($this->toArrayStream(0x00, 0x00));
    $this->assertFalse($this->object->isDup());
    $this->object->fromStream($this->toArrayStream(0x08, 0x00));
    $this->assertTrue($this->object->isDup());
  }

  public function testSetPacketTypeProperlySetsBit() {
    $this->object->setPacketType(\Mqtt\Protocol\IPacket::CONNACK);
    $this->assertEquals($this->toStringStream(0x20, 0x00), (string)$this->object);
    $this->object->setPacketType(\Mqtt\Protocol\IPacket::UNSUBSCRIBE);
    $this->assertEquals($this->toStringStream(0xA0, 0x00), (string)$this->object);
  }

  public function testGetPacketTypeProperlyReadsBit() {
    $this->object->fromStream($this->toArrayStream(0x20, 0x00));
    $this->assertEquals(\Mqtt\Protocol\IPacket::CONNACK, $this->object->getPacketType());
    $this->object->fromStream($this->toArrayStream(0xA0, 0x00));
    $this->assertEquals(\Mqtt\Protocol\IPacket::UNSUBSCRIBE, $this->object->getPacketType());
  }

  public function testSetAsRetainProperlySetsBit() {
    $this->object->setAsRetain(true);
    $this->assertEquals($this->toStringStream(0x01, 0x00), (string)$this->object);
    $this->object->setAsRetain(false);
    $this->assertEquals($this->toStringStream(0x00, 0x00), (string)$this->object);
  }

  public function testIsRetainProperlyReadsBit() {
    $this->object->fromStream($this->toArrayStream(0x00, 0x00));
    $this->assertFalse($this->object->isRetain());
    $this->object->fromStream($this->toArrayStream(0x01, 0x00));
    $this->assertTrue($this->object->isRetain());
  }

  public function testSetQosAtMostOnceProperlySetsBits() {
    $this->object->setQoS(\Mqtt\Entity\IQoS::AT_MOST_ONCE);
    $this->assertEquals($this->toStringStream(0x00, 0x00), (string)$this->object);
    $this->object->setQoS(\Mqtt\Entity\IQoS::AT_LEAST_ONCE);
    $this->object->setQoS(\Mqtt\Entity\IQoS::AT_MOST_ONCE);
    $this->assertEquals($this->toStringStream(0x00, 0x00), (string)$this->object);
  }

  public function testGetQosProperlyReadsBitsForAtMostOnce() {
    $this->object->fromStream($this->toArrayStream(0x00, 0x00));
    $this->assertEquals(\Mqtt\Entity\IQoS::AT_MOST_ONCE, $this->object->getQoS());
  }

  public function testSetQosAtLeastOnceProperlySetsBits() {
    $this->object->setQoS(\Mqtt\Entity\IQoS::AT_LEAST_ONCE);
    $this->assertEquals($this->toStringStream(0x02, 0x00), (string)$this->object);
    $this->object->setQoS(\Mqtt\Entity\IQoS::AT_MOST_ONCE);
    $this->assertEquals($this->toStringStream(0x00, 0x00), (string)$this->object);
  }

  public function testGetQosProperlyReadsBitsForAtLeastOnce() {
    $this->object->fromStream($this->toArrayStream(0x02, 0x00));
    $this->assertEquals(\Mqtt\Entity\IQoS::AT_LEAST_ONCE, $this->object->getQoS());
  }

  public function testSetQosExactlyOnceProperlySetsBits() {
    $this->object->setQoS(\Mqtt\Entity\IQoS::EXACTLY_ONCE);
    $this->assertEquals($this->toStringStream(0x04, 0x00), (string)$this->object);
    $this->object->setQoS(\Mqtt\Entity\IQoS::AT_MOST_ONCE);
    $this->assertEquals($this->toStringStream(0x00, 0x00), (string)$this->object);
  }

  public function testGetQosProperlyReadsBitsForExactlyOnce() {
    $this->object->fromStream($this->toArrayStream(0x04, 0x00));
    $this->assertEquals(\Mqtt\Entity\IQoS::EXACTLY_ONCE, $this->object->getQoS());
  }

  public function testSetRemainingLengthSmallPayloadProperlySetsBits() {
    $this->object->setRemainingLength(17);
    $this->assertEquals($this->toStringStream(0x00, 0x11), (string)$this->object);
    $this->object->setRemainingLength(0);
    $this->assertEquals($this->toStringStream(0x00, 0x00), (string)$this->object);
  }

  public function testGetRemainingLengthSmallPayloadProperlyReadsBits() {
    $this->object->fromStream($this->toArrayStream(0x00, 0x11));
    $this->assertEquals(17, $this->object->getRemainingLength());
  }

  public function testSetRemainingLengthBigPayloadProperlySetsBits() {
    $this->object->setRemainingLength(1049);
    $this->assertEquals($this->toStringStream(0x00, 0x99, 0x08), (string)$this->object);
    $this->object->setRemainingLength(0);
    $this->assertEquals($this->toStringStream(0x00, 0x00), (string)$this->object);
  }

  public function testGetRemainingLengthBigPayloadProperlyReadsBits() {
    $this->object->fromStream($this->toArrayStream(0x00, 0x99, 0x08));
    $this->assertEquals(1049, $this->object->getRemainingLength());
  }

  public function testToStringProperlyEncodesSmallPayload() {
    $this->assertEquals(chr(0x00) . chr(0x00), (string) $this->object);

    $this->object->setAsDup();
    $this->object->setAsRetain();
    $this->object->setQoS(\Mqtt\Entity\IQoS::AT_LEAST_ONCE);
    $this->object->setPacketType(\Mqtt\Protocol\IPacket::SUBSCRIBE);
    $this->object->setRemainingLength(235);

    $this->assertEquals($this->toStringStream(0x8b, 0xeb, 0x01), (string) $this->object);
  }

}

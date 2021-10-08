<?php

namespace Mqtt\Protocol\Binary\Flags;

class ConnectTest extends \PHPUnit\Framework\TestCase {

   use \Test\Helpers\Binary;

 /**
   * @var Connect
   */
  protected $object;

  protected function setUp() {
    $this->object = new Connect(new \Mqtt\Protocol\Binary\Byte);
  }

  public function testCloneResetsInstance() {
    $cloning = clone $this->object;

    $this->assertEquals(0x00, $cloning->get());
  }

  public function testResetsInstance() {
    $this->object->reset();

    $this->assertEquals(0x00, $this->object->get());
  }

  public function testUseUsernameSetsBitProperly() {
    $this->object->useUsername(false);
    $this->assertEquals(0x00, $this->object->get());
    $this->object->useUsername(true);
    $this->assertEquals(0x80, $this->object->get());
  }

  public function testUseCleanSessionSetsBitProperly() {
    $this->object->useCleanSession(false);
    $this->assertEquals(0x00, $this->object->get());
    $this->object->useCleanSession(true);
    $this->assertEquals(0x02, $this->object->get());
  }

  public function testUsePasswordWithoutUsernameDoesNothing() {
    $this->object->usePassword(true);
    $this->assertEquals(0x00, $this->object->get());
  }

  public function testUsePasswordWithUsernameSetsBitProperly() {
    $this->object->useUsername();

    $this->object->usePassword(false);
    $this->assertEquals(0x80, $this->object->get());
    $this->object->usePassword(true);
    $this->assertEquals(0xC0, $this->object->get());
  }

  public function testUseWillSetsBitProperly() {
    $this->object->useWill(false);
    $this->assertEquals(0x00, $this->object->get());
    $this->object->useWill(true);
    $this->assertEquals(0x04, $this->object->get());
  }

  public function testSetWillQosWithoutUseWillDoesNothing() {
    $this->object->useWill(false);

    $this->object->setWillQoS(0);
    $this->assertEquals(0x00, $this->object->get());
    $this->object->setWillQoS(1);
    $this->assertEquals(0x08, $this->object->get());
    $this->object->setWillQoS(2);
    $this->assertEquals(0x10, $this->object->get());
  }

  public function testSetWillQosToAtMostOnceWithUseWillSetsBitProperly() {
    $this->object->useWill(true);
    $this->object->setWillQoS(0);
    $this->assertEquals(0x04, $this->object->get());
  }

  public function testSetWillQosToAtAtLeastOnceWithUseWillSetsBitProperly() {
    $this->object->useWill(true);
    $this->object->setWillQoS(1);
    $this->assertEquals(0x0C, $this->object->get());
  }

  public function testSetWillQosToAtExactlyOnceWithUseWillSetsBitProperly() {
    $this->object->useWill(true);
    $this->object->setWillQoS(2);
    $this->assertEquals(0x14, $this->object->get());
  }

  public function testSetWillReatinWithoutUseWillDoesNothing() {
    $this->object->useWill(false);

    $this->object->setWillRetain(false);
    $this->assertEquals(0x00, $this->object->get());
    $this->object->setWillRetain(true);
    $this->assertEquals(0x20, $this->object->get());
  }

  public function testSetWillReatinWithUseWillSetsBitProperly() {
    $this->object->useWill(true);

    $this->object->setWillRetain(false);
    $this->assertEquals(0x04, $this->object->get());
    $this->object->setWillRetain(true);
    $this->assertEquals(0x24, $this->object->get());
  }

}

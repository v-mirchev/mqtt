<?php

namespace Mqtt\Entity\Configuration;

class ProtocolTest extends \PHPUnit\Framework\TestCase {

  /**
   * @var Protocol
   */
  protected $object;

  protected function setUp() {
    $this->object = new Protocol();
  }

  public function testSetProtocolSetsProperty() {
    $protocol = '#protocol';
    $this->object->protocol($protocol);
    $this->assertEquals($protocol, $this->object->protocol);
  }

  public function testSetVersionSetsProperty() {
    $version = '#version';
    $this->object->version($version);
    $this->assertEquals($version, $this->object->version);
  }

  public function testSetProtocolFluentlySetsUsername() {
    $this->assertSame($this->object, $this->object->protocol(''));
  }

  public function testSetVersionFluentlySetsPassword() {
    $this->assertSame($this->object, $this->object->version(''));
  }


}

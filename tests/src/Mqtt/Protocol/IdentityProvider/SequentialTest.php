<?php declare(strict_types = 1);

namespace Mqtt\Protocol\IdentityProvider;

class SequentialTest extends \PHPUnit\Framework\TestCase {

  /**
   * @var \Mqtt\Protocol\IdentityProvider\Sequential
   */
  protected $object;

  protected function setUp() {
    $this->object = new \Mqtt\Protocol\IdentityProvider\Sequential();
  }

  public function testGetReturnsSequentialNumbers() {
    for ($id = 1; $id < 10; $id ++ ) {
      $this->assertEquals($id, $this->object->get());
    }
  }

  public function testGetReturnsFirstFreeNumber() {
    $id = 7;
    for ($id = 1; $id < 10; $id ++ ) {
      $this->object->get();
    }
    $this->object->free($id);
    $this->assertEquals($id, $this->object->get());
  }

  public function testGetFailureIdTooBig() {
    $this->expectException(\Exception::class, 'message');
    for ($id = 1; $id < Sequential::MAX_VALUE_16BIT + 1; $id ++ ) {
      $this->object->get();
    }
  }

}

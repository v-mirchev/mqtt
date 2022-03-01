<?php declare(strict_types = 1);

namespace Mqtt\Flow\IdentityProvider;

class SequentialTest extends \PHPUnit\Framework\TestCase {

  /**
   * @var \Mqtt\Flow\IdentityProvider\Sequential
   */
  protected $object;

  protected function setUp() {
    $this->object = new \Mqtt\Flow\IdentityProvider\Sequential();
  }

  public function testCloningIsInCleanState() {
    $this->object->get();
    $this->object->get();
    $this->object->get();
    $object = clone $this->object;
    $this->assertEquals(1, $object->get());
  }

  public function testGetFailureIdTooBig() {
    $this->expectException(\Exception::class, 'message');
    for ($id = 1; $id < Sequential::MAX_VALUE_16BIT + 1; $id ++ ) {
      $this->object->get();
    }
  }

  public function testFreeFailsWhenIdAlreadyFreed() {
    $freeId = 5;

    for ($id = 1; $id < 10; $id ++ ) {
      $this->object->get();
    }

    $this->object->free($freeId);
    $this->expectException(\Exception::class);
    $this->object->free($freeId);
  }

  public function testFreeFailsWhenIdNotUsedYet() {
    $freeId = 16;
    for ($id = 1; $id < 10; $id ++ ) {
      $this->object->get();
    }

    $this->expectException(\Exception::class);
    $this->object->free($freeId);
  }

  public function testGetReturnsSequentialNumbers() {
    for ($id = 1; $id < 10; $id ++ ) {
      $this->assertEquals($id, $this->object->get());
    }
  }

  public function testGetReturnsFirstFreeNumber() {
    $firstFreeId = 1;
    $secondFreeId = 5;
    $nextFreeId = 10;

    for ($id = 1; $id < $nextFreeId; $id ++ ) {
      $this->object->get();
    }

    $this->object->free($firstFreeId);
    $this->object->free($secondFreeId);
    $this->assertEquals($firstFreeId, $this->object->get());
    $this->assertEquals($secondFreeId, $this->object->get());

    $this->assertEquals($nextFreeId, $this->object->get());
  }

}

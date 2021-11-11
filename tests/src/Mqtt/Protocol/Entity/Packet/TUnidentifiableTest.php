<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Entity\Packet;

class TUnidentifiableTest extends \PHPUnit\Framework\TestCase {

  /**
   * @var \Mqtt\Protocol\Entity\Packet\IPacket
   */
  protected $object;

  protected function setUp() {
    $this->object = $this->getMockForTrait(\Mqtt\Protocol\Entity\Packet\TUnidentifiable::class);
  }

  public function testGetIdAttemptMustFailForEntitiesWithoutId() {
    $this->expectException(\Exception::class);
    $this->object->getId();
  }

  public function testSetIdAttemptMustFailForEntitiesWithoutId() {
    $this->expectException(\Exception::class);
    $this->object->setId(10);
  }

}

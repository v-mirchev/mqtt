<?php

namespace Mqtt\Protocol\Packet;

class FactoryTest extends \PHPUnit\Framework\TestCase {

  /**
   * @var \PHPUnit\Framework\MockObject\MockObject
   */
  protected $dicMock;

  /**
   * @var string[]
   */
  protected $classMap = [
    1 => 'Class1',
    2 => 'Class2',
    3 => 'Class3',
  ];

  /**
   * @var Factory
   */
  protected $object;

  protected function setUp() {
    $this->dicMock = $this->getMockBuilder(\Psr\Container\ContainerInterface::class)->
      getMock();

    $this->object = new Factory($this->dicMock, $this->classMap);
  }

  public function testCreateFailsOnUnknownPacketId() {
    $this->expectException(\Exception::class);
    $this->object->create(-1);
  }

  public function testCreateCallsDicToInstantinateMappedClass() {
    $expectedObject = $this->getMockBuilder(\Mqtt\Protocol\Packet\IType::class)->
      getMock();

    $this->dicMock->
      expects($this->once())->
      method('get')->
      will($this->returnValue($expectedObject));

    $actualObject = $this->object->create(2);
    $this->assertEquals($expectedObject, $actualObject);
    $this->assertInstanceOf(\Mqtt\Protocol\Packet\IType::class, $actualObject);
  }

}

<?php

namespace Mqtt\Protocol\Decoder\Frame\Fsm;

/**
 * @Inject $container
 * @property \Psr\Container\ContainerInterface $___container
 */
class FactoryTest extends \PHPUnit\Framework\TestCase {

  /**
   * @var \Mqtt\Protocol\Decoder\Frame\Fsm\Factory
   */
  protected $object;

  protected function setUp() {
    $this->object = clone $this->___container->get(\Mqtt\Protocol\Decoder\Frame\Fsm\Factory::class);
  }

  public function testCreateFailsForUnknownPacketType() {
    $this->expectException(\Exception::class);
    $this->object->create(-1);
  }

  public function testCreatesProperPacketType() {
    $packetEncoder = $this->object->create(\Mqtt\Protocol\Decoder\Frame\Fsm\State\IState::FRAME_COMPLETED);
    $this->assertInstanceOf(\Mqtt\Protocol\Decoder\Frame\Fsm\State\FrameCompleted::class, $packetEncoder);
  }

}

<?php declare(strict_types = 1);

namespace Mqtt;

class TimeoutTest extends \PHPUnit\Framework\TestCase {

  /**
   * @var Timeout
   */
  protected $object;

  /**
   * @var \PHPUnit\Framework\MockObject\MockObject
   */
  protected $consumerMock;

  /**
   * @var \phpmock\Mock
   */
  protected $timeMock;

  /**
   * @var int
   */
  protected $currentTimeStubbed;

  protected function setUp() {
    $builder = new \phpmock\MockBuilder();
    $this->timeMock = $builder->setNamespace(__NAMESPACE__)->setName('time')->setFunction(function () {
      return $this->currentTimeStubbed;
    })->build();
    $this->timeMock->enable();

    $this->consumerMock =  $this->getMockBuilder(ITimeoutHandler::class)->getMock();

    $this->object = new Timeout();
    $this->object->subscribe($this->consumerMock);
  }

  protected function tearDown() {
    $this->timeMock->disable();
  }

  public function testNoOnTimeoutCallsWhenNotStarted() {
    $this->currentTimeStubbed = 10;

    $this->object->setInterval(1);
    $this->consumerMock->expects($this->never())->method('onTimeout');

    $this->currentTimeStubbed = 15;
    $this->object->tick();
    $this->currentTimeStubbed = 20;
    $this->object->tick();
  }

  public function testOnTimeoutCalledAfterTimeout() {
    $this->object->setInterval(5);

    $this->consumerMock->expects($this->once())->method('onTimeout');

    $this->currentTimeStubbed = 10;
    $this->object->start();
    $this->currentTimeStubbed = 16;
    $this->object->tick();
  }

  public function testOnTimeoutCalledImmediatelyAfterTimeout() {
    $this->object->setInterval(5);

    $this->consumerMock->expects($this->once())->method('onTimeout');

    $this->currentTimeStubbed = 10;
    $this->object->start();
    $this->currentTimeStubbed = 15;
    $this->object->tick();
  }

  public function testOnTimeoutNotCalledWhenResetedRegularly() {
    $this->object->setInterval(10);

    $this->consumerMock->expects($this->never())->method('onTimeout');
    $this->currentTimeStubbed = 10;
    $this->object->start();
    $this->currentTimeStubbed = 5;
    $this->object->reset();
    $this->object->tick();
    $this->object->reset();
    $this->object->tick();
    $this->object->reset();
    $this->object->tick();
  }

  public function testOnTimeoutNotCalledAfterStopped() {
    $this->object->setInterval(5);

    $this->consumerMock->expects($this->never())->method('onTimeout');
    $this->object->start();
    $this->currentTimeStubbed = 10;
    $this->object->stop();
    $this->object->tick();
  }

  public function testCloneResetsInstance() {
    $this->object->setInterval(5);
    $this->object->start();

    $object = clone $this->object;
    $this->currentTimeStubbed = 10;

    $this->consumerMock->expects($this->never())->method('onTimeout');

    $this->currentTimeStubbed = 15;
    $object->tick();
    $this->currentTimeStubbed = 20;
    $object->tick();
  }

}
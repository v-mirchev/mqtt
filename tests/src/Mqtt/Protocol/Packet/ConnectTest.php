<?php

namespace Mqtt\Protocol\Packet;

class ConnectTest extends \PHPUnit\Framework\TestCase {

  use \Test\Helpers\ProxyAssert;
  use \Test\Helpers\CallSequenceAssert;

  /**
   * @var \Mqtt\Entity\Configuration\Session
   */
  protected $sessionParameters;

  /**
   * @var \PHPUnit\Framework\MockObject\MockObject
   */
  protected $flagsMock;

  /**
   * @var \PHPUnit\Framework\MockObject\MockObject
   */
  protected $frameMock;

  /**
   * @var Connect
   */
  protected $object;

  protected function setUp() {
    $this->sessionParameters = new \Mqtt\Entity\Configuration\Session(
      new \Mqtt\Entity\Configuration\Authentication(),
      new \Mqtt\Entity\Configuration\Will(new \Mqtt\Entity\QoS()),
      new \Mqtt\Entity\Configuration\Protocol()
    );

    $this->flagsMock = $this->getMockBuilder(\Mqtt\Protocol\Binary\Flags\Connect::class)->
      disableOriginalConstructor()->
      getMock();

    $this->frameMock = $this->getMockBuilder(\Mqtt\Protocol\Binary\Frame::class)->
      disableOriginalConstructor()->
      getMock();

    $this->object = new Connect($this->sessionParameters, $this->flagsMock);
  }

  public function testEncodeResetsFlags() {
    $this->flagsMock->
      expects($this->once())->
      method('reset');

    $this->flagsMock->
      expects($this->once())->
      method('get')->
      will($this->returnValue(0xA));

    $this->object->encode($this->frameMock);
  }

  public function testEncodeProxiesCleanSessionToFlags() {
    $this->sessionParameters->usePersistent();

    $frameMock = $this->getMockBuilder(\Mqtt\Protocol\Binary\Frame::class)->
      disableOriginalConstructor()->
      getMock();

    $this->proxy($this->object)->
      with($this->flagsMock)->
      method('encode')->
      arguments([ $frameMock ])->
      proxyMethod('useCleanSession')->
      proxyArguments([!$this->sessionParameters->isPersistent])->
      assert();
  }

  public function testEncodeAddsToFrameWithProperOrder() {
    $this->sessionParameters->
      usePersistent()->
      clientId('#clientId')->
      keepAliveInterval(37);

    $this->sessionParameters->authentication()->
      username('#username')->
      password('#password');

    $this->sessionParameters->will()->
      atMostOnce()->
      topic('#willTopic')->
      content('#willMessage')->
      retain();

    $frameMock = $this->getMockBuilder(\Mqtt\Protocol\Binary\Frame::class)->
      disableOriginalConstructor()->
      getMock();

    $this->callSequence()->start();

    $frameMock->
      expects($this->callSequence()->next())->
      method('setPacketType')->
      with($this->equalTo(\Mqtt\Protocol\IPacket::CONNECT));

    $frameMock->
      expects($this->callSequence()->next())->
      method('addVariableHeader')->
      with($this->equalTo($this->sessionParameters->protocol->protocol));

    $frameMock->
      expects($this->callSequence()->next())->
      method('addVariableHeaderByte')->
      with($this->equalTo($this->sessionParameters->protocol->version));

    $frameMock->
      expects($this->callSequence()->next())->
      method('addVariableHeaderByte')->
      with($this->equalTo($this->flagsMock->get()));

    $frameMock->
      expects($this->callSequence()->next())->
      method('addVariableHeaderIdentifier')->
      with($this->equalTo($this->sessionParameters->keepAliveInterval));

    $frameMock->
      expects($this->callSequence()->next())->
      method('addVariableHeader')->
      with($this->equalTo($this->sessionParameters->clientId));

    $frameMock->
      expects($this->callSequence()->next())->
      method('addVariableHeader')->
      with($this->equalTo($this->sessionParameters->will->topic));

    $frameMock->
      expects($this->callSequence()->next())->
      method('addVariableHeader')->
      with($this->equalTo($this->sessionParameters->will->content));

    $frameMock->
      expects($this->callSequence()->next())->
      method('addVariableHeader')->
      with($this->equalTo($this->sessionParameters->authentication->username));

    $frameMock->
      expects($this->callSequence()->next())->
      method('addVariableHeader')->
      with($this->equalTo($this->sessionParameters->authentication->password));

    $this->object->encode($frameMock);
  }

  public function testEncodeSkipsAuthenitcationToFrameIfNotSet() {
    $this->sessionParameters->
      usePersistent()->
      clientId('#clientId')->
      keepAliveInterval(37);

    $this->sessionParameters->authentication(false);

    $this->sessionParameters->will()->
      atMostOnce()->
      topic('#willTopic')->
      content('#willMessage')->
      retain();

    $frameMock = $this->getMockBuilder(\Mqtt\Protocol\Binary\Frame::class)->
      disableOriginalConstructor()->
      getMock();

    $frameMock->
      expects($this->callSequence()->next())->
      method('setPacketType')->
      with($this->equalTo(\Mqtt\Protocol\IPacket::CONNECT));

    $frameMock->
      expects($this->callSequence()->next())->
      method('addVariableHeader')->
      with($this->equalTo($this->sessionParameters->protocol->protocol));

    $frameMock->
      expects($this->callSequence()->next())->
      method('addVariableHeaderByte')->
      with($this->equalTo($this->sessionParameters->protocol->version));

    $frameMock->
      expects($this->callSequence()->next())->
      method('addVariableHeaderByte')->
      with($this->equalTo($this->flagsMock->get()));

    $frameMock->
      expects($this->callSequence()->next())->
      method('addVariableHeaderIdentifier')->
      with($this->equalTo($this->sessionParameters->keepAliveInterval));

    $frameMock->
      expects($this->callSequence()->next())->
      method('addVariableHeader')->
      with($this->equalTo($this->sessionParameters->clientId));

    $frameMock->
      expects($this->callSequence()->next())->
      method('addVariableHeader')->
      with($this->equalTo($this->sessionParameters->will->topic));

    $frameMock->
      expects($this->callSequence()->next())->
      method('addVariableHeader')->
      with($this->equalTo($this->sessionParameters->will->content));

    $this->object->encode($frameMock);
  }

  public function testEncodeSkipsWillToFrameIfNotUsed() {
    $this->sessionParameters->
      usePersistent()->
      clientId('#clientId')->
      keepAliveInterval(37);

    $this->sessionParameters->authentication()->
      username('#username')->
      password('#password');

    $this->sessionParameters->will(false);

    $frameMock = $this->getMockBuilder(\Mqtt\Protocol\Binary\Frame::class)->
      disableOriginalConstructor()->
      getMock();

    $this->callSequence()->start();

    $frameMock->
      expects($this->callSequence()->next())->
      method('setPacketType')->
      with($this->equalTo(\Mqtt\Protocol\IPacket::CONNECT));

    $frameMock->
      expects($this->callSequence()->next())->
      method('addVariableHeader')->
      with($this->equalTo($this->sessionParameters->protocol->protocol));

    $frameMock->
      expects($this->callSequence()->next())->
      method('addVariableHeaderByte')->
      with($this->equalTo($this->sessionParameters->protocol->version));

    $frameMock->
      expects($this->callSequence()->next())->
      method('addVariableHeaderByte')->
      with($this->equalTo($this->flagsMock->get()));

    $frameMock->
      expects($this->callSequence()->next())->
      method('addVariableHeaderIdentifier')->
      with($this->equalTo($this->sessionParameters->keepAliveInterval));

    $frameMock->
      expects($this->callSequence()->next())->
      method('addVariableHeader')->
      with($this->equalTo($this->sessionParameters->clientId));

    $frameMock->
      expects($this->callSequence()->next())->
      method('addVariableHeader')->
      with($this->equalTo($this->sessionParameters->authentication->username));

    $frameMock->
      expects($this->callSequence()->next())->
      method('addVariableHeader')->
      with($this->equalTo($this->sessionParameters->authentication->password));

    $this->object->encode($frameMock);
  }

}

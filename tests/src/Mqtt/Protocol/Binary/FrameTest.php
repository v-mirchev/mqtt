<?php

namespace Mqtt\Protocol\Binary;

class FrameTest extends \PHPUnit\Framework\TestCase {

  use \Test\Helpers\ProxyAssert;
  use \Test\Helpers\Binary;

  /**
   * @var Frame
   */
  protected $object;

  /**
   * @var \PHPUnit\Framework\MockObject\MockObject
   */
  protected $fixedHeaderMock;

  /**
   * @var \PHPUnit\Framework\MockObject\MockObject
   */
  protected $variableHeaderMock;

  protected function setUp() {
    $this->fixedHeaderMock = $this->getMockBuilder(\Mqtt\Protocol\Binary\FixedHeader::class)->
      disableOriginalClone()->
      disableOriginalConstructor()->
      getMock();

    $this->fixedHeaderMock->
      method('__toString')->
      will($this->returnValue('#FixedHeader'));

    $this->variableHeaderMock = $this->getMockBuilder(\Mqtt\Protocol\Binary\VariableHeader::class)->
      disableOriginalClone()->
      disableOriginalConstructor()->
      getMock();

    $this->variableHeaderMock->
      expects($this->any())->
      method('create')->
      will($this->returnSelf());

    $this->variableHeaderMock->
      expects($this->any())->
      method('createByte')->
      will($this->returnSelf());

    $this->variableHeaderMock->
      expects($this->any())->
      method('createIdentifier')->
      will($this->returnSelf());

    $this->variableHeaderMock->
      method('__toString')->
      will($this->returnValue('#VariableHeader'));

    $this->object = new Frame($this->fixedHeaderMock, $this->variableHeaderMock);
  }

  public function testCloneResetsInstance() {
    $this->object->addVariableHeaderByte(0x05);
    $object = clone $this->object;
    $this->assertEquals('#FixedHeader', (string) $object);
  }

  public function testEncodingFixedHeaderOnly() {
    $this->assertEquals('#FixedHeader', (string)$this->object);
  }

  public function testEncodingFixedHeaderSingleVariableHeaderByteN() {
    $this->object->addVariableHeaderByte(5);
    $this->assertEquals('#FixedHeader#VariableHeader', (string)$this->object);
  }

  public function testEncodingFixedHeaderSingleVariableHeaderContent() {
    $this->object->addVariableHeader('');
    $this->assertEquals('#FixedHeader#VariableHeader', (string)$this->object);
  }

  public function testEncodingFixedHeaderSingleVariableHeaderIdentifier() {
    $this->object->addVariableHeaderIdentifier(67);
    $this->assertEquals('#FixedHeader#VariableHeader', (string)$this->object);
  }

  public function testEncodingFixedHeaderMultipleVariableHeaders() {
    $this->object->addVariableHeader('');
    $this->object->addVariableHeaderByte(0x06);
    $this->object->addVariableHeaderIdentifier(45);

    $this->assertEquals('#FixedHeader#VariableHeader#VariableHeader#VariableHeader', (string)$this->object);
  }

  public function testEncodingFixedHeaderMultipleVariableHeadersAndBody() {
    $this->object->addVariableHeader('');
    $this->object->addVariableHeaderByte(0x06);
    $this->object->addVariableHeaderIdentifier(45);
    $this->object->setPayload('#Payload');

    $this->assertEquals('#FixedHeader#VariableHeader#VariableHeader#VariableHeader#Payload', (string)$this->object);
  }

  public function testFromStreamReadsProperlyNoBody() {
    $expectedBody = '';

    $this->object->getFixedHeaderInstane()->
      expects($this->once())->
      method('fromStream');

    $this->object->getFixedHeaderInstane()->
      expects($this->once())->
      method('getRemainingLength')->
      will($this->returnValue(0));

    $this->object->getVariableHeaderInstane()->
      expects($this->once())->
      method('set')->
      with($this->equalTo($expectedBody));

    $this->object->fromStream(new \ArrayIterator([ '#FixedHeader' , '#Body1', '#Body2', '#Body3']));

    $this->assertEquals($expectedBody, $this->object->getBody());
  }

  public function testFromStreamReadsProperlySingleBlockBody() {
    $expectedBody = '#Body1';

    $this->object->getFixedHeaderInstane()->
      expects($this->once())->
      method('fromStream');

    $this->object->getFixedHeaderInstane()->
      expects($this->once())->
      method('getRemainingLength')->
      will($this->returnValue(1));

    $this->object->getVariableHeaderInstane()->
      expects($this->once())->
      method('set')->
      with($this->equalTo($expectedBody));

    $this->object->fromStream(new \ArrayIterator([ '#FixedHeader' , '#Body1', '#Body2', '#Body3']));

    $this->assertEquals($expectedBody, $this->object->getBody());
  }

  public function testFromStreamReadsProperlyTwoBlocksBody() {
    $expectedBody = '#Body1#Body2';

    $this->object->getFixedHeaderInstane()->
      expects($this->once())->
      method('fromStream');

    $this->object->getFixedHeaderInstane()->
      expects($this->once())->
      method('getRemainingLength')->
      will($this->returnValue(2));

    $this->object->getVariableHeaderInstane()->
      expects($this->once())->
      method('set')->
      with($this->equalTo($expectedBody));

    $this->object->fromStream(new \ArrayIterator([ '#FixedHeader' , '#Body1', '#Body2', '#Body3']));

    $this->assertEquals($expectedBody, $this->object->getBody());
  }

  public function testSetAsDupProxiesCalls() {
    $this->proxy($this->object)->
      with($this->object->getFixedHeaderInstane())->
      method('setAsDup')->
      arguments([ true ])->
      assert();
  }

  public function testIsDupProxiesCalls() {
    $this->proxy($this->object)->
      with($this->object->getFixedHeaderInstane())->
      method('isDup')->
      returnValue(true)->
      assert();
  }


  public function testSetAsRetainProxiesCalls() {
    $this->proxy($this->object)->
      with($this->object->getFixedHeaderInstane())->
      method('setAsRetain')->
      arguments([ true ])->
      assert();
  }


  public function testIsRetainProxiesCalls() {
    $this->proxy($this->object)->
      with($this->object->getFixedHeaderInstane())->
      method('isRetain')->
      returnValue(true)->
      assert();
  }

  public function testSetPacketTypeProxiesCalls() {
    $this->proxy($this->object)->
      with($this->object->getFixedHeaderInstane())->
      method('setPacketType')->
      arguments([ 5 ])->
      assert();
  }

  public function testGetPacketTypeProxiesCalls() {
    $this->proxy($this->object)->
      with($this->object->getFixedHeaderInstane())->
      method('getPacketType')->
      returnValue(5)->
      assert();
  }

  public function testSetQosProxiesCalls() {
    $this->proxy($this->object)->
      with($this->object->getFixedHeaderInstane())->
      method('setQoS')->
      arguments([ 2 ])->
      assert();
  }

  public function testGetQosProxiesCalls() {
    $this->proxy($this->object)->
      with($this->object->getFixedHeaderInstane())->
      method('getQoS')->
      returnValue(2)->
      assert();
  }

  public function testGetPayloadProxiesCalls() {
    $this->proxy($this->object)->
      with($this->object->getVariableHeaderInstane())->
      method('getPayload')->
      proxyMethod('getBody')->
      returnValue('ABCDEF1234')->
      assert();
  }

  public function testGetVariableHeaderProxiesCalls() {
    $this->proxy($this->object)->
      with($this->object->getVariableHeaderInstane())->
      method('getVariableHeader')->
      proxyMethod('get')->
      returnValue('ABCDEF1234')->
      assert();
  }

  public function testGetVariableHeaderByteProxiesCalls() {
    $this->proxy($this->object)->
      with($this->object->getVariableHeaderInstane())->
      method('getVariableHeaderByte')->
      proxyMethod('getByte')->
      returnValue(0x5)->
      assert();
  }

  public function testGetVariableHeaderIdentifierProxiesCalls() {
    $this->proxy($this->object)->
      with($this->object->getVariableHeaderInstane())->
      method('getVariableHeaderIdentifier')->
      proxyMethod('getIdentifier')->
      returnValue(22)->
      assert();
  }

  public function testGetPayloadIdentifiersProxiesCalls() {
    $this->proxy($this->object)->
      with($this->object->getVariableHeaderInstane())->
      method('getPayloadBytes')->
      proxyMethod('getBytes')->
      returnValue([22, 33, 44])->
      assert();
  }

}

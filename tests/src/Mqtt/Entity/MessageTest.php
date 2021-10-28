<?php

namespace Mqtt\Entity;

class MessageTest extends \PHPUnit\Framework\TestCase {

  /**
   * @var \Mqtt\Entity\Message
   */
  protected $object;

  protected function setUp() {
    $this->object = new \Mqtt\Entity\Message(new \Mqtt\Entity\QoS());
  }

  public function testQoSFluentlyProxiesCall() {
    $this->object->atMostOnce();
    $this->assertEquals($this->object->qos->qos, QoS::AT_MOST_ONCE);
    $this->object->atLeastOnce();
    $this->assertEquals($this->object->qos->qos, QoS::AT_LEAST_ONCE);
    $this->object->exactlyOnce();
    $this->assertEquals($this->object->qos->qos, QoS::EXACTLY_ONCE);
  }

  public function testSetTopicChangesProperty() {
    $topic = '#topic';
    $this->object->topic($topic);
    $this->assertEquals($topic, $this->object->topic);
  }

  public function testSetContentChangesProperty() {
    $content = '#content';
    $this->object->content($content);
    $this->assertEquals($content, $this->object->content);
  }

  public function testSetRetainChangesProperty() {
    $retain = true;
    $this->object->retain($retain);
    $this->assertEquals($retain, $this->object->isRetain);
  }

  public function testSetTopicFluently() {
    $this->assertSame($this->object, $this->object->topic(''));
  }

  public function testSetContentFluently() {
    $this->assertSame($this->object, $this->object->content(''));
  }

  public function testSetRetainFluently() {
    $this->assertSame($this->object, $this->object->retain());
  }

}

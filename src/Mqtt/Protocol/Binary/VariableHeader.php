<?php

namespace Mqtt\Protocol\Binary;

class VariableHeader {

  /**
   * @var \Mqtt\Protocol\Binary\IBuffer
   */
  protected $buffer;

  /**
   * @var \Mqtt\Protocol\Binary\Data\Uint16
   */
  protected $uint16;

  /**
   * @var string
   */
  protected $content;

  /**
   * @param \Mqtt\Protocol\Binary\IBuffer $buffer
   * @param \Mqtt\Protocol\Binary\Data\Uint16 $word
   */
  public function __construct(
    \Mqtt\Protocol\Binary\IBuffer $buffer,
    \Mqtt\Protocol\Binary\Data\Uint16 $word
  ) {
    $this->buffer = clone $buffer;
    $this->uint16 = clone $word;

    $this->content = '';
  }

  /**
   * @param string $content
   */
  public function createString(string $content) : \Mqtt\Protocol\Binary\VariableHeader {
    $instance = clone $this;
    $instance->uint16->set(strlen($content));
    $instance->content = $content;

    return $instance;
  }

  /**
   * @param int $identifier
   */
  public function createWord(int $identifier) {
    $instance = clone $this;
    $instance->uint16->set($identifier);
    $instance->content = '';

    return $instance;
  }

  /**
   * @param int $byte
   */
  public function createByte(int $byte) {
    $instance = clone $this;
    $instance->uint16 = null;
    $instance->content = chr($byte);

    return $instance;
  }

  /**
   * @param string $body
   */
  public function decode(string $body) {
    $this->buffer->set($body);
  }

  /**
   * @return string
   */
  public function getBody(): string {
    return (string) $this->buffer;
  }

  /**
   * @param int length
   * @return $this
   */
  public function getString() : string {
    $this->uint16->decode($this->buffer);
    $content = $this->buffer->getString($this->uint16->get());
    return $content;
  }

  /**
   * @return int
   */
  public function getWord() {
    $this->uint16->decode($this->buffer);
    return $this->uint16->get();
  }

  /**
   * @return string
   */
  public function getByte() : int {
    $this->uint16->set($this->buffer->getByte());
    return $this->uint16->getLsb()->get();
  }

  /**
   * @return int[]
   */
  public function getBytes() {
    return $this->buffer->getBytes();
  }

  /**
   * @return string
   */
  public function __toString() : string {
    return
      $this->uint16 .
      $this->content;
  }

  public function __clone() {
    $this->buffer = clone $this->buffer;
    $this->uint16 = clone $this->uint16;
    $this->content = '';
  }

}

<?php

namespace Mqtt\Protocol\Binary;

class VariableHeader {

  /**
   * @var \Mqtt\Protocol\Binary\Data\Buffer
   */
  protected $buffer;

  /**
   * @var \Mqtt\Protocol\Binary\Data\Word
   */
  protected $word;

  /**
   * @var string
   */
  protected $content;

  /**
   * @param \Mqtt\Protocol\Binary\Data\Buffer $buffer
   * @param \Mqtt\Protocol\Binary\Data\Word $word
   */
  public function __construct(
    \Mqtt\Protocol\Binary\Data\Buffer $buffer,
    \Mqtt\Protocol\Binary\Data\Word $word
  ) {
    $this->buffer = clone $buffer;
    $this->word = clone $word;

    $this->content = '';
  }

  /**
   * @param string $content
   */
  public function createString(string $content) : \Mqtt\Protocol\Binary\VariableHeader {
    $instance = clone $this;
    $instance->word->set(strlen($content));
    $instance->content = $content;

    return $instance;
  }

  /**
   * @param int $identifier
   */
  public function createWord(int $identifier) {
    $instance = clone $this;
    $instance->word->set($identifier);
    $instance->content = '';

    return $instance;
  }

  /**
   * @param int $byte
   */
  public function createByte(int $byte) {
    $instance = clone $this;
    $instance->word = null;
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
    $this->word->setBytes($this->buffer->getByte(), $this->buffer->getByte());
    $content = $this->buffer->getString($this->word->get());
    return $content;
  }

  /**
   * @return int
   */
  public function getWord() {
    $this->word->setBytes($this->buffer->getByte(), $this->buffer->getByte());
    return $this->word->get();
  }

  /**
   * @return string
   */
  public function getByte() : int {
    $this->word->setBytes(0, $this->buffer->getByte());
    return $this->word->getLsb()->get();
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
      $this->word .
      $this->content;
  }

  public function __clone() {
    $this->buffer = clone $this->buffer;
    $this->word = clone $this->word;
    $this->content = '';
  }

}

<?php

namespace Mqtt\Protocol\Binary;

class VariableHeader {

  /**
   * @var \Mqtt\Protocol\Binary\Word
   */
  protected $word;

  /**
   * @var string
   */
  protected $content;

  /**
   * @var string
   */
  protected $body;

  /**
   * @var bool
   */

  /**
   * @param \Mqtt\Protocol\Binary\Word $word
   */
  public function __construct(\Mqtt\Protocol\Binary\Word $word) {
    $this->word = clone $word;
    $this->content = '';
    $this->body = '';
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
   * @param string $byte
   */
  public function createByte(string $byte) {
    $instance = clone $this;
    $instance->word = null;
    $instance->content = chr($byte);

    return $instance;
  }

  /**
   * @param string $body
   */
  public function decode(string $body) {
    $this->body = $body;
  }

  /**
   * @return string
   */
  public function getBody(): string {
    return $this->body;
  }

  /**
   * @param int length
   * @return $this
   */
  public function getString() : string {
    $this->word->setBytes($this->body[0], $this->body[1]);
    $content = substr($this->body, 2, $this->word->get());
    $this->body = substr($this->body, $this->word->get() + 2);

    return $content;
  }

  /**
   * @return int
   */
  public function getWord() {
    $this->word->setBytes($this->body[0], $this->body[1]);
    $this->body = substr($this->body, 2);
    return $this->word->get();
  }

  /**
   * @return string
   */
  public function getByte() : int {
    $this->word->setBytes(0, $this->body[0]);
    $this->body = substr($this->body, 1);
    return $this->word->getLsb()->get();
  }

  /**
   * @return int[]
   */
  public function getBytes() {
    $bytes = [];
    while (strlen($this->body)) {
      $bytes[] = $this->getByte();
    }
    return $bytes;
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
    $this->word = clone $this->word;
    $this->content = '';
    $this->body = '';
  }

}

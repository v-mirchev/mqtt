<?php

namespace Mqtt\Protocol\Binary;

class VariableHeader {

  /**
   * @var int
   */
  protected $lsb;

  /**
   * @var int
   */
  protected $msb;

  /**
   * @var string
   */
  protected $content;

  /**
   * @var string
   */
  protected $body;

  public function __construct() {
    $this->lsb = 0;
    $this->msb = 0;
    $this->content = '';
    $this->body = '';
  }

  /**
   * @param string $content
   */
  public function create(string $content) : \Mqtt\Protocol\Binary\VariableHeader {
    $instance = clone $this;
    $dataLength = strlen($content);
    $instance->msb = $dataLength >> 8;
    $instance->lsb = $dataLength % 256;
    $instance->content = $content;

    return $instance;
  }

  /**
   * @param string $body
   */
  public function set(string $body) {
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
  public function get() : string {
    $length = (ord($this->body[0]) << 4) + ord($this->body[1]);
    $content = substr($this->body, 2, $length);
    $this->body = substr($this->body, $length + 2);

    return $content;
  }

  /**
   * @param int $identifier
   */
  public function createIdentifier(int $identifier) {
    $instance = clone $this;
    $instance->msb = $identifier >> 8;
    $instance->lsb = $identifier % 256;
    $instance->content = '';

    return $instance;
  }

  /**
   * @return int
   */
  public function getIdentifier() {
    $identifier = (ord($this->body[0]) << 4) + ord($this->body[1]);
    $this->body = substr($this->body, 2);
    return $identifier;
  }

  /**
   * @return int[]
   */
  public function getBytes() {
    $identifiers = [];
    while (strlen($this->body)) {
      $identifiers[] = $this->getByte();
    }
    return $identifiers;
  }

  /**
   * @param string $byte
   */
  public function createByte(string $byte) {
    $instance = clone $this;
    $instance->msb = null;
    $instance->lsb = null;
    $instance->content = chr($byte);

    return $instance;
  }

  /**
   * @return string
   */
  public function getByte() : int {
    $byte = (ord($this->body[0]));
    $this->body = substr($this->body, 1);
    return $byte;
  }

  /**
   * @return string
   */
  public function __toString() : string {
    return
      ( is_null($this->msb) ? '' : \chr($this->msb) ) .
      ( is_null($this->lsb) ? '' : \chr($this->lsb) ) .
      $this->content;
  }

  public function __clone() {
    $this->lsb = 0;
    $this->msb = 0;
    $this->content = '';
    $this->body = '';
  }

}

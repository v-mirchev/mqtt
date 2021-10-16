<?php

namespace Mqtt\Protocol\Binary;

class Frame {

  /**
   * @var \Mqtt\Protocol\Binary\IFixedHeader
   */
  public $fixedHeader;

  /**
   * @var \Mqtt\Protocol\Binary\VariableHeader
   */
  protected $variableHeader;

  /**
   * @var string
   */
  protected $payload;

  /**
   * @var string
   */
  protected $body;

  /**
   * @var \Mqtt\Protocol\Binary\VariableHeader[]
   */
  protected $variableHeaders;

  /**
   * @param \Mqtt\Protocol\Binary\IFixedHeader $fixedHeader
   * @param \Mqtt\Protocol\Binary\VariableHeader $variableHeader
   */
  public function __construct(
    \Mqtt\Protocol\Binary\IFixedHeader $fixedHeader,
    \Mqtt\Protocol\Binary\VariableHeader $variableHeader
  ) {
    $this->fixedHeader = clone $fixedHeader;
    $this->variableHeader = clone $variableHeader;
    $this->variableHeaders = [];
    $this->payload = '';
  }

  /**
   * @param \Iterator $stream
   */
  public function decode(\Iterator $stream) : void {
    $this->body = '';
    $this->fixedHeader->decode($stream);
    $remainingLength = $this->fixedHeader->getRemainingLength();
    for ($i = 0; $i < $remainingLength; $i++) {
      $stream->next();
      $this->body .= $stream->current();
    }
    $this->variableHeader->decode($this->body);
  }

  /**
   * @param int length
   * @return $this
   */
  public function getString() : string {
    return $this->variableHeader->getString();
  }

  /**
   * @param string $content
   * @return $this
   */
  public function addString(string $content) : \Mqtt\Protocol\Binary\Frame {
    $this->variableHeaders[] = $this->variableHeader->createString($content);
    return $this;
  }

  /**
   * @return int
   */
  public function getWord() : int {
    return $this->variableHeader->getWord();
  }

  /**
   * @param int $identifier
   * @return $this
   */
  public function addWord(int $identifier) : \Mqtt\Protocol\Binary\Frame {
    $this->variableHeaders[] = $this->variableHeader->createWord($identifier);
    return $this;
  }

  /**
   * @return string
   */
  public function getByte() : int {
    return $this->variableHeader->getByte();
  }

  /**
   * @param int $byte
   * @return $this
   */
  public function addByte(int $byte): \Mqtt\Protocol\Binary\Frame {
    $this->variableHeaders[] = $this->variableHeader->createByte($byte);
    return $this;
  }

  /**
   * @param string $payload
   * @return $this
   */
  public function setPayload(string $payload): \Mqtt\Protocol\Binary\Frame {
    $this->payload = $payload;
    return $this;
  }

  /**
   * @param string $payload
   * @return $this
   */
  public function getPayload() : string {
    return $this->variableHeader->getBody();
  }

  /**
   * @param type $type
   * @return $this
   */
  public function setPacketType(int $type): \Mqtt\Protocol\Binary\Frame {
    $this->fixedHeader->setPacketType($type);
    return $this;
  }

  /**
   * @return int
   */
  public function getPacketType() : int {
    return $this->fixedHeader->getPacketType();
  }

  /**
   * @return int
   */
  public function getQoS() : int {
    return $this->fixedHeader->getQoS();
  }

  /**
   * @param int $qos
   * @return $this
   */
  public function setQoS(int $qos): \Mqtt\Protocol\Binary\Frame {
    $this->fixedHeader->setQoS($qos);
    return $this;
  }

  /**
   * @return bool
   */
  public function isDup() : bool {
    return $this->fixedHeader->isDup();
  }

  /**
   * @param bool $dup
   * @return $this
   */
  public function setAsDup(bool $dup = true): \Mqtt\Protocol\Binary\Frame {
    $this->fixedHeader->setAsDup($dup);
    return $this;
  }

  /**
   * @param bool $retain
   * @return $this
   */
  public function setAsRetain(bool $retain = true): \Mqtt\Protocol\Binary\Frame {
    $this->fixedHeader->setAsRetain($retain);
    return $this;
  }

  /**
   * @return bool
   */
  public function isRetain() : bool {
    return $this->fixedHeader->isRetain();
  }

  /**
   * @return string
   */
  public function __toString() : string {
    $variableHeaders = implode('', $this->variableHeaders);
    $this->fixedHeader->setRemainingLength(strlen($variableHeaders) + strlen($this->payload));
    return (string)$this->fixedHeader . $variableHeaders . $this->payload;
  }

  public function __clone() {
    $this->fixedHeader = clone $this->fixedHeader;
    $this->variableHeader = clone $this->variableHeader;
    $this->variableHeaders = [];
    $this->payload = '';
    $this->body = '';
  }

  /**
   * @return int[]
   */
  public function getPayloadBytes() : array {
    return $this->variableHeader->getBytes();
  }

}

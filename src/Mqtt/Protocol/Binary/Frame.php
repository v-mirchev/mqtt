<?php

namespace Mqtt\Protocol\Binary;

class Frame {

  /**
   * @var \Mqtt\Protocol\Binary\FixedHeader
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
   * @param \Mqtt\Protocol\Binary\FixedHeader $fixedHeader
   * @param \Mqtt\Protocol\Binary\VariableHeader $variableHeader
   */
  public function __construct(
    \Mqtt\Protocol\Binary\FixedHeader $fixedHeader,
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
  public function fromStream(\Iterator $stream) : void {
    $this->body = '';
    $this->fixedHeader->fromStream($stream);
    $remainingLength = $this->fixedHeader->getRemainingLength();
    for ($i = 0; $i < $remainingLength; $i++) {
      $stream->next();
      $this->body .= $stream->current();
    }
    $this->variableHeader->set($this->body);
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
  public function getVariableHeader() : string {
    return $this->variableHeader->get();
  }

  /**
   * @param string $content
   * @return $this
   */
  public function addVariableHeader(string $content) : \Mqtt\Protocol\Binary\Frame {
    $this->variableHeaders[] = $this->variableHeader->create($content);
    return $this;
  }

  /**
   * @return int
   */
  public function getVariableHeaderIdentifier() : int {
    return $this->variableHeader->getIdentifier();
  }

  /**
   * @param int $identifier
   * @return $this
   */
  public function addVariableHeaderIdentifier(int $identifier) : \Mqtt\Protocol\Binary\Frame {
    $this->variableHeaders[] = $this->variableHeader->createIdentifier($identifier);
    return $this;
  }

  /**
   * @return string
   */
  public function getVariableHeaderByte() : int {
    return $this->variableHeader->getByte();
  }

  /**
   * @param int $byte
   * @return $this
   */
  public function addVariableHeaderByte(string $byte): \Mqtt\Protocol\Binary\Frame {
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
    return $this->fixedHeader . $variableHeaders . $this->payload;
  }

  public function __clone() {
    $this->fixedHeader = clone $this->fixedHeader;
    $this->variableHeader = clone $this->variableHeader;
    $this->variableHeaders = [];
    $this->payload = '';
    $this->body = '';
  }


  /**
   * @return \Mqtt\Protocol\Binary\FixedHeader
   * @CodeSmell Used only for unit testing, no Singleton used
   */
  public function getFixedHeaderInstane(): \Mqtt\Protocol\Binary\FixedHeader {
    return $this->fixedHeader;
  }

  /**
   * @return \Mqtt\Protocol\Binary\VariableHeader
   * @CodeSmell Used only for unit testing, no Singleton used
   */
  public function getVariableHeaderInstane(): \Mqtt\Protocol\Binary\VariableHeader {
    return $this->variableHeader;
  }

  /**
   * @return int[]
   */
  public function getPayloadBytes() : array {
    return $this->variableHeader->getBytes();
  }

}

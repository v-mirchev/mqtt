<?php

namespace Mqtt\Protocol\Binary\Data;

class UintVariable implements \Mqtt\Protocol\Binary\Data\ICodec {

  /**
   * @var string
   */
  protected $value;

  /**
   * @var \Mqtt\Protocol\Binary\Data\Uint8[]
   */
  protected $lengthBytes;

  /**
   * @param \Mqtt\Protocol\Binary\Data\Bit $length
   */
  public function __construct(\Mqtt\Protocol\Binary\Data\Uint8 $length) {
    $this->value = 0;
    $this->length = clone $length;
  }

  /**
   * @param string $content
   * @return $this
   */
  public function set(string $content) : \Mqtt\Protocol\Binary\Data\UintVariable {
    $this->value = (string)$content;
    $this->length->set(strlen($this->value));
    return $this;
  }

  /**
   * @return string
   */
  public function get(): string {
    return $this->value;
  }

  /**
   * @return string
   */
  public function __toString() : string {
    return $this->length . $this->value;
  }

  public function __clone() {
    $this->value = '';
    $this->length = clone $this->length;
  }


  /**
   * @param \Mqtt\Protocol\Binary\Data\Buffer $buffer
   * @return void
   */
  public function decode(\Mqtt\Protocol\Binary\Data\Buffer $buffer): void {
    $this->length->decode($buffer);
    $this->set($buffer->getString($this->length->get()));
  }

  /**
   * @param \Mqtt\Protocol\Binary\Data\Buffer $buffer
   * @return void
   */
  public function encode(\Mqtt\Protocol\Binary\Data\Buffer $buffer): void {
    $buffer->append((string) $this);
  }
}

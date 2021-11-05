<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Binary\Data;

class Utf8String implements \Mqtt\Protocol\Binary\Data\ICodec {

  /**
   * @var string
   */
  protected $content;

  /**
   * @var \Mqtt\Protocol\Binary\Data\Uint16
   */
  protected $length;

  /**
   * @param \Mqtt\Protocol\Binary\Data\Bit $length
   */
  public function __construct(\Mqtt\Protocol\Binary\Data\Uint16 $length) {
    $this->content = '';
    $this->length = clone $length;
  }

  /**
   * @param string $content
   * @return $this
   */
  public function set(string $content) : \Mqtt\Protocol\Binary\Data\Utf8String {
    $this->content = (string)$content;
    $this->length->set(strlen($this->content));
    return $this;
  }

  /**
   * @return string
   */
  public function get(): string {
    return $this->content;
  }

  /**
   * @return string
   */
  public function __toString() : string {
    return $this->length . $this->content;
  }

  public function __clone() {
    $this->content = '';
    $this->length = clone $this->length;
  }


  /**
   * @param \Mqtt\Protocol\Binary\IBuffer $buffer
   * @return void
   */
  public function decode(\Mqtt\Protocol\Binary\IBuffer $buffer): void {
    $this->length->decode($buffer);
    $this->set($buffer->getString($this->length->get()));
  }

  /**
   * @param \Mqtt\Protocol\Binary\IBuffer $buffer
   * @return void
   */
  public function encode(\Mqtt\Protocol\Binary\IBuffer $buffer): void {
    $buffer->append((string) $this);
  }
}

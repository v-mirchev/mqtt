<?php

namespace Mqtt\Protocol\Binary\Data;

class Buffer {

  /**
   * @var string
   */
  protected $buffer;

  /**
   * @var int
   */
  protected $position;

  public function __construct() {
    $this->buffer = '';
    $this->position = 0;
  }

  public function __clone() {
    $this->buffer = '';
    $this->position = 0;
  }

  /**
   * @param int $length
   * @return string
   */
  public function getString(int $length = null): string {
    if ($this->position + $length > strlen($this->buffer)) {
      throw new \Exception('Trying to read outside buffer');
    }
    $value = is_null($length) ?
       substr($this->buffer, $this->position) :
       substr($this->buffer, $this->position, $length);
    $this->position = $length ? $this->position + $length : strlen($this->buffer);
    return $value;
  }

  /**
   * @param int $count
   * @return int[]
   */
  public function getBytes(int $count = null): array {
    $value = $this->getString($count);
    if ($value === '') {
      return [];
    }
    return array_map('ord', str_split($value));
  }

  /**
   * @return int
   */
  public function getByte(): int {
    return ord($this->getString(1));
  }

  /**
   * @return bool
   */
  public function eof(): bool {
    return $this->position === strlen($this->buffer);
  }

  /**
   * @param string $buffer
   * @return $this
   */
  public function set(string $buffer) {
    $this->buffer = $buffer;
    $this->position = 0;
    return $this;
  }

  /**
   * @param string $encodedData
   * @return $this
   */
  public function append(string $encodedData) : \Mqtt\Protocol\Binary\Data\Buffer {
    $this->buffer .= $encodedData;
    return $this;
  }

  /**
   * @return string
   */
  public function __toString() {
    return $this->getString();
  }

}

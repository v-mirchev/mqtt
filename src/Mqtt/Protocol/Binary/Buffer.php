<?php

namespace Mqtt\Protocol\Binary;

class Buffer implements \Mqtt\Protocol\Binary\IBuffer {

  /**
   * @var string
   */
  protected $buffer;

  public function __construct() {
    $this->buffer = '';
  }

  public function __clone() {
    $this->buffer = '';
  }

  /**
   * @param int $length
   * @return \Mqtt\Protocol\Binary\IBuffer
   */
  public function get(int $length = null) : \Mqtt\Protocol\Binary\IBuffer {
    $buffer = clone $this;
    $buffer->buffer = $this->getString($length);
    return $buffer;
  }

  /**
   * @param int $length
   * @return string
   */
  public function getString(int $length = null): string {
    if ($length > strlen($this->buffer)) {
      throw new \Exception('Trying to read outside buffer');
    }
    $value = is_null($length) ? $this->buffer : substr($this->buffer, 0, $length );
    $this->buffer = is_null($length) ?  '' : substr($this->buffer, $length);
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
   * @return string
   */
  public function getChar(): string {
    return $this->getString(1);
  }

  /**
   * @return int
   */
  public function length(): int {
    return strlen($this->buffer);
  }

  /**
   * @return bool
   */
  public function isEmpty(): bool {
    return strlen($this->buffer) === 0;
  }

  /**
   * @param string $buffer
   * @return $this
   */
  public function set(string $buffer) : \Mqtt\Protocol\Binary\IBuffer {
    $this->buffer = $buffer;
    return $this;
  }

  /**
   * @return $this
   */
  public function reset() : \Mqtt\Protocol\Binary\IBuffer {
    $this->set('');
    return $this;
  }

  /**
   * @param string $encodedData
   * @return $this
   */
  public function append(string $encodedData) : \Mqtt\Protocol\Binary\IBuffer {
    $this->buffer .= $encodedData;
    return $this;
  }

  /**
   * @return string
   */
  public function __toString() : string {
    return $this->getString();
  }

  public function getIterator(): \Traversable {
    return (function() {
      while (!$this->isEmpty()) {
        yield $this->getString(1);
      }
    })();
  }

}

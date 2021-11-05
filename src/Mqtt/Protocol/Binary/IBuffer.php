<?php

namespace Mqtt\Protocol\Binary;

interface IBuffer extends \IteratorAggregate {

  /**
   * @param int $length
   * @return \Mqtt\Protocol\Binary\IBuffer
   */
  public function get(int $length = null) : \Mqtt\Protocol\Binary\IBuffer;

  /**
   * @param int $length
   * @return string
   */
  public function getString(int $length = null): string;

  /**
   * @param int $count
   * @return int[]
   */
  public function getBytes(int $count = null): array;

  /**
   * @return int
   */
  public function getByte(): int;

  /**
   * @return string
   */
  public function getChar(): string;

  /**
   * @return int
   */
  public function length(): int;

  /**
   * @return bool
   */
  public function isEmpty(): bool;

  /**
   * @param string $buffer
   * @return $this
   */
  public function set(string $buffer) : \Mqtt\Protocol\Binary\IBuffer;

  /**
   * @return $this
   */
  public function reset()  : IBuffer;

  /**
   * @param string $encodedData
   * @return $this
   */
  public function append(string $encodedData) : \Mqtt\Protocol\Binary\IBuffer;

  /**
   * @return string
   */
  public function __toString() : string;

}

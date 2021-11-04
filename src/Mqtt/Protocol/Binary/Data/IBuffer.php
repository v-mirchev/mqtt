<?php

namespace Mqtt\Protocol\Binary\Data;

interface IBuffer extends \IteratorAggregate {

  /**
   * @param int $length
   * @return \Mqtt\Protocol\Binary\Data\IBuffer
   */
  public function get(int $length = null) : \Mqtt\Protocol\Binary\Data\IBuffer;

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
  public function set(string $buffer) : \Mqtt\Protocol\Binary\Data\IBuffer;

  /**
   * @return $this
   */
  public function reset()  : IBuffer;

  /**
   * @param string $encodedData
   * @return $this
   */
  public function append(string $encodedData) : \Mqtt\Protocol\Binary\Data\IBuffer;

  /**
   * @return string
   */
  public function __toString() : string;

}

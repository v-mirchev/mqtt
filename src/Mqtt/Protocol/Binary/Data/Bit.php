<?php

namespace Mqtt\Protocol\Binary\Data;

class Bit {

  /**
   * @var \Mqtt\Protocol\Binary\Data\IUint
   */
  protected $uint;

  /**
   * @param \Mqtt\Protocol\Binary\Data\IUint $uint
   * @return \Mqtt\Protocol\Binary\Data\Bit
   */
  public function uint(\Mqtt\Protocol\Binary\Data\IUint $uint) : \Mqtt\Protocol\Binary\Data\Bit {
    $this->uint = $uint;
    return $this;
  }

  /**
   * @param int|string $uint
   * @return $this
   */
  public function set($uint) : \Mqtt\Protocol\Binary\Data\Bit {
    $this->uint = $uint instanceof \Mqtt\Protocol\Binary\Data\IUint ? $uint : $this->uint->set($uint);
    return $this;
  }

  /**
   * @return int
   */
  public function get(): int {
    return $this->uint->get();
  }

  /**
   * @param int $bit
   * @param type $bitValue
   * @return void
   */
  public function setBit(int $bit, $bitValue) : \Mqtt\Protocol\Binary\Data\Bit {
    $this->uint->set($bitValue ? $this->get() | (1 << $bit) : $this->get() & ~(1 << $bit));
    return $this;
  }

  /**
   * @param int $bit
   * @return int
   */
  public function getBit(int $bit) : int {
    return (bool)($this->get() & (1 << $bit));
  }

  /**
   * @param int $startBit
   * @param int $endBit
   * @return int
   */
  public function getSub(int $startBit, int $endBit) : \Mqtt\Protocol\Binary\Data\IUint {
    return (clone $this->uint)->set((int)(($this->get() & ((1 << ($endBit + 1)) - 1)) >> $startBit));
  }

  /**
   * @param int $startBit
   * @param int $endBit
   * @param int|\Mqtt\Protocol\Binary\Data\IUint $uint
   * @return \Mqtt\Protocol\Binary\Data\Bit
   */
  public function setSub(int $startBit, int $endBit, $uint) : \Mqtt\Protocol\Binary\Data\Bit {
    $uintValue = $uint instanceof \Mqtt\Protocol\Binary\Data\IUint ? $uint->get() : (int)$uint;
    $mask = (int)(((1 << ($endBit + 1)) - 1) >> $startBit) << $startBit;
    $shiftedValue = (($uintValue << $startBit) & $mask);
    $this->uint->set($this->uint->get() & (~$mask));
    $this->uint->set($this->uint->get() | $shiftedValue);
    return $this;
  }

  /**
   * @return string
   */
  public function __toString() : string {
    return (string)$this->uint;
  }

}

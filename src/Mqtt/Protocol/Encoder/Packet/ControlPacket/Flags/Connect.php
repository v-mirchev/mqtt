<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Encoder\Packet\ControlPacket\Flags;

class Connect {

  /**
   * @var \Mqtt\Protocol\Binary\Data\Uint8
   */
  protected $flags;

  const BIT_CLEAN_SESSION = 1;
  const BIT_WILL = 2;
  const BIT_WILL_QOS_LS = 3;
  const BIT_WILL_QOS_MS = 4;
  const BIT_WILL_REATIN = 5;
  const BIT_PASSWORD = 6;
  const BIT_USERNAME = 7;

  /**
   * @var bool
   */
  protected $useCleanSession;

  public function __construct(\Mqtt\Protocol\Binary\Data\Uint8 $byte) {
    $this->flags = clone $byte;
  }

  /**
   * @param bool $useUsername
   */
  public function useUsername(bool $useUsername = true) {
    $this->flags->bits()->setBit(static::BIT_USERNAME, $useUsername);
  }

  /**
   * @param bool $usePassword
   */
  public function usePassword(bool $usePassword = true) {
    $this->flags->bits()->setBit(static::BIT_PASSWORD, $usePassword);
  }

  /**
   * @param bool $willRetain
   */
  public function setWillRetain(bool $willRetain = true) {
    $this->flags->bits()->setBit(static::BIT_WILL_REATIN, $willRetain);
  }

  /**
   * @param int $willQoS
   */
  public function setWillQoS(int $willQoS = 0) {
    $this->flags->bits()->setSub(static::BIT_WILL_QOS_LS, static::BIT_WILL_QOS_MS, $willQoS);
  }

  /**
   * @param bool $will
   */
  public function useWill(bool $will = true) {
    $this->flags->bits()->setBit(static::BIT_WILL, $will);
  }

  /**
   * @param bool $cleanSession
   */
  public function useCleanSession(bool $cleanSession = true) {
    $this->flags->bits()->setBit(static::BIT_CLEAN_SESSION, $cleanSession);
  }

  /**
   * @return int
   */
  public function get() : int {
    return $this->flags->bits()->get();
  }

  public function reset() {
    $this->flags->bits()->set(0);
  }

  public function __clone() {
    $this->flags = clone $this->flags;
  }

}

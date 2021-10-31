<?php

namespace Mqtt\Protocol\Packet\Type;

class Connect implements \Mqtt\Protocol\Packet\IType {

  use \Mqtt\Protocol\Packet\Type\TType;

  /**
   * @var \Mqtt\Entity\Configuration\Session
   */
  protected $sessionParameters;

  /**
   * @var \Mqtt\Protocol\Packet\Type\Flags\Connect
   */
  protected $flags;

  /**
   * @param \Mqtt\Entity\Configuration\Session $sessionParameters
   * @param \Mqtt\Protocol\Packet\Type\Flags\Connect $flags
   */
  public function __construct(
    \Mqtt\Entity\Configuration\Session $sessionParameters,
    \Mqtt\Protocol\Packet\Type\Flags\Connect $flags
  ) {
    $this->sessionParameters = $sessionParameters;
    $this->flags = $flags;
  }

  public function encode(\Mqtt\Protocol\Binary\Frame $frame) {
    $this->flags->reset();

    $this->flags->useCleanSession(!$this->sessionParameters->isPersistent);
    if ($this->sessionParameters->useWill) {
      $this->flags->useWill();
      $this->flags->setWillQoS($this->sessionParameters->will->qos->qos);
      $this->flags->setWillRetain($this->sessionParameters->will->isRetain);
    }

    if ($this->sessionParameters->useAuthentication) {
      $this->flags->useUsername();
    }
    if ($this->sessionParameters->useAuthentication) {
      $this->flags->usePassword();
    }

    $frame->setPacketType(\Mqtt\Protocol\Packet\IType::CONNECT);
    $frame->setReserved(0x0);
    $frame->addString($this->sessionParameters->protocol->protocol);
    $frame->addByte($this->sessionParameters->protocol->version);
    $frame->addByte($this->flags->get());
    $frame->addWord($this->sessionParameters->keepAliveInterval);
    $frame->addString($this->sessionParameters->clientId);

    if ($this->sessionParameters->useWill) {
      $frame->addString($this->sessionParameters->will->topic);
      $frame->addString($this->sessionParameters->will->content);
    }

    if ($this->sessionParameters->useAuthentication) {
      $frame->addString($this->sessionParameters->authentication->username);
    }
    if ($this->sessionParameters->useAuthentication) {
      $frame->addString($this->sessionParameters->authentication->password);
    }

  }

  /**
   * @param int $packetId
   * @return bool
   */
  public function is(int $packetId): bool {
    return \Mqtt\Protocol\Packet\IType::CONNECT === $packetId;
  }

}

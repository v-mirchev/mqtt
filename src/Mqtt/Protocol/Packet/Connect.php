<?php

namespace Mqtt\Protocol\Packet;

class Connect {

  use \Mqtt\Protocol\Packet\TPacket;

  /**
   * @var \Mqtt\Entity\Configuration\Session
   */
  protected $sessionParameters;

  /**
   * @var \Mqtt\Protocol\Binary\Flags\Connect
   */
  protected $flags;

  /**
   * @param \Mqtt\Entity\Configuration\Session $sessionParameters
   * @param \Mqtt\Protocol\Binary\Flags\Connect $flags
   */
  public function __construct(
    \Mqtt\Entity\Configuration\Session $sessionParameters,
    \Mqtt\Protocol\Binary\Flags\Connect $flags
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

    $frame->setPacketType(\Mqtt\Protocol\IPacket::CONNECT);
    $frame->addVariableHeader($this->sessionParameters->protocol->protocol);
    $frame->addVariableHeaderByte($this->sessionParameters->protocol->version);
    $frame->addVariableHeaderByte($this->flags->get());
    $frame->addVariableHeaderIdentifier($this->sessionParameters->keepAliveInterval);
    $frame->addVariableHeader($this->sessionParameters->clientId);

    if ($this->sessionParameters->useWill) {
      $frame->addVariableHeader($this->sessionParameters->will->topic);
      $frame->addVariableHeader($this->sessionParameters->will->content);
    }

    if ($this->sessionParameters->useAuthentication) {
      $frame->addVariableHeader($this->sessionParameters->authentication->username);
    }
    if ($this->sessionParameters->useAuthentication) {
      $frame->addVariableHeader($this->sessionParameters->authentication->password);
    }

  }

}
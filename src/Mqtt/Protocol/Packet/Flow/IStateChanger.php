<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Packet\Flow;

interface IStateChanger {

  public function setState(string $sessionState) : void;

}

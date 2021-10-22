<?php

namespace Mqtt\Protocol\Packet\Flow;

interface IStateChanger {

  public function setState(string $sessionState) : void;

}

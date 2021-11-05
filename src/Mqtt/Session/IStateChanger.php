<?php declare(strict_types = 1);

namespace Mqtt\Session;

interface IStateChanger {

  public function setState(string $sessionState) : void;

}

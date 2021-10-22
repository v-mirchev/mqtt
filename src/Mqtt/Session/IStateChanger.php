<?php

namespace Mqtt\Session;

interface IStateChanger {

  public function setState(string $sessionState) : void;

}

<?php

namespace Mqtt\Session;

interface ISessionStateChanger {

  public function setState(string $sessionState) : void;

}

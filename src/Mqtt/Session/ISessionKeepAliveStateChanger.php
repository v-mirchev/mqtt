<?php

namespace Mqtt\Session;

interface ISessionKeepAliveStateChanger {

 public function setKeepAliveState(string $sessionState) : void;

}

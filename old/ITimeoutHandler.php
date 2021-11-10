<?php declare(strict_types = 1);

namespace Mqtt;

interface ITimeoutHandler  {

  public function onTimeout() : void;

}

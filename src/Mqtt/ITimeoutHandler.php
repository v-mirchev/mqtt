<?php

namespace Mqtt;

interface ITimeoutHandler  {

  public function onTimeout() : void;

}

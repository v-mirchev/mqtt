<?php

namespace Mqtt\Session;

interface ISession extends \Mqtt\Protocol\IHandler  {

  public function start() : void;

  public function stop() : void;

  public function publish() : void;

  public function subscribe() : void;

  public function unsubscribe() : void;

}

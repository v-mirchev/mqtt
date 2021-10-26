<?php

namespace Mqtt;

interface IConsumer  {

  public function onStart(\Mqtt\Client\Client $client) : void;
  public function onStop(\Mqtt\Client\Client $client) : void;
  public function onTick(\Mqtt\Client\Client $client) : void;

}

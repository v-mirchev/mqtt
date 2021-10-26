<?php declare(ticks = 1);

set_time_limit(0);

/* @var $container \DI\Container */
$container = require_once __DIR__ . '/bootstrap/bootstrap.php';


$consumer = new class implements \Mqtt\IConsumer {

  public function onStart(\Mqtt\Client\Client $client): void {
    $client->subscription()->
      atMostOnce()->
      topic('tele/tasmota_switch/STATE')->
      handler(new class implements \Mqtt\Client\Handler\ISubscription {
        public function onAcknowledged(\Mqtt\Entity\Topic $topic): void { error_log('ACK' . $topic->name); }
        public function onFailed(\Mqtt\Entity\Topic $topic): void {}
        public function onSubscribed(\Mqtt\Entity\Topic $topic): void {}
        public function onUnacknowledged(\Mqtt\Entity\Topic $topic): void {}
    });
    $client->subscribe();
  }

  public function onStop(\Mqtt\Client\Client $client): void {
  }

  public function onTick(\Mqtt\Client\Client $client): void {
  }

};

/* @var $client \Mqtt\Client\Client */
$client = $container->get(\Mqtt\Client\Client::class);
$client->setConsumer($consumer);
$client->start();


//
//$client->
//  onDisconnectBehaviour()->
//  keepReconnecting();
//// OR $client->
////  onDisconnectBehaviour()->
////  stayDisconnected();
//
//$client->
//  supscription()->
//    exacltyOnce()->
//      topic()->
//      onMessageReceived()->
//    subscribe();
//
//$client->
//  publisher()->
//    atMostOnce()->
//      topic()->
//      message()->
//      // onMessageSendingFailed()->
//    publish();
//
//$client->
//  publisher()->
//    atLeastOnce()->
//      topic()->
//      message()->
//      // onMessageSendingFailed()->
//      onMessageDeliveryFailed()->
//    publish();
//
//$client->
//  publisher()->
//    exactlyOnce()->
//      topic()->
//      message()->
//      // onMessageSendingFailed()->
//      onMessageDeliveryFailed()->
//      onMessageReleaseFailed()->
//    publish();
//
//$client->
//  subscription()->
//    exactlyOnce()->
//      topic()->
//       onSubscriptionFailed()->
//      onMessage()
//  subscription()->
//    exactlyOnce()->
//      topic()->
//       onSubscriptionFailed()->
//      onMessage()
//  subscribe();
//

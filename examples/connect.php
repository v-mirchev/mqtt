<?php declare(ticks = 1);

set_time_limit(0);

/* @var $container \DI\Container */
$container = require_once __DIR__ . '/bootstrap/bootstrap.php';


/* @var $client \Mqtt\Client */
$client = $container->get(\Mqtt\Client::class);
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

$client->start();

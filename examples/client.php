<?php declare(ticks = 1);

set_time_limit(0);

/* @var $container \DI\Container */
$container = require_once __DIR__ . '/bootstrap/bootstrap.php';


$consumer = new class implements \Mqtt\IConsumer, \Mqtt\ITimeoutHandler {

  /**
   * @var \Mqtt\Timeout
   */
  protected $timeout;

  /**
   * @var \Mqtt\Client\IClient
   */
  protected $client;

  /**
   * @var \Mqtt\Entity\Subscription
   */
  protected $subscription;

  public function __construct() {
    $this->timeout = (new \Mqtt\Timeout())->setInterval(3)->subscribe($this);
  }

  public function onStart(\Mqtt\Client\Client $client): void {
    $this->client = $client;

    $this->subscription = $client->subscription()->
      exactlyOnce()->
      topicFilter('test/topic/1')->
      handler(new class implements \Mqtt\Client\Handler\ISubscription {
        public function onSubscribeAcknowledged(\Mqtt\Entity\TopicFilter $topic): void { error_log('ACK' . $topic->filter); }
        public function onSubscribeFailed(\Mqtt\Entity\TopicFilter $topic): void {}
        public function onSubscribed(\Mqtt\Entity\TopicFilter $topic): void {}
        public function onSubscribeUnacknowledged(\Mqtt\Entity\TopicFilter $topic): void {}
        public function onUnsubscribeUnacknowledged(\Mqtt\Entity\TopicFilter $topic): void {}
        public function onUnsubscribeAcknowledged(\Mqtt\Entity\TopicFilter $topic): void {}
        public function onMessage(\Mqtt\Entity\Message $message): void {
          var_dump($message);
        }
    });
    $client->subscribe();
    $this->timeout->start();
  }

  public function onStop(\Mqtt\Client\Client $client): void {
  }

  public function onTick(\Mqtt\Client\Client $client): void {
    $this->timeout->tick();
  }

  public function onTimeout(): void {
    $this->timeout->stop();
    $this->client->message()->
      atMostOnce()->
      topic('/test/topic/1')->
      content('Publishing ...')->
      handler(new class implements Mqtt\Client\Handler\IMessage {

        public function onMessageAcknowledged(\Mqtt\Entity\Message $message): void {
        }

        public function onMessageSent(\Mqtt\Entity\Message $message): void {
        }

        public function onMessageUnacknowledged(\Mqtt\Entity\Message $message): void {
        }
    });
    $this->client->publish();

//    $this->timeout->stop();
//    $this->client->unsubscription($this->subscription)->
//      unsubscribe();
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

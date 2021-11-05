<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Decoder\Frame\Fsm;

class Context {

  /**
   * @var \Mqtt\Protocol\Decoder\Frame\ControlHeader
   */
  public $controlHeader;

  /**
   * @var \Mqtt\Protocol\Decoder\Frame\UintVariable
   */
  public $remainingLengthHeader;

  /**
   * @var \Mqtt\Protocol\Decoder\Frame\Payload
   */
  public $payload;

  /**
   * @var \Mqtt\Protocol\Decoder\Frame\Receiver
   */
  public $controlHeaderReceiver;

  /**
   * @var \Mqtt\Protocol\Decoder\Frame\Receiver
   */
  public $remainingLengthReceiver;

  /**
   * @var \Mqtt\Protocol\Decoder\Frame\Receiver
   */
  public $payloadReceiver;

  /**
   * @param \Mqtt\Protocol\Decoder\Frame\ControlHeader $controlHeader
   * @param \Mqtt\Protocol\Decoder\Frame\UintVariable $remainingLengthHeader
   * @param \Mqtt\Protocol\Decoder\Frame\Payload $payload
   */
  public function __construct(
    \Mqtt\Protocol\Decoder\Frame\ControlHeader $controlHeader,
    \Mqtt\Protocol\Decoder\Frame\UintVariable $remainingLengthHeader,
    \Mqtt\Protocol\Decoder\Frame\Payload $payload
  ) {
    $this->controlHeader = $controlHeader;
    $this->remainingLengthHeader = $remainingLengthHeader;
    $this->payload = $payload;

    $this->controlHeaderReceiver = $this->controlHeader->receiver();
    $this->remainingLengthReceiver = $this->remainingLengthHeader->receiver();
    $this->payloadReceiver = $this->payload->receiver();
  }

}

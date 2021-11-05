<?php declare(strict_types = 1);

namespace Mqtt\Protocol\Decoder\Frame\Fsm;

class Fsm implements \Mqtt\Protocol\Decoder\Frame\Fsm\IActions {

  /**
   * @var \Mqtt\Protocol\Decoder\Frame\Fsm\Context
   */
  protected $context;

  /**
   * @var \Mqtt\Protocol\Decoder\Frame\Fsm\Factory
   */
  protected $stateFactory;

  /**
   * @var \Mqtt\Protocol\Entity\Frame
   */
  protected $entityPrototype;

  /**
   * @var callable
   */
  protected $onFrameCompleted;

  /**
   * @var \Mqtt\Protocol\Decoder\Frame\State\IState
   */
  public $state;

  public function __construct(
    \Mqtt\Protocol\Decoder\Frame\Fsm\Context $context,
    \Mqtt\Protocol\Decoder\Frame\Fsm\Factory $stateFactory,
    \Mqtt\Protocol\Entity\Frame $entityPrototype
  ) {
    $this->context = $context;
    $this->stateFactory = $stateFactory;
    $this->entityPrototype = $entityPrototype;

    $this->onFrameCompleted = function (\Mqtt\Protocol\Entity\Frame $frame) {};
  }

  /**
   * @return void
   */
  public function start() : void {
    $this->controlHeaderReceiver = $this->context->controlHeader->receiver();
    $this->remainingLengthReceiver = $this->context->remainingLengthHeader->receiver();
    $this->payloadReceiver = $this->context->payload->receiver();

    $this->setState(\Mqtt\Protocol\Decoder\Frame\Fsm\State\IState::CONTROL_HEADER_PROCESSING);
  }

  /**
   * @param string $char
   * @return void
   */
  public function input(string $char) : void {
    $this->state->input($char);
  }

  /**
   * @param string $state
   * @return void
   */
  public function setState(string $state) : void {
    $this->state = $this->stateFactory->create($state);
    $this->state->setContext($this->context);
    $this->state->setAction($this);
    $this->state->onEnter();
  }

  /**
   * @return void
   */
  public function complete() : void {
    $entity = clone $this->entityPrototype;
    $entity->packetType = $this->context->controlHeader->getPacketType();
    $entity->flags = $this->context->controlHeader->getFlags();
    $entity->payload = $this->context->payload->get();

    $onFrameCompleted = $this->onFrameCompleted;
    $onFrameCompleted($entity);
  }

  /**
   * @param callable $onFrameCompleted
   */
  public function onCompleted(callable $onFrameCompleted) : void {
    $this->onFrameCompleted = $onFrameCompleted;
  }

}

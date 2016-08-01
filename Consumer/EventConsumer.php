<?php

namespace Ndewez\EventsBundle\Consumer;

use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use Ndewez\EventsBundle\Model\Event;
use Ndewez\EventsBundle\Service\Listener;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class EventConsumer.
 */
class EventConsumer extends AbstractConsumer
{
    /** @var Listener */
    private $listen;

    /** @var SerializerInterface */
    private $serializer;

    /**
     * @param Listener $listen
     */
    public function __construct(Listener $listen)
    {
        $this->listen = $listen;
        $this->serializer = SerializerBuilder::create()->build();
    }

    /**
     * {@inheritdoc}
     */
    public function execute(AMQPMessage $amqpMessage)
    {
        // Ack message and get event
        $amqpMessage->delivery_info['channel']->basic_ack($amqpMessage->delivery_info['delivery_tag']);

        /** @var Event $event */
        $event = $this->serializer->deserialize(
            $amqpMessage->getBody(),
            'Ndewez\EventsBundle\Model\Event',
            'json'
        );

        // Process
        $this->listen->listen($event);
    }
}

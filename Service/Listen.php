<?php

namespace Ndewez\EventsBundle\Service;

use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use Ndewez\EventsBundle\EventDispatcher\Event\NdewezEvent;
use Ndewez\EventsBundle\Model\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class Listen.
 */
class Listen
{
    /** @var EventDispatcherInterface */
    private $dispatcher;

    /** @var SerializerInterface */
    private $serializer;

    /** @var array */
    private $events;

    /**
     * @param EventDispatcherInterface $dispatcher
     * @param array                    $events
     */
    public function __construct(EventDispatcherInterface $dispatcher, array $events)
    {
        $this->dispatcher = $dispatcher;
        $this->events = $events;
        $this->serializer = SerializerBuilder::create()->build();
    }

    /**
     * @param Event $event
     */
    public function listen(Event $event)
    {
        if (!in_array($event->getTitle(), $this->events, true)) {
            return;
        }

        $payload = $this->serializer->deserialize($event->getPayload(), $event->getNamespace(), 'json');
        $ndewezEvent = new NdewezEvent($payload);

        $this->dispatcher->dispatch(
            sprintf('ndewez_events.%s', $event->getTitle()),
            $ndewezEvent
        );
    }
}

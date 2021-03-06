<?php

namespace Ndewez\EventsBundle\Service;

use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use Ndewez\EventsBundle\Connector\ConnectorInterface;
use Ndewez\EventsBundle\Exception\LogicException;
use Ndewez\EventsBundle\Model\Event;

/**
 * Class Publisher.
 */
class Publisher
{
    /** @var ConnectorInterface */
    private $connector;

    /** @var SerializerInterface */
    private $serializer;

    /**
     * @param ConnectorInterface  $connector
     */
    public function __construct(ConnectorInterface $connector)
    {
        $this->serializer = SerializerBuilder::create()->build();
        $this->connector = $connector;
    }

    /**
     * @param string $title
     * @param object $object
     */
    public function publish($title, $object)
    {
        $event = $this->buildEvent($title, $object);
        $this->connector->publish($event);
    }

    /**
     * @param string $title
     * @param object $object
     *
     * @return Event
     *
     * @throws LogicException
     */
    private function buildEvent($title, $object)
    {
        if (!is_object($object)) {
            throw new LogicException(sprintf('Connector only allows publish objects, "%s" given', gettype($object)));
        }

        $event = new Event();
        $event
            ->setTitle($title)
            ->setNamespace(get_class($object))
            ->setPayload($this->serializer->serialize($object, 'json'))
        ;

        return $event;
    }
}

<?php

namespace Ndewez\EventsBundle\Connector;

use GuzzleHttp\Client;
use JMS\Serializer\SerializerInterface;
use Ndewez\EventsBundle\Model\Event;

/**
 * Class MockConnector.
 */
class MockConnector implements ConnectorInterface
{
    /**
     * {@inheritdoc}
     */
    public function publish(Event $event)
    {
        return;
    }
}

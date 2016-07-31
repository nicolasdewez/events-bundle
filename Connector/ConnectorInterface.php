<?php

namespace Ndewez\EventsBundle\Connector;

use Ndewez\EventsBundle\Model\Event;

/**
 * Interface ConnectorInterface.
 */
interface ConnectorInterface
{
    const MODE_SYNCH = 'synch';
    const MODE_ASYNCH = 'asynch';

    /**
     * @param Event $event
     */
    public function publish(Event $event);
}

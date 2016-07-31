<?php

namespace Ndewez\EventsBundle\Connector;

/**
 * Interface AmqpConnectorInterface.
 */
interface AmqpConnectorInterface extends ConnectorInterface
{
    /**
     * @param array $callback
     */
    public function listen(array $callback);
}

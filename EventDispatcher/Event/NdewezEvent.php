<?php

namespace Ndewez\EventsBundle\EventDispatcher\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class NdewezEvent.
 */
class NdewezEvent extends Event
{
    /** @var object */
    private $payload;

    /**
     * @param object $payload
     */
    public function __construct($payload)
    {
        $this->payload = $payload;
    }

    /**
     * @return object $payload
     */
    public function getPayload()
    {
        return $this->payload;
    }
}

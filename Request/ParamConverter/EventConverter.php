<?php

namespace Ndewez\EventsBundle\Request\ParamConverter;

use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use Ndewez\EventsBundle\Model\Event;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class EventConverter.
 */
class EventConverter implements ParamConverterInterface
{
    /** @var SerializerInterface */
    private $serializer;

    public function __construct()
    {
        $this->serializer = SerializerBuilder::create()->build();
    }

    /**
     * {@inheritdoc}
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        $json = $request->getContent();

        /** @var Event $event */
        $event = $this->serializer->deserialize($json, 'Ndewez\EventsBundle\Model\Event', 'json');

        $request->attributes->set('event', $event);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ParamConverter $configuration)
    {
        return 'events' === $configuration->getName();
    }
}

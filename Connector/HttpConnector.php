<?php

namespace Ndewez\EventsBundle\Connector;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use JMS\Serializer\SerializerInterface;
use Ndewez\EventsBundle\Exception\PublishException;
use Ndewez\EventsBundle\Model\Event;

/**
 * Class HttpConnector.
 */
class HttpConnector implements ConnectorInterface
{
    const PUBLISH_URL = '/api/publish';

    /** @var Client */
    private $client;

    /** @var SerializerInterface */
    private $serializer;

    /**
     * @param SerializerInterface $serializer
     * @param string              $host
     */
    public function __construct(SerializerInterface $serializer, $host)
    {
        $this->client = new Client(['base_uri' => $host]);
        $this->serializer = $serializer;
    }

    /**
     * {@inheritdoc}
     */
    public function publish(Event $event)
    {
        try  {
            $this->client->post(self::PUBLISH_URL, [
                'body' => $this->serializer->serialize($event, 'json'),
            ]);
        } catch(BadResponseException $exception) {
            throw new PublishException($exception->getMessage());
        }
    }
}

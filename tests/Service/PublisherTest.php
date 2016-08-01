<?php

namespace Tests\Ndewez\EventsBundle\Service;

use Ndewez\EventsBundle\Connector\MockConnector;
use Ndewez\EventsBundle\Model\Event;
use Ndewez\EventsBundle\Service\Publisher;
use Tests\Ndewez\EventsBundle\Model\MyModel;

class PublisherTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Ndewez\EventsBundle\Exception\LogicException
     */
    public function testBuildEventWithNoObject()
    {
        $publisher = new Publisher(new MockConnector());

        $class = new \ReflectionClass($publisher);
        $method = $class->getMethod('buildEvent');
        $method->setAccessible(true);

        $method->invokeArgs($publisher, ['title', 'object']);
    }

    public function testBuildEvent()
    {
        $expected = new Event();
        $expected
            ->setTitle('title')
            ->setNamespace('Tests\\Ndewez\\EventsBundle\\Model\\MyModel')
            ->setPayload('{"field1":"field1","field2":"field2"}')
        ;

        $publisher = new Publisher(new MockConnector());

        $class = new \ReflectionClass($publisher);
        $method = $class->getMethod('buildEvent');
        $method->setAccessible(true);

        $object = new MyModel();
        $object
            ->setField1('field1')
            ->setField2('field2')
        ;

        $event = $method->invokeArgs($publisher, ['title', $object]);
        $this->assertEquals($expected, $event);
    }

    public function testPublish()
    {
        $publisher = new Publisher(new MockConnector());

        $object = new MyModel();
        $object
            ->setField1('field1')
            ->setField2('field2')
        ;

        $publisher->publish('myEvent', $object);
    }
}

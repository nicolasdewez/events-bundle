<?php

namespace Tests\Ndewez\EventsBundle\Service;

use Ndewez\EventsBundle\EventDispatcher\Event\NdewezEvent;
use Ndewez\EventsBundle\Model\Event;
use Ndewez\EventsBundle\Service\Listener;

class ListenerTest extends \PHPUnit_Framework_TestCase
{
    public function testListenEventUnknown()
    {
        $dispatcher = $this
            ->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
            ->getMock()
        ;

        $dispatcher
            ->expects($this->never())
            ->method('dispatch')
        ;

        $listener = new Listener($dispatcher, []);

        $event = new Event();
        $event->setTitle('myEvent');

        $listener->listen($event);
    }

    public function testListen()
    {
        $event = new Event();
        $event->setTitle('title');
        $ndewezEvent = new NdewezEvent($event);


        $dispatcher = $this
            ->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
            ->getMock()
        ;

        $dispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with('ndewez_events.myEvent', $this->equalTo($ndewezEvent))
        ;

        $listener = new Listener($dispatcher, ['myEvent']);


        $eventArg = new Event();
        $eventArg
            ->setTitle('myEvent')
            ->setNamespace('Ndewez\\EventsBundle\\Model\\Event')
            ->setPayload('{"title":"title"}')
        ;

        $listener->listen($eventArg);
    }
}

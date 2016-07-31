<?php

namespace Ndewez\EventsBundle\Controller;

use Ndewez\EventsBundle\Model\Event;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ListenController.
 */
class ListenController extends Controller
{
    /**
     * @param Event $event
     *
     * @return Response
     *
     * @ParamConverter("events")
     */
    public function indexAction(Event $event)
    {
        $this->get('ndewez.events.listen')->listen($event);

        return new Response();
    }
}

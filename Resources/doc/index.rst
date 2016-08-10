Using NdewezEventsBundle
========================

Welcome to NdewezEventsBundle.


Installation
------------

Step 1: Download the Bundle
~~~~~~~~~~~~~~~~~~~~~~~~~~~

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

.. code-block:: bash

    $ composer require nicolasdewez/events-bundle "^1.0"

This command requires you to have Composer installed globally, as explained
in the `installation chapter`_ of the Composer documentation.


Step 2: Enable the Bundle
~~~~~~~~~~~~~~~~~~~~~~~~~

Then, enable the bundle by adding the following line in the ``app/AppKernel.php``
file of your project:

.. code-block:: php

    // app/AppKernel.php

    // ...
    class AppKernel extends Kernel
    {
        public function registerBundles()
        {
            $bundles = array(
                // ...

                new Ndewez\EventsBundle\NdewezEventsBundle(),
            );

            // ...
        }

        // ...
    }


Step 4: Configure the bundle
~~~~~~~~~~~~~~~~~~~~~~~~~~~~

The bundle comes with a sensible default configuration, which is listed below.
If you skip this step, these defaults will be used.

.. configuration-block::

    .. code-block:: yaml

        # app/config/config.yml
        ndewez_events:
            # synch or asynch
            mode: asynch
            # Events to listen
            events: ["myEvent"]
            connection:
                # Queuing host for asynchronous mode, base url of events application for synchronous mode
                host: "localhost"
                # Just used for asynchronous mode
                port: "5672"
                # Just used for asynchronous mode
                user: "guest"
                # Just used for asynchronous mode
                password: "guest"
                # Just used for asynchronous mode: name of queue
                application_code: "APP"


Two modes are available:

* asynchronous
* synchronous


If synchronous mode is used:

* it's necessary to include routing file.
* url ``/events/listen`` should be accessible

.. configuration-block::

    .. code-block:: yaml
        ndewez_events:
            resource: "@NdewezEventsBundle/Resources/config/routing.yml"


In asynchronous mode, a command is available in order to consume messages to server. You can execute one or most times.

.. code-block:: sh

    bin/console ndewez_events:consumer:event



Listen an event
---------------

For execute actions when an event is listen, it's very simple:

* create a service which listen event
* configure service into service file


.. code-block:: php

    // src/AppBundle/EventListener/TestSubscriber.php
    namespace AppBundle\EventListener;

    use Ndewez\EventsBundle\EventDispatcher\Event\NdewezEvent;
    use Symfony\Component\EventDispatcher\EventSubscriberInterface;

    class TestSubscriber implements EventSubscriberInterface
    {
        /**
         * {@inheritdoc}
         */
        public static function getSubscribedEvents()
        {
            return [
                // Prefix "ndewez_events." is used by symfony dispatcher
                'ndewez_events.myEvent' => [['myEvent']],
            ];
        }

        /**
         * @param NdewezEvent $event
         */
        public function myEvent(NdewezEvent $event)
        {
            /** @var object $payload */
            $payload = $event->getPayload();

            // $payload will be typed by initial class
        }
    }


.. code-block:: xml

    <!-- services.xml -->
    <service id="app.subscriber.test" class="AppBundle\EventListener\TestSubscriber">
        <tag name="kernel.event_subscriber" /> <!-- Just tag service -->
    </service>


Publish an event
----------------

For publish an event, the service ndewez.events.publisher can be used.

.. code-block:: php

    // src/AppBundle/Controller/PublishController.php
    $object = new MyClass();
    $object
        ->setTitle('Title')
        ->setPrice(12)
    ;

    $this->get('ndewez.events.publisher')->publish('myEvent', $event);


With this code, an event ``myEvent`` will be sent to events application which transfer to application(s).


Improvements
------------

* In mode synchronous: security with token
* Serialize attribute of object: null or initialize

<?php

namespace Ndewez\EventsBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class EventConsumerCommand.
 */
class EventConsumerCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('ndewez_events:consumer:event')
            ->setDescription('Run an event consumer');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getContainer()->get('ndewez.events.consumer.event')->run();
    }
}

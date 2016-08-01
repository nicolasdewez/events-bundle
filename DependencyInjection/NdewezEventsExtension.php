<?php

namespace Ndewez\EventsBundle\DependencyInjection;

use Ndewez\EventsBundle\Connector\ConnectorInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * Class NdewezEventsExtension.
 */
class NdewezEventsExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('connectors.xml');
        $loader->load('services.xml');

        // Listen service
        $container
            ->getDefinition('ndewez.events.listener')
            ->addArgument($config['events'])
        ;

        // Connector mock
        if (true === $config['mock']) {
            $this->setConnector($container, $container->getDefinition('ndewez.events.connector.mock'));

            return;
        }

        if (!isset($config['connection'])) {
            return;
        }

        // Connector synchronous
        if (ConnectorInterface::MODE_SYNCH === $config['mode']) {
            $connector = $container->getDefinition('ndewez.events.connector.http');
            $connector->replaceArgument(0, $config['connection']['host']);

            $this->setConnector($container, $connector);

            return;
        }

        // Connector asynchronous
        $connector = $container->getDefinition('ndewez.events.connector.amqp');
        $connector
            ->replaceArgument(0, $config['connection']['host'])
            ->replaceArgument(1, $config['connection']['port'])
            ->replaceArgument(2, $config['connection']['user'])
            ->replaceArgument(3, $config['connection']['password'])
            ->replaceArgument(4, $config['connection']['application_code'])
        ;

        $this->setConnector($container, $connector);
    }

    /**
     * @param ContainerBuilder $container
     * @param Definition       $connector
     */
    private function setConnector(ContainerBuilder $container, Definition $connector)
    {
        $container->getDefinition('ndewez.events.publisher')
            ->addArgument($connector)
        ;
    }
}

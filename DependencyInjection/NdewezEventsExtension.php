<?php

namespace Ndewez\EventsBundle\DependencyInjection;

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

        if (true === $config['mock']) {
            $this->setConnector($container, $container->getDefinition('ndewez.events.connector.mock'));

            return;
        }

        if ('synchronous' === $config['mode']) {
            $connector = $container->getDefinition('ndewez.events.connector.http');
            $connector->replaceArgument(1, $config['host']);

            $this->setConnector($container, $connector);

            return;
        }

        // asynchronous --> change connector
        $this->setConnector($container, $container->getDefinition('ndewez.events.connector.http'));
    }

    private function setConnector(ContainerBuilder $container, Definition $connector)
    {
        $container->getDefinition('ndewez.events.publisher')
            ->addArgument($connector)
        ;
    }
}

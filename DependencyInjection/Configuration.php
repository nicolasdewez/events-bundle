<?php

namespace Ndewez\EventsBundle\DependencyInjection;

use Ndewez\EventsBundle\Connector\ConnectorInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration.
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('events');

        $rootNode
            ->children()
                ->booleanNode('mock')
                    ->defaultFalse()
                ->end()
                ->scalarNode('mode')
                    ->validate()
                    ->ifNotInArray([ConnectorInterface::MODE_SYNCH, ConnectorInterface::MODE_ASYNCH])
                        ->thenInvalid('Invalid mode "%s"')
                    ->end()
                    ->defaultValue(ConnectorInterface::MODE_SYNCH)
                ->end()
                ->arrayNode('events')
                    ->defaultValue(array())
                    ->prototype('scalar')->end()
                ->end()
                ->arrayNode('connection')
                    ->children()
                        ->scalarNode('host')
                            ->defaultValue('localhost')
                        ->end()
                        ->scalarNode('port')
                            ->defaultValue('5672')
                        ->end()
                        ->scalarNode('user')
                            ->defaultValue('guest')
                        ->end()
                        ->scalarNode('password')
                            ->defaultValue('guest')
                        ->end()
                        ->scalarNode('application_code')
                            ->defaultValue('XXX')
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}

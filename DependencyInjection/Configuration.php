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
                ->booleanNode('mock')->defaultFalse()->end()
                ->scalarNode('mode')
                    ->isRequired()
                    ->validate()
                    ->ifNotInArray([ConnectorInterface::MODE_SYNCH, ConnectorInterface::MODE_ASYNCH])
                        ->thenInvalid('Invalid mode "%s"')
                    ->end()
                ->end()
                ->scalarNode('host')->defaultNull()->end()
            ->end();

        return $treeBuilder;
    }
}

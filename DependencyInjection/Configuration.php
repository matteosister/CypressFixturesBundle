<?php

namespace Cypress\FixturesBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('cypress_fixtures');

        $rootNode
            ->children()
                ->arrayNode('staleness')
                    ->children()
                        ->scalarNode('type')->isRequired()->end()
                        ->scalarNode('path')->end()
                    ->end()
                ->end()
                ->arrayNode('fixtures')
                    ->prototype('scalar')->end()
                ->end()
                ->arrayNode('watches')
                    ->prototype('scalar')->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}

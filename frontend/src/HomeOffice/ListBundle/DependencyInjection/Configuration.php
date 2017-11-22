<?php

namespace HomeOffice\ListBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see:
 * {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('home_office_list');
     
        $rootNode
            ->children()
                ->scalarNode('cache_timeout_seconds')
                    ->defaultValue('3600')
                    ->end()
                ->arrayNode('list_definitions')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('name')
                                ->end()
                            ->scalarNode('type')
                                ->end()
                            ->scalarNode('alfresco_list_name')
                                ->end()
                            ->scalarNode('file_name')
                                ->end()
                            ->scalarNode('storage')
                                ->end()
                            ->scalarNode('retrieve_method_name')
                                ->end()
                            ->scalarNode('prepare_method_name')
                                ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}

<?php

namespace HomeOffice\AlfrescoApiBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

// @codingStandardsIgnoreStart
/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
// @codingStandardsIgnoreEnd
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('home_office_alfresco_api');
     
        $rootNode
            ->children()
                ->scalarNode('endpoint')
                    ->defaultValue('https://hocs-alfresco.hocs-dev.svc.cluster.local:443/alfresco/')
                    ->end()
                ->scalarNode('list_endpoint')
                    ->defaultValue('https://hocs-alfresco.hocs-dev.svc.cluster.local:443/alfresco/')
                    ->end()
                ->scalarNode('workspace')
                    ->defaultValue('workspace')
                    ->end()
                ->scalarNode('store')
                    ->defaultValue('SpacesStore')
                    ->end()
                ->scalarNode('alfresco_qname_prefix')
                    ->defaultValue('http://cts-beta.homeoffice.gov.uk/model/content/1.0')
                    ->end()
                ->scalarNode('cts_namespace')
                    ->defaultValue('cts')
                    ->end()
                ->scalarNode('cts_case_object_type_id')
                    ->defaultValue('F:cts:case')
                    ->end()
                ->arrayNode('cts_case_properties')
                    ->useAttributeAsKey('alfresco_prop_name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('object_prop_name')
                                ->end()
                            ->scalarNode('alfresco_prop_name')
                                ->end()
                            ->scalarNode('query_name')
                                ->end()
                            ->scalarNode('type')
                                ->end()
                            ->booleanNode('system_field')
                                ->end()
                            ->booleanNode('required_for_case_queue')
                                ->end()
                            ->booleanNode('required_for_search_results')
                                ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('cts_case_permissions')
                    ->useAttributeAsKey('alfresco_prop_name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('object_prop_name')
                                ->end()
                            ->scalarNode('alfresco_prop_name')
                                ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('cts_case_document_properties')
                    ->useAttributeAsKey('alfresco_prop_name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('object_prop_name')
                                ->end()
                            ->scalarNode('alfresco_prop_name')
                                ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('cts_case_document_template_properties')
                    ->useAttributeAsKey('alfresco_prop_name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('object_prop_name')
                                ->end()
                            ->scalarNode('alfresco_prop_name')
                                ->end()
                            ->scalarNode('type')
                                ->end()
                            ->booleanNode('required_for_update')
                                ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('cts_case_standard_line_properties')
                    ->useAttributeAsKey('alfresco_prop_name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('object_prop_name')
                                ->end()
                            ->scalarNode('alfresco_prop_name')
                                ->end()
                            ->scalarNode('type')
                                ->end()
                            ->booleanNode('required_for_update')
                                ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('cts_help_document_properties')
                    ->useAttributeAsKey('alfresco_prop_name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('object_prop_name')
                                ->end()
                            ->scalarNode('alfresco_prop_name')
                                ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('cts_case_bulk_create_document_properties')
                    ->useAttributeAsKey('alfresco_prop_name')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('object_prop_name')
                                ->end()
                            ->scalarNode('alfresco_prop_name')
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

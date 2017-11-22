<?php

namespace HomeOffice\AlfrescoApiBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class HomeOfficeAlfrescoApiExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('home_office_alfresco_api.workspace', $config['workspace']);
        $container->setParameter('home_office_alfresco_api.store', $config['store']);
        $container->setParameter('home_office_alfresco_api.alfresco_qname_prefix', $config['alfresco_qname_prefix']);
        $container->setParameter('home_office_alfresco_api.cts_namespace', $config['cts_namespace']);
        $container->setParameter('home_office_alfresco_api.cts_case_properties', $config['cts_case_properties']);
        $container->setParameter('home_office_alfresco_api.cts_case_permissions', $config['cts_case_permissions']);
        $container->setParameter('home_office_alfresco_api.cts_case_document_properties', $config['cts_case_document_properties']);
        $container->setParameter('home_office_alfresco_api.cts_case_document_template_properties', $config['cts_case_document_template_properties']);
        $container->setParameter('home_office_alfresco_api.cts_case_standard_line_properties',$config['cts_case_standard_line_properties']);
        $container->setParameter('home_office_alfresco_api.cts_help_document_properties', $config['cts_help_document_properties']);
        $container->setParameter('home_office_alfresco_api.cts_case_bulk_create_document_properties', $config['cts_case_bulk_create_document_properties']);

        $container->setParameter('home_office_alfresco_api.endpoint', $config['endpoint']);
        $container->setParameter('home_office_alfresco_api.list_endpoint', $config['list_endpoint']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}

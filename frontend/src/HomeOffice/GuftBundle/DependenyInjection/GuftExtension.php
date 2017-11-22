<?php

namespace HomeOffice\GuftBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class GuftExtension
 *
 * @author  Adam Lewis <adam.lewis@digital.homeoffice.gov.uk>
 * @since   2016-04-21
 * @package HomeOffice\GuftBundle\DependencyInjection
 */
class GuftExtension extends Extension
{
    /**
     * @param array $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = [];
        foreach ($configs as $subConfig) {
            $config = array_merge($config, $subConfig);
        }

        if (isset($config['enabled']) && $config['enabled']) {
            $loader = new YamlFileLoader(
                $container,
                new FileLocator(__DIR__.'/../Resources/config')
            );
            $loader->load('services.yml');

            // override settings
            foreach ($config as $index => $value) {
                $container->setParameter(
                    'ft_config.'.$index,
                    $value
                );
            }
        }
    }
}

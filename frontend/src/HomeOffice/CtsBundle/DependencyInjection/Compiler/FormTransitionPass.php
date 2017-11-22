<?php

namespace HomeOffice\CtsBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class FormTransitionPass
 *
 * @package HomeOffice\CtsBundle\DependencyInjection\Compiler
 */
class FormTransitionPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('home_office_cts.form.transition_factory')) {
            return;
        }

        $definition = $container->findDefinition('home_office_cts.form.transition_factory');

        foreach ($container->findTaggedServiceIds('form.transition') as $id => $tags) {
            foreach ($tags as $tag) {
                if (array_key_exists('alias', $tag)) {
                    $definition->addMethodCall('addTransition', [new Reference($id), $tag['alias']]);
                }
            }

        }
    }
}

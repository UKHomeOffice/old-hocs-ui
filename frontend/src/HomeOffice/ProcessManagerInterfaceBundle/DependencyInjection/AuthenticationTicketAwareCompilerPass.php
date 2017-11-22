<?php

namespace HomeOffice\ProcessManagerInterfaceBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class AuthenticationTicketAwareCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $taggedProvidersServices = $container->findTaggedServiceIds(
            'home_office_process_manager_interface.authentication_provider'
        );

        foreach ($taggedProvidersServices as $providerId => $providerAttributes) {
            $providerDefinition = $container->getDefinition($providerId);

            $taggedListenersServices = $container->findTaggedServiceIds(
                'home_office_process_manager_interface.authentication_listener'
            );

            foreach ($taggedListenersServices as $listenerId => $listenerAttributes) {
                $providerDefinition->addMethodCall(
                    'addAuthenticationTicketListener',
                    array(new Reference($listenerId))
                );
            }
        }
    }
}

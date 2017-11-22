<?php

namespace HomeOffice\ProcessManagerInterfaceBundle;

use HomeOffice\ProcessManagerInterfaceBundle\DependencyInjection\AuthenticationTicketAwareCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class HomeOfficeProcessManagerInterfaceBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new AuthenticationTicketAwareCompilerPass());
    }
}

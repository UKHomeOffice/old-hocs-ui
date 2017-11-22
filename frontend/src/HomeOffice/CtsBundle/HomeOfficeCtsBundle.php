<?php

namespace HomeOffice\CtsBundle;

use HomeOffice\CtsBundle\DependencyInjection\Compiler\FormTransitionPass;
use HomeOffice\CtsBundle\Utils\CtsBundleLogger;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class HomeOfficeCtsBundle
 *
 * @package HomeOffice\CtsBundle
 */
class HomeOfficeCtsBundle extends Bundle
{
    /**
     * Boot
     */
    public function boot()
    {
        parent::boot();
        CtsBundleLogger::init($this->container->get('logger'));
    }

    /**
     * Build
     *
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new FormTransitionPass());
    }
}

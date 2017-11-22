<?php

namespace HomeOffice\GuftBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class StyleGuideController
 *
 * @author Adam Lewis <adam.lewis@digital.homeoffice.gov.uk>
 * @since 2016-04-21
 * @package HomeOffice\GuftBundle\Controller
 */
class StyleGuideController extends Controller
{

    /**
     * Index Action
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $params = [];

        if ($this->container->has('ft_config')) {
            $config = $this->get('ft_config');
            $params = $config->getParams();
        }

        return $this->render(
            'GuftBundle:Default:index.html.twig',
            ['breadcrumb' => ['Home' => 'homeoffice_cts_home_home', 'Style Guide' => 'ft_test']]
        );
    }
}

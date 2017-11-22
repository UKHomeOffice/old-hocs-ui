<?php

namespace HomeOffice\CtsBundle\Controller;

use HomeOffice\CtsBundle\Controller\CtsController as Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

/**
 * Class DashboardController
 *
 * @package HomeOffice\CtsBundle\Controller
 */
class DashboardController extends Controller
{
    /**
     * @Route("/dashboard")
     * @Method("GET")
     * @Template
     * @Cache(expires="5 minutes", public=true)
     *
     * @return array
     */
    public function indexAction()
    {
        return [
            'chart' => $this->get('home_office_alfresco_api.service.dashboard')->getChart(),
        ];
    }
}

<?php

namespace HomeOffice\CtsBundle\Controller;

use HomeOffice\AlfrescoApiBundle\Consumer\Reports;
use HomeOffice\CtsBundle\Controller\CtsController as Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ReportsController
 *
 * @package HomeOffice\CtsBundle\Controller
 */
class ReportsController extends Controller
{
    /**
     * @Route("/reports")
     * @Method("GET")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        /** @var Reports $reports */
        $reports = $this->get('home_office_alfresco_api.consumer.reports');

        return [
            'businessUnits' => $reports->getList()
        ];
    }

    /**
     * @Route("/reports/{businessUnit}")
     * @Method("GET")
     * @Template()
     *
     * @param Request $request
     *
     * @return array
     */
    public function viewAction(Request $request)
    {
        /** @var Reports $reports */
        $reports = $this->get('home_office_alfresco_api.consumer.reports');

        return [
            'businessUnits' => $reports->getList(),
            'businessUnit'  => $request->get('businessUnit'),
            'reports'       => $reports->getReports($request->get('businessUnit')),
        ];
    }

    /**
     * @Route("/reports/download/{objectId}/{name}")
     * @Method("GET")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function downloadAction(Request $request)
    {
        /** @var Reports $reports */
        $reports = $this->get('home_office_alfresco_api.consumer.reports');

        $response = new Response($reports->getReport($request->get('objectId')));

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="'.$request->get('name').'"');

        return $response;
    }
}

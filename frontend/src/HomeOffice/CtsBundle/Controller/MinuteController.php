<?php

namespace HomeOffice\CtsBundle\Controller;

use HomeOffice\CtsBundle\Controller\CtsController as Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use HomeOffice\AlfrescoApiBundle\Entity\CtsCaseMinute;
use HomeOffice\CtsBundle\Form\Type\CtsCaseMinuteType;

class MinuteController extends Controller
{

    /**
     * Used only when adding a minute from the view screen.
     * Upon a successful add the user will be redirected to the view case screen.
     * @Template
     * @Route("/minute/add")
     * @Method({"POST"})
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addAction(Request $request)
    {
        $workspace = $this->container->getParameter('home_office_alfresco_api.workspace');
        $store = $this->container->getParameter('home_office_alfresco_api.store');
        $ctsCaseMinuteRepository = $this->get('home_office_alfresco_api.cts_case_minute.repository');
        // use CtsCase super type here to generically add minutes to any case type
        $ctsCase = new CtsCase($workspace, $store);
        $ctsCase->setId($request->request->get('ctsCaseMinuteType')['id']);

        if (isset($request->request->get('ctsCaseMinuteType')['task'])) {
            $ctsCase->setCaseTask($request->request->get('ctsCaseMinuteType')['task']);
        }

        $ctsCaseMinute = new CtsCaseMinute();

        $ctsCaseMinuteType = new CtsCaseMinuteType(
            $ctsCase->getId(),
            ($ctsCase->isQaEligible()) ? $ctsCase->getCaseTask() : null
        );

        $ctsCaseMinuteForm = $this->createForm(
            $ctsCaseMinuteType,
            $ctsCaseMinute
        );

        $ctsCaseMinuteForm->handleRequest($request);

        if ($ctsCaseMinuteForm->isValid()) {
            $ctsCaseMinuteRepository->create($ctsCaseMinute, $ctsCase->getNodeId());
        }
            return $this->redirect($this->generateUrl(
                'homeoffice_cts_case_view',
                array('nodeRef' => $ctsCase->getNodeId())
            ));
    }
}

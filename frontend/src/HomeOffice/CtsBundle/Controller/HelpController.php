<?php

namespace HomeOffice\CtsBundle\Controller;

use HomeOffice\CtsBundle\Controller\CtsController as Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class HelpController extends Controller
{

    /**
     * @Template
     * @Route("/help")
     * @Method({"GET"})
     * @return array
     */
    public function helpAction()
    {
        $ctsHelpRepository = $this->get('home_office_alfresco_api.cts_help_document.repository');
        $helpDocuments = $ctsHelpRepository->getHelpDocuments();
     
        return array(
            'helpDocuments' => $helpDocuments
        );
    }
}

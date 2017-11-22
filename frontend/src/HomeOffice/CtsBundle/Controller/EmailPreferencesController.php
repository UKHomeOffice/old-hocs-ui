<?php

namespace HomeOffice\CtsBundle\Controller;

use HomeOffice\CtsBundle\Controller\CtsController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Class EmailPreferencesController
 *
 * @package HomeOffice\CtsBundle\Controller
 */
class EmailPreferencesController extends Controller
{
    /**
     * @Route("/account/email")
     * @Method({"GET", "POST"})
     * @Template
     *
     * @param Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $form = $this->createForm('EmailPreferences');

        if ($form->handleRequest($request)->isValid()) {
            $this->get('home_office_alfresco_api.consumer.email_preferences')->updatePreferences($form->getData());
        }

        return [
            'form' => $form->createView(),
        ];
    }
}

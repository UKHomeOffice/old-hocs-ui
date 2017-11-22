<?php

namespace HomeOffice\CtsBundle\Controller;

use HomeOffice\CtsBundle\Controller\CtsController as Controller;
use HomeOffice\CtsBundle\Form\Type\FeatureToggleType;
use HomeOffice\CtsBundle\Utils\CtsFeaturesToggle;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;

class FeatureToggleController extends Controller
{
    /**
     * Edit Action
     *
     * @Route("/featuretoggle")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return Response A Response instance
     */
    public function editAction(Request $request)
    {
        $form = $this->createForm(new FeatureToggleType())->handleRequest($request);
        $featureToggle = new CtsFeaturesToggle(new Session);

        if ($form->isSubmitted() && $form->isValid()) {
            $featureToggle->set($request->get('featureToggle')['featureToggle']);
        }

        return $this->render(
            'HomeOfficeCtsBundle:FeatureToggle:featuretoggle.html.twig',
            [
                'toggle' => $featureToggle->get(),
                'form' => $form->createView(),
                'breadcrumb' => [
                    'Home' => 'homeoffice_cts_home_home',
                    'Toggle New Features' => 'homeoffice_cts_featuretoggle_edit'
                ]
            ]
        );
    }
}

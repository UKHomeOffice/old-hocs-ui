<?php

namespace HomeOffice\CtsBundle\Controller;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsHmpoComCase;
use HomeOffice\AlfrescoApiBundle\Service\CaseProgressHelper;
use HomeOffice\CtsBundle\Controller\CtsController as Controller;
use HomeOffice\CtsBundle\Form\FormTransitionFactory;
use HomeOffice\CtsBundle\Form\Transition\TransitionInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CtsCaseController
 *
 * @package HomeOffice\CtsBundle\Controller
 */
class CtsCaseController extends Controller
{
    /**
     * @var array
     */
    private $builderBasedTypes = [
        'FOI',
        'FTC',
        'FUT',
        'FLT',
        'FSC',
        'FSCI',
        'FTCI',
        'DTEN',
        'COL',
        'COM1',
        'COM2',
        'DGEN',
        'GNR',
        'MIN',
        'TRO',
    ];

    /**
     * @Route("/casetest/{nodeRef}")
     * @Method("GET")
     * @Template
     *
     * @param string $nodeRef
     *
     * @return array
     */
    public function indexAction($nodeRef)
    {
        return [
            'nodeRef' => $nodeRef,
            'case'    => $this->getCase($nodeRef, "Viewed Case")
        ];
    }

    /**
     * @Route("/cases/jump/{nodeRef}")
     * @Method({"GET", "POST"})
     * @Template
     *
     * @param string  $nodeRef
     *
     * @return RedirectResponse
     */
    public function jumpAction($nodeRef)
    {
        $case = $this->getCase($nodeRef);

        if ($case instanceof CtsHmpoComCase) {
            return $this->redirect(($this->generateUrl('homeoffice_cts_case_view', ['nodeRef' => $nodeRef])));
        }

        switch ($case->getCaseStatus()) {
            case CaseProgressHelper::PROGRESS_CREATE:
                $response = $this->redirect($this->generateUrl('homeoffice_cts_ctscase_create', ['nodeRef' => $nodeRef]));
                break;
            case CaseProgressHelper::PROGRESS_DRAFT:
                $response = $this->redirect($this->generateUrl('homeoffice_cts_ctscase_draft', ['nodeRef' => $nodeRef]));
                break;
            case CaseProgressHelper::PROGRESS_APPROVE:
            case CaseProgressHelper::PROGRESS_NFA:
            case CaseProgressHelper::PROGRESS_OGD:
                $response = $this->redirect($this->generateUrl('homeoffice_cts_ctscase_approve', ['nodeRef' => $nodeRef]));
                break;
            case CaseProgressHelper::PROGRESS_SIGNOFF:
                $response = $this->redirect($this->generateUrl('homeoffice_cts_ctscase_signoff', ['nodeRef' => $nodeRef]));
                break;
            case CaseProgressHelper::PROGRESS_DISPATCH:
            case CaseProgressHelper::PROGRESS_HOLD:
                $response = $this->redirect($this->generateUrl('homeoffice_cts_ctscase_dispatch', ['nodeRef' => $nodeRef]));
                break;
            case CaseProgressHelper::PROGRESS_COMPLETED:
                $response = $this->redirect($this->generateUrl('homeoffice_cts_ctscase_complete', ['nodeRef' => $nodeRef]));
                break;
            default:
                $response = $this->redirect($this->generateUrl('homeoffice_cts_home_home'));
        }

        return $response;
    }

    /**
     * @Route("/cases/create/{nodeRef}")
     * @Method({"GET", "POST", "PATCH"})
     *
     * @param Request $request
     * @param string  $nodeRef
     *
     * @return JsonResponse|RedirectResponse|Response
     * @throws \Exception
     */
    public function createAction(Request $request, $nodeRef)
    {
        $case = $this->getCase($nodeRef);
        $case->setCaseDocuments($this->getCtsCaseDocumentRepository()->getDocumentsForCase($case->getNodeId()));

        if ($this->getCaseProgressHelper()->isStepValid('create', $case) === false) {
            return $this->redirect($this->generateUrl('homeoffice_cts_ctscase_jump', ['nodeRef' => $nodeRef]));
        }

        $correspondenceType = $case->getCorrespondenceType();

        if (in_array($correspondenceType, $this->builderBasedTypes)) {
            $template = 'HomeOfficeCtsBundle:Builder:create/' . strtolower($correspondenceType) . '.html.twig';
            $form = $this->createForm($correspondenceType . 'Create', $case);
        } else {
            $template = 'HomeOfficeCtsBundle:CtsCase:create/' . $case->getShortName() . '.html.twig';
            $form = $this->createForm($case->getShortName() . 'Create', $case);
        }

        if ($form->handleRequest($request)->isSubmitted() && $request->isXmlHttpRequest()) {
            return $this->handleFormSubmit($form, $case)->getResponse();
        }

        return $this->render($template, [
            'step'       => 'create',
            'case'       => $case,
            'form'       => $form->createView(),
            'memberList' => $this->getListHandler()->getList('ctsMemberList') ,
            'title'      => 'Create ' . $case->getShortName()
        ]);
    }

    /**
     * @Route("/cases/draft/{nodeRef}")
     * @Method({"GET", "POST", "PATCH"})
     *
     * @param Request $request
     * @param string  $nodeRef
     *
     * @return JsonResponse|RedirectResponse|Response
     */
    public function draftAction(Request $request, $nodeRef)
    {
        $case = $this->getCase($nodeRef);
        $case->setCaseDocuments($this->getCtsCaseDocumentRepository()->getDocumentsForCase($case->getNodeId()));

        if ($this->getCaseProgressHelper()->isStepValid('draft', $case) === false) {
            return $this->redirect($this->generateUrl('homeoffice_cts_ctscase_jump', ['nodeRef' => $nodeRef]));
        }

        $correspondenceType = $case->getCorrespondenceType();

        if (in_array($correspondenceType, $this->builderBasedTypes)) {
            $template = 'HomeOfficeCtsBundle:Builder:draft/' . strtolower($correspondenceType) . '.html.twig';
            $form = $this->createForm($correspondenceType . 'Draft', $case);
        } else {
            $template = 'HomeOfficeCtsBundle:CtsCase:draft/' . $case->getShortName() . '.html.twig';
            $form = $this->createForm($case->getShortName() . 'Draft', $case);
        }

        if ($form->handleRequest($request)->isSubmitted() && $request->isXmlHttpRequest()) {
            return $this->handleFormSubmit($form, $case)->getResponse();
        }

        return $this->render($template, [
            'step'       => 'draft',
            'case'       => $case,
            'form'       => $form->createView(),
            'memberList' => $this->getListHandler()->getList('ctsMemberList')
        ]);
    }

    /**
     * @Route("/cases/approve/{nodeRef}")
     * @Method({"GET", "POST", "PATCH"})
     *
     * @param Request $request
     * @param string  $nodeRef
     *
     * @return array|Response
     */
    public function approveAction(Request $request, $nodeRef)
    {
        $case = $this->getCase($nodeRef);
        $case->setCaseDocuments($this->getCtsCaseDocumentRepository()->getDocumentsForCase($case->getNodeId()));

        if ($this->getCaseProgressHelper()->isStepValid('approve', $case) === false) {
            return $this->redirect($this->generateUrl('homeoffice_cts_ctscase_jump', ['nodeRef' => $nodeRef]));
        }

        $correspondenceType = $case->getCorrespondenceType();

        if (in_array($correspondenceType, $this->builderBasedTypes)) {
            $template = 'HomeOfficeCtsBundle:Builder:approve/' . strtolower($correspondenceType) . '.html.twig';
            $form = $this->createForm($correspondenceType . 'Approve', $case);
        } else {
            $template = 'HomeOfficeCtsBundle:CtsCase:approve/' . $case->getShortName() . '.html.twig';
            $form = $this->createForm($case->getShortName() . 'Approve', $case);
        }

        if ($form->handleRequest($request)->isSubmitted() && $request->isXmlHttpRequest()) {
            return $this->handleFormSubmit($form, $case)->getResponse();
        }

        $minister = $case->getMarkupMinister();
        $formattedMinister = $this->getListHandler()->getMinisterName($minister);

        return $this->render($template, [
            'step' => 'approve',
            'case' => $case,
            'form' => $form->createView(),
            'helpers' => [
                'ministerDisplay' => $formattedMinister
            ]
        ]);
    }

    /**
     * @Route("/cases/sign-off/{nodeRef}")
     * @Method({"GET", "POST", "PATCH"})
     *
     * @param Request $request
     * @param string  $nodeRef
     *
     * @return array|Response
     */
    public function signOffAction(Request $request, $nodeRef)
    {
        $case = $this->getCase($nodeRef);
        $case->setCaseDocuments($this->getCtsCaseDocumentRepository()->getDocumentsForCase($case->getNodeId()));

        if ($this->getCaseProgressHelper()->isStepValid('signOff', $case) === false) {
            return $this->redirect($this->generateUrl('homeoffice_cts_ctscase_jump', ['nodeRef' => $nodeRef]));
        }

        $correspondenceType = $case->getCorrespondenceType();

        if (in_array($correspondenceType, $this->builderBasedTypes)) {
            $template = 'HomeOfficeCtsBundle:Builder:signoff/' . strtolower($correspondenceType) . '.html.twig';
            $form = $this->createForm($correspondenceType . 'Signoff', $case);
        } else {
            $template = 'HomeOfficeCtsBundle:CtsCase:signoff/' . $case->getShortName() . '.html.twig';
            $form = $this->createForm($case->getShortname() . 'SignOff', $case);
        }

        if ($form->handleRequest($request)->isSubmitted() && $request->isXmlHttpRequest()) {
            return $this->handleFormSubmit($form, $case)->getResponse();
        }

        return $this->render($template, [
            'step' => 'signOff',
            'case' => $case,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/cases/dispatch/{nodeRef}")
     * @Method({"GET", "POST", "PATCH"})
     *
     * @param Request $request
     * @param string  $nodeRef
     *
     * @return array|Response
     */
    public function dispatchAction(Request $request, $nodeRef)
    {
        $case = $this->getCase($nodeRef);
        $case->setCaseDocuments($this->getCtsCaseDocumentRepository()->getDocumentsForCase($case->getNodeId()));

        if ($this->getCaseProgressHelper()->isStepValid('dispatch', $case) === false) {
            return $this->redirect($this->generateUrl('homeoffice_cts_ctscase_jump', ['nodeRef' => $nodeRef]));
        }

        $correspondenceType = $case->getCorrespondenceType();

        if (in_array($correspondenceType, $this->builderBasedTypes)) {
            $template = 'HomeOfficeCtsBundle:Builder:dispatch/' . strtolower($correspondenceType) . '.html.twig';
            $form = $this->createForm($correspondenceType . 'Dispatch', $case);
        } else {
            $template = 'HomeOfficeCtsBundle:CtsCase:dispatch/' . $case->getShortName() . '.html.twig';
            $form = $this->createForm($case->getShortName() . 'Dispatch', $case);
        }

        if ($form->handleRequest($request)->isSubmitted() && $request->isXmlHttpRequest()) {
            return $this->handleFormSubmit($form, $case)->getResponse();
        }

        return $this->render($template, [
            'step' => 'dispatch',
            'case' => $case,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/cases/complete/{nodeRef}")
     * @Method({"GET", "POST", "PATCH"})
     *
     * @param Request $request
     * @param string  $nodeRef
     *
     * @return array|Response
     */
    public function completeAction(Request $request, $nodeRef)
    {
        $case = $this->getCase($request->get('nodeRef'));
        if ($case->getCaseStatus() !== CaseProgressHelper::PROGRESS_COMPLETED) {
            return $this->redirect($this->generateUrl('homeoffice_cts_ctscase_jump', ['nodeRef' => $nodeRef]));
        }

        $correspondenceType = $case->getCorrespondenceType();

        if (in_array($correspondenceType, $this->builderBasedTypes)) {
            $template = 'HomeOfficeCtsBundle:Builder:dispatch/' . strtolower($correspondenceType) . '.html.twig';
            $form = $this->createForm($correspondenceType . 'Dispatch', $case);
        } else {
            $template = 'HomeOfficeCtsBundle:CtsCase:dispatch/' . $case->getShortName() . '.html.twig';
            $form = $this->createForm($case->getShortName() . 'Dispatch', $case);
        }

        return $this->render($template, [
            'step' => 'dispatch',
            'case' => $case,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/cases/reallocate/{nodeRef}")
     * @Method({"GET", "POST", "PATCH"})
     * @Template
     *
     * @param Request $request
     * @param string  $nodeRef
     *
     * @return array|Response
     */
    public function reallocateAction(Request $request, $nodeRef)
    {
        $case = $this->getCase($nodeRef);

        $form = $this->createForm('CtsCaseReallocate', $case);
        if ($form->handleRequest($request)->isSubmitted() && $request->isXmlHttpRequest()) {
            return $this->handleFormSubmit($form, $case)->getResponse();
        }

        return [
            'step' => 'reallocate',
            'case' => $case,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/cases/return/{nodeRef}")
     * @Method({"GET", "POST", "PATCH"})
     * @Template
     *
     * @param Request $request
     * @param string  $nodeRef
     *
     * @return array|JsonResponse
     */
    public function returnAction(Request $request, $nodeRef)
    {
        $case = $this->getCase($nodeRef);

        $form = $this->createForm('CtsCaseReturn', $case);

        if ($form->handleRequest($request)->isSubmitted() && $request->isXmlHttpRequest()) {
            return $this->handleFormSubmit($form, $case)->getResponse();
        }

        return [
            'step' => 'return',
            'case' => $case,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/cases/reject/{nodeRef}")
     * @Method({"GET", "POST", "PATCH"})
     * @Template
     *
     * @param Request $request
     * @param string  $nodeRef
     *
     * @return array|Response
     */
    public function rejectAction(Request $request, $nodeRef)
    {
        $case = $this->getCase($nodeRef);

        $form = $this->createForm('CtsCaseReject', $case);
        if ($form->handleRequest($request)->isSubmitted() && $request->isXmlHttpRequest()) {
            return $this->handleFormSubmit($form, $case)->getResponse();
        }

        return [
            'step' => 'reject',
            'case' => $case,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/cases/cancel/{nodeRef}")
     * @Method({"GET", "POST", "PATCH"})
     * @Template
     *
     * @param Request $request
     *
     * @return array|Response
     */
    public function cancelAction(Request $request)
    {
        $case = $this->getCase($request->get('nodeRef'));

        $form = $this->createForm('CtsCaseCancel', $case);
        if ($form->handleRequest($request)->isSubmitted() && $request->isXmlHttpRequest()) {
            return $this->handleFormSubmit($form, $case)->getResponse();
        }

        return [
            'step' => 'cancel',
            'case' => $case,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/cases/manual-allocation/{nodeRef}/{transition}")
     * @Method({"GET", "POST"})
     * @Template
     *
     * @param Request $request
     *
     * @return array|Response
     */
    public function manualAllocationAction(Request $request)
    {
        $case = $this->getCase($request->get('nodeRef'));

        $form = $this->createForm('Allocate', $case);

        if ($form->handleRequest($request)->isSubmitted() && $request->isXmlHttpRequest()) {
            if ($case->getCaseWorkflowStatus()->hasTransition($request->get('transition')) === false) {
                $form->addError(new FormError('Manual Allocation is not valid'));
            }

            $transition = $this->handleFormSubmit($form, $case);
            $transition->getAjaxResponseBuilder()->setCallbackParameters(array_merge(
                $transition->getAjaxResponseBuilder()->getCallbackParameters(),
                [$request->get('transition')]
            ));

            return $transition->getResponse();
        }

        return [
            'case'       => $case,
            'transition' => $request->get('transition'),
            'form'       => $form->createView(),
        ];
    }

    /**
     * Handle Form Submit
     *
     * @param Form    $form
     * @param CtsCase $case
     *
     * @return TransitionInterface
     * @throws \Exception
     */
    private function handleFormSubmit(Form $form, CtsCase $case)
    {
        if (!$this->isUserAllowedToSubmit($form, $case)) {
            $form->addError(new FormError('Reallocate this case to yourself to perform this action'));
        }

        $form = $this->dcuReturnReason($form, $case);

        /** @var FormTransitionFactory $transitionFactory */
        $transitionFactory = $this->get('home_office_cts.form.transition_factory');

        $transition = $transitionFactory
            ->build($form, $case)
            ->validate();

        if ($form->isValid()) {
            $transition->handle();
        }

        return $transition;
    }

    private function dcuReturnReason(Form $form, CtsCase $case)
    {
        if (
            in_array($case->getShortName(), ['CtsDcuMinisterialCase', 'CtsDcuTreatOfficialCase', 'CtsNo10Case'])
            && $form->has('returnReason')
            && ! $form->get('returnReason')->getData()
        ) {
            $form->get('returnReason')->addError(new FormError('Reason for return is compulsory'));
        }

        return $form;
    }

    /**
     * Is the current user allowed to submit the form using the clicked button?
     *
     * @param Form    $form
     * @param CtsCase $case
     *
     * @return bool
     */
    private function isUserAllowedToSubmit(Form $form, CtsCase $case)
    {
        $allowedButtons = ['Reallocate', 'ManualAllocate'];

        return
            $this->getUser()->isManager() === true ||
            !is_null($form->getClickedButton()) && in_array($form->getClickedButton()->getName(), $allowedButtons) ||
            (!is_null($case->getCaseOwner()) && $case->getCaseOwner() == $this->getUser()->getUserName());
    }
}

<?php

namespace HomeOffice\CtsBundle\Controller;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use HomeOffice\AlfrescoApiBundle\Repository\CtsCaseMinuteRepository;
use HomeOffice\AlfrescoApiBundle\Repository\CtsCaseRepository;
use HomeOffice\AlfrescoApiBundle\Service\CTSHelper;
use HomeOffice\CtsBundle\Controller\CtsController as Controller;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use HomeOffice\AlfrescoApiBundle\Entity\CtsCaseEntry;
use HomeOffice\AlfrescoApiBundle\Entity\CtsCaseDocument;
use HomeOffice\AlfrescoApiBundle\Entity\CtsCaseMinute;
use HomeOffice\AlfrescoApiBundle\Entity\CtsTsoFeed;
use HomeOffice\CtsBundle\Form\Type\AllocateUserType;
use HomeOffice\CtsBundle\Form\GuftType\CtsCaseEntryType as GuftCtsCaseEntryType;
use HomeOffice\CtsBundle\Form\Type\CtsCaseDocumentType;
use HomeOffice\CtsBundle\Form\Type\CtsCaseMinuteType;
use HomeOffice\CtsBundle\Form\Type\CtsCaseUploadType;
use HomeOffice\CtsBundle\Form\Type\CtsViewCaseType;
use HomeOffice\AlfrescoApiBundle\Service\CaseStatus;
use HomeOffice\AlfrescoApiBundle\Service\TaskStatus;
use HomeOffice\AlfrescoApiBundle\Service\TypeClassMaps;
use HomeOffice\AlfrescoApiBundle\Service\CaseCorrespondenceSubType;
use Symfony\Component\Form\FormError;
use HomeOffice\CtsBundle\Utils\CtsFeaturesToggle;
use HomeOffice\AlfrescoApiBundle\Service\CaseCorrespondenceType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Form;

/**
 * Class CaseController
 *
 * @package HomeOffice\CtsBundle\Controller
 */
class CaseController extends Controller
{
    /**
     * Override createForm
     * Creates and returns a CtsForm instance from the type of the form.
     *
     * @param string|FormTypeInterface $type    The built type of the form
     * @param mixed                    $data    The initial data for the form
     * @param array                    $options Options for the form
     *
     * @return Form
     */
    public function createForm($type, $data = null, array $options = array())
    {
        return $this->container->get('home_office_cts.form.cts_form.factory')->create($type, $data, $options);
    }

    /**
     * @Route("/cases/createSimple")
     * @Method({"GET", "POST"})
     * @Template
     *
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function createSimpleAction(Request $request)
    {

        // @todo abstract out to middleware.
        if (false === $this->get('security.context')->isGranted('create', $this->getUser())) {
            $this->setSessionParameter('errorMsg', 'You do not have permission to create a case');
            return $this->redirect($this->generateUrl('homeoffice_cts_home_home'));
        }

        // create the basic type to start with
        $ctsCaseEntry = new CtsCaseEntry();
        $ctsCaseEntry->setValidate($this->getSessionParameter('ctsCaseEntryValidate'));
        $this->setSessionParameter('ctsCaseEntryValidate', null);
        $ctsCaseEntryForm = $this->createForm(new GuftCtsCaseEntryType(), $ctsCaseEntry);

        // if we have a post then check the request for the correspondence type
        // and set the form type again with this to create the extra fields
        $oldCorrespondenceType = null;
        $correspondenceType = null;
        if ($request->getMethod() == 'POST') {
            $correspondenceType = $request->get('ctsCaseEntry[correspondenceType]', null, true);
            $oldCorrespondenceType = $request->get('ctsCaseEntry[oldCorrespondenceType]', null, true);
            if ($correspondenceType === 'COR') {
                $correspondenceTypeGroup = CaseCorrespondenceType::HMPO;
            } else {
                $correspondenceTypeGroup = CaseCorrespondenceSubType::getCaseType($correspondenceType);
            }
            $formType = new GuftCtsCaseEntryType($correspondenceType, $correspondenceTypeGroup);

            $ctsCaseEntryForm = $this->createForm($formType, $ctsCaseEntry);
        }

        $ctsCaseEntryForm->handleRequest($request);

        if ($ctsCaseEntryForm->get('SetType')->isClicked() &&
            $oldCorrespondenceType &&
            $oldCorrespondenceType != $correspondenceType
        ) {
            $formType = new GuftCtsCaseEntryType($correspondenceType, $correspondenceTypeGroup);
            $ctsCaseEntryForm = $this->createForm($formType, $ctsCaseEntry);
            $this->setSessionParameter('ctsCaseEntryValidate', true);

            return $this->renderInitiateCase($ctsCaseEntryForm, $request, $correspondenceType, $oldCorrespondenceType);
        }

        if ($ctsCaseEntryForm->isValid()) {
            $this->setSessionParameter('ctsCaseEntryValidate', true);
            // if the correspondence type has been set then we just need to
            // reload the form type with the correspondence type and group
            // if the create button was clicked we work out the type and build
            // the correct case object.
            if ($ctsCaseEntryForm->get('SetType')->isClicked()) {
                $correspondenceType = $ctsCaseEntry->getCorrespondenceType();
                if ($correspondenceType === 'COR') {
                    $correspondenceTypeGroup = CaseCorrespondenceType::HMPO;
                } else {
                    $correspondenceTypeGroup = CaseCorrespondenceSubType::getCaseType($correspondenceType);
                }
                $ctsCaseEntryForm = $this->createForm(
                    new GuftCtsCaseEntryType($correspondenceType, $correspondenceTypeGroup),
                    $ctsCaseEntry
                );
            } else {
                $caseFactory = $this->get('home_office_alfresco_api.cts_case.factory');
                /** @var CtsCase $ctsCase */
                $ctsCase = $caseFactory->build(
                    $ctsCaseEntry->toArray(),
                    $this->getCtsHelper()->getCaseClassFromType($ctsCaseEntry->getCorrespondenceType())
                );
                if (isset($ctsCase)) {
                    // set the case details
                    $workspace = $this->container->getParameter('home_office_alfresco_api.workspace');
                    $store = $this->container->getParameter('home_office_alfresco_api.store');
                    $ctsCase->setWorkspace($workspace);
                    $ctsCase->setStore($store);
                    $ctsCase->setFolderName('NameReplacedByAlfresco');
                    $ctsCase->setCaseStatus(CaseStatus::NEW_CASE);
                    $ctsCase->setCaseTask(TaskStatus::CREATE_CASE);

                    // create the document if necessary and add it to the case
                    if (null !== $ctsCaseEntry->getOriginalDocument()) {
                        $ctsCaseDocument = new CtsCaseDocument($workspace, $store);
                        $ctsCaseDocument->setFile($ctsCaseEntry->getOriginalDocument());
                        $ctsCaseDocument->setDocumentType('Original');
                        $ctsCaseDocument->setDocumentDescription('Original document');
                        $ctsCase->setNewDocument($ctsCaseDocument);
                    }

                    if ($ctsCase->getCorrespondenceType() === 'COR') {
                        $ctsCase->setCorrespondenceType($ctsCaseEntryForm->get('hmpoCorrespondenceType')->getData());
                    }

                    $this->getCtsCaseRepository()->create($ctsCase);

                    if ($ctsCase->getNewDocument() != null) {
                        $caseDocumentFile = $ctsCase->getNewDocument()->getFile();
                    }
                    if (isset($caseDocumentFile)) {
                        $return = $this->getCtsCaseDocumentRepository()->create(
                            $ctsCase->getNewDocument(),
                            $ctsCase->getId(),
                            $ctsCase->getNodeId()
                        );

                        if( $return == "virus")
                        {
                            throw new \HttpException("A Virus was found in the file. Do not try again.");
                        }
                    }

                    return $this->redirect($this->generateUrl(
                        'homeoffice_cts_case_edit',
                        array('nodeRef' => $ctsCase->getNodeId())
                    ));
                }
            }
        }

        if ($ctsCaseEntryForm->isSubmitted()) {
            $this->setSessionParameter('ctsCaseEntryValidate', true);
        }

        return $this->renderInitiateCase($ctsCaseEntryForm, $request, $correspondenceType, $oldCorrespondenceType);
    }

    /**
     * Render Initiate Case
     *
     * @param Form    $ctsCaseEntryForm
     * @param Request $request
     * @param string  $correspondenceType
     * @param string  $oldCorrespondenceType
     *
     * @return Response
     */
    private function renderInitiateCase(
        Form $ctsCaseEntryForm,
        Request $request,
        $correspondenceType,
        $oldCorrespondenceType
    ) {
        return $this->render('HomeOfficeCtsBundle:Case:guftCreateSimple.html.twig', [
            'form'                    => $ctsCaseEntryForm->createView(),
            'oldCorrespondenceType'   => $correspondenceType ? $correspondenceType : $oldCorrespondenceType,
            'correspondenceTypeGroup' => $request->get('correspondenceTypeGroup'),
            'correspondenceType'      => $request->get('ctsCaseEntry')['correspondenceType'],
            'correspondenceTypes'     => CaseCorrespondenceType::getCorrespondenceTypesForCaseInitiation()
        ]);
    }

    /**
     * @Template
     * @Route("/cases/view/{nodeRef}")
     * @Method("GET")
     */
    public function viewAction(Request $request, $nodeRef)
    {
        $errorMsg = $this->getSessionParameter('errorMsg');

        if (isset($errorMsg)) {
            $this->setSessionParameter('errorMsg', null);
        }

        $mandatoryFieldError = $this->getSessionParameter($nodeRef . 'mandatoryFieldError');

        if (isset($mandatoryFieldError)) {
            $errorMsg .= $mandatoryFieldError;
            $this->setSessionParameter($nodeRef . 'mandatoryFieldError', null);
        }

        $addDocumentError = $this->getSessionParameter('documentErrorMsg');

        if (isset($addDocumentError)) {
            $this->setSessionParameter('documentErrorMsg', null);
        }

        $filters = (null === $request->get('filters'))
            ? array('manual')
            : $request->get('filters');

        $grouped      = $request->query->get('grouped');
        $actionType   = $request->query->get('actionType');
        $statusChange = $request->query->get('statusChange');
        $allocateUnitValue = $this->getSessionParameter('allocateUnitValue');
        $allocateTeamValue = $this->getSessionParameter('allocateTeamValue');
        $allocateUserValue = $this->getSessionParameter('allocateUserValue');

        if (isset($allocateUnitValue)) {
            $this->setSessionParameter('allocateUnitValue', null);
        }

        if (isset($allocateTeamValue)) {
            $this->setSessionParameter('allocateTeamValue', null);
        }

        if (isset($allocateUserValue)) {
            $this->setSessionParameter('allocateUserValue', null);
        }

        $workspace                     = $this->container->getParameter('home_office_alfresco_api.workspace');
        $store                         = $this->container->getParameter('home_office_alfresco_api.store');
        $ctsCaseRepository             = $this->get('home_office_alfresco_api.cts_case.repository');
        $ctsCaseDocumentRepository     = $this->get('home_office_alfresco_api.cts_case_document.repository');
        $ctsCaseStandardLineRepository = $this->get('home_office_alfresco_api.cts_case_standard_line.repository');
        $ctsHelper                     = $this->get('home_office_alfresco_api.cts_case.cts_helper');
        $ctsListHandler                = $this->get('home_office_list.handler');
        /** @var CtsCase $ctsCase */
        $ctsCase = $ctsCaseRepository->getCase($nodeRef);


        $featureToggle = new CtsFeaturesToggle(new Session);
        if ($featureToggle->get($ctsCase)) {
            // Use the jumper to target the correct progress page.
            return $this->redirect($this->generateUrl('homeoffice_cts_ctscase_jump', ['nodeRef' => $nodeRef]));
        }

        $ctsHelper->setCaseOwner($ctsCase);
        $activeTransition = null;
        $mandatoryFields  = null;

        if ($actionType == 'allocate') {
            $ctsCase->setAssignedUnit(null);
            $ctsCase->setAssignedTeam(null);
            $ctsCase->setAssignedUser(null);
        }

        if ($statusChange != null) {
            if ($statusChange != 'Reallocate') {
                $mandatoryFields = $ctsCase->getCaseWorkflowStatus()->getMandatoryFields();

                if ($mandatoryFields != null) {
                    $error = '';

                    foreach ($mandatoryFields as $mandatoryField) {
                        $getter = 'get' . ucfirst($mandatoryField->getName());

                        if (method_exists($ctsCase, $getter)) {
                            if ($ctsCase->$getter() == null) {
                                $error .= $mandatoryField->getMessage() . "\n";
                            }
                        }
                    }

                    if ($error != '') {
                        $this->setSessionParameter($ctsCase->getNodeId() . 'mandatoryFieldError', $error);

                        return $this->redirect(
                            $this->generateUrl(
                                'homeoffice_cts_case_view',
                                array(
                                    'nodeRef' => $ctsCase->getNodeId()
                                )
                            )
                        );
                    }
                }
            }

            $activeTransition = $ctsCase
                ->getCaseWorkflowStatus()
                ->getTransitions()[$statusChange];
        }

        $formTypeClass = $ctsHelper->getFormTypeClassFromType($ctsCase->getCorrespondenceType());

        $displayMemberStopListIcon = false;
        $displayTopicStopListIcon  = false;
        $ministerList              = $ctsListHandler->getList('ctsMinisterList');
        $unitList                  = $ctsListHandler->getList('ctsUnitList');

        if (array_key_exists($ctsCase->getMarkupMinister(), $ministerList)) {
            $ctsCase->setMarkupMinister($ministerList[$ctsCase->getMarkupMinister()]);
        }

        if (array_key_exists($ctsCase->getMarkupUnit(), $unitList)) {
            $ctsCase->setMarkupUnit($unitList[$ctsCase->getMarkupUnit()]);
        }

        $addDocAttempted = false;

        // if there is a document set in the session, use this
        // else create a new document
        $ctsCaseDocument = $this->getSessionParameter('ctsCaseDocument');

        if (isset($ctsCaseDocument)) {
            $addDocAttempted = true;
            $this->setSessionParameter('ctsCaseDocument', null);
        } else {
            $ctsCaseDocument = new CtsCaseDocument($workspace, $store);
        }

        $addMinuteAttempted = false;
        // if there is a minute set in the session, use this
        // else create a new minute
        $ctsCaseMinute = $this->getSessionParameter('ctsCaseMinute');

        if (isset($ctsCaseMinute)) {
            $addMinuteAttempted = true;
            $this->setSessionParameter('ctsCaseMinute', null);
        } else {
            $ctsCaseMinute = new CtsCaseMinute($workspace, $store);
        }

        /** @var CtsCaseMinuteRepository $ctsCaseMinuteRepository */
        $ctsCaseMinuteRepository = $this->get('home_office_alfresco_api.cts_case_minute.repository');
        $ctsCase->setNewDocument($ctsCaseDocument);
        $ctsCase->setCaseDocuments($ctsCaseDocumentRepository->getDocumentsForCase($ctsCase->getNodeId()));
        $ctsCase->setCaseMinutes($ctsCaseMinuteRepository->getMinutesForCase($ctsCase->getNodeId(), $filters));
        $standardLine = null;

        $documentTemplates = $this->getDocumentTemplates($ctsCase->getCorrespondenceType());

        $ctsCaseDocumentForm = $this->createForm(
            new CtsCaseDocumentType(
                'view',
                $workspace,
                $store,
                $ctsCase->getId()
            ),
            $ctsCaseDocument,
            array(
                'action' => $this->generateUrl('homeoffice_cts_document_add')
            )
        );

        // if a document add was attempted, manually set errors on the form
        if ($addDocAttempted) {
            $this->populateCaseDocumentFormErrors($ctsCaseDocument, $ctsCaseDocumentForm);
        }

        $ctsCaseMinuteForm = $this->createForm(
            new CtsCaseMinuteType(
                $ctsCase->getId(),
                ($ctsCase->isQaEligible()) ? $ctsCase->getCaseTask() : null
            ),
            $ctsCaseMinute,
            array(
                'action' => $this->generateUrl('homeoffice_cts_minute_add')
            )
        );

        if ($addMinuteAttempted) {
            $this->populateCaseMinuteFormErrors($ctsCaseMinute, $ctsCaseMinuteForm);
        }

        $ctsViewCaseForm = $this->createForm(
            new CtsViewCaseType(
                $this->get('security.context'),
                $actionType
            ),
            $ctsCase,
            array(
                'action' => $this->generateUrl('homeoffice_cts_status_updatestatus')
            )
        );

        $allocateView = null;

        if ($actionType == 'allocate') {
            if (false === $this->get('security.context')->isGranted('allocate', $ctsCase)) {
                $this->setSessionParameter('errorMsg', 'You do not have permission to allocate this case');

                return $this->redirect(
                    $this->generateUrl(
                        'homeoffice_cts_case_view',
                        array(
                            'nodeRef' => $ctsCase->getNodeId()
                        )
                    )
                );
            }

            if ($allocateUnitValue != null) {
                $ctsCase->setAssignedUnit($allocateUnitValue);
            }

            if ($allocateTeamValue != null) {
                $ctsCase->setAssignedTeam($allocateTeamValue);
            }

            if ($allocateUserValue != null) {
                $ctsCase->setAssignedUser($allocateUserValue);
            }

            $userTeams = $this->getUser()->getTeams();
            $userUnits = $this->getUser()->getUnits();

            $allocateUserForm = $this->createForm(
                new AllocateUserType(
                    $ctsListHandler,
                    $userUnits,
                    $userTeams,
                    $statusChange
                ),
                $ctsCase,
                array(
                    'action' => $this->generateUrl('homeoffice_cts_status_allocate')
                )
            );

            $allocateView     = $allocateUserForm->createView();
        }

        return array(
            'ctsCase'                   => $ctsCase,
            'ctsHelper'                 => $ctsHelper,
            'displayMemberStopListIcon' => $displayMemberStopListIcon,
            'displayTopicStopListIcon'  => $displayTopicStopListIcon,
            'ctsViewCaseForm'           => $ctsViewCaseForm->createView(),
            'ctsCaseDocumentForm'       => $ctsCaseDocumentForm->createView(),
            'ctsCaseMinuteForm'         => $ctsCaseMinuteForm->createView(),
            'allocateUserForm'          => $allocateView,
            'documentTemplates'         => $documentTemplates,
            'documentErrorMsg'          => $addDocumentError,
            'standardLine'              => $standardLine,
            'errorMsg'                  => $errorMsg,
            'navClass'                  => 'case_creation',
            'statuses'                  => CaseStatus::getStatusArrayConstants(),
            'taskStatuses'              => TaskStatus::getTaskArrayConstants(),
            'correspondenceSubTypes'    => CaseCorrespondenceSubType::getCorrespondenceSubTypeArray(),
            'grouped'                   => $grouped,
            'canDelete'                 => $this->get('security.context')->isGranted('delete', $ctsCase),
            'isAssigned'                => $this->get('security.context')->isGranted('assigned', $ctsCase),
            'activeTransition'          => $activeTransition,
            'mandatoryFields'           => $mandatoryFields,
            'caseTypeText'              => TypeClassMaps::getName($ctsCase->getCorrespondenceType()),
            'ctsCaseMinuteFilters'      => array_flip($filters)
        );
    }

    /**
     * @Template
     * @Route("/cases/viewMinutes/{nodeRef}")
     * @Method({"GET"})
     */
    public function viewMinutesAction(Request $request, $nodeRef)
    {

        $filters = (null === $request->get('filters'))
            ? array('manual')
            : $request->get('filters');

        /** @var CtsCaseRepository $ctsCaseRepository */
        $ctsCaseRepository       = $this->get('home_office_alfresco_api.cts_case.repository');
        /** @var CtsCase $ctsCase */
        $ctsCase                 = $ctsCaseRepository->getCase($nodeRef);
        /** @var CTSHelper $ctsHelper */
        $ctsHelper               = $this->get('home_office_alfresco_api.cts_case.cts_helper');
        /** @var CtsCaseMinuteRepository $ctsCaseMinuteRepository */
        $ctsCaseMinuteRepository = $this->get('home_office_alfresco_api.cts_case_minute.repository');

        $ctsCase->setCaseMinutes($ctsCaseMinuteRepository->getMinutesForCase($ctsCase->getNodeId(), $filters));

        return array(
            'ctsCase'   => $ctsCase,
            'ctsHelper' => $ctsHelper,
        );
    }

    /**
     * @Template
     * @Route("/cases/unlink/{nodeRef}/{childRef}")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param         $nodeRef
     * @param         $childRef
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function unlinkAction(Request $request, $nodeRef, $childRef)
    {

        $workspace                 = $this->container->getParameter('home_office_alfresco_api.workspace');
        $store                     = $this->container->getParameter('home_office_alfresco_api.store');
        /** @var CtsCaseRepository $ctsCaseRepository */
        $ctsCaseRepository         = $this->get('home_office_alfresco_api.cts_case.repository');

        $ctsCase = $this->getSessionParameter('ctsCase_' . $nodeRef);

        if (false === isset($ctsCase)) {
            $ctsCase = $ctsCaseRepository->getCase($nodeRef);
        }

        /** @var CtsCase $childCase */
        $childCase = $ctsCaseRepository->getCase($childRef);

        if (true === $childCase->getIsLinkedCase()) {
            $ctsCaseRepository->removeLinkedCase(
                $ctsCase,
                $childCase
            );
        }

        return $this
            ->redirect(
                $this->generateUrl(
                    'homeoffice_cts_case_edit',
                    array(
                        'nodeRef' => $nodeRef
                    )
                )
            );

    }

    /**
     * @Template
     * @Route("/cases/edit/{nodeRef}")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, $nodeRef)
    {
        $errorMsg = $this->getSessionParameter('errorMsg');
        if (isset($errorMsg)) {
            $this->setSessionParameter('errorMsg', null);
        }

        $mandatoryFieldError = $this->getSessionParameter($nodeRef . 'mandatoryFieldError');
        if (isset($mandatoryFieldError)) {
            $errorMsg .= $mandatoryFieldError;
            $this->setSessionParameter($nodeRef . 'mandatoryFieldError', null);
        }

        $filters = (null === $request->get('filters')) ? array('manual') : $request->get('filters');

        $workspace                 = $this->container->getParameter('home_office_alfresco_api.workspace');
        $store                     = $this->container->getParameter('home_office_alfresco_api.store');
        $ctsHelper                 = $this->get('home_office_alfresco_api.cts_case.cts_helper');
        $ctsCaseRepository         = $this->get('home_office_alfresco_api.cts_case.repository');
        $ctsCaseDocumentRepository = $this->get('home_office_alfresco_api.cts_case_document.repository');
        $ctsCaseMinuteRepository   = $this->get('home_office_alfresco_api.cts_case_minute.repository');
        $ctsListHandler            = $this->get('home_office_list.handler');
        $saveWarningMessage        = null;

        // if there is a case set in the session, use this
        // else get the case from alfresco
        /** @var CtsCase $ctsCase */
        $ctsCase = $this->getSessionParameter('ctsCase_' . $nodeRef);

        if (isset($ctsCase)) {
            $this->setSessionParameter('ctsCase_' . $nodeRef, null);
            // if group cases were set then we must get the case from alfresco to load these
            // also null the uins to group so it is set back to an empty text box
            if (method_exists($ctsCase, 'getUinsToGroup')) {
                if ($ctsCase->getUinsToGroup() != '') {
                    $ctsCase->setUinsToGroup(null);
                    $ctsCaseCurrent = $ctsCaseRepository->getCase($nodeRef);
                    $ctsCase->setGroupedCases($ctsCaseCurrent->getGroupedCases());
                }
            }
            if ($ctsCase->getHrnsToLink() != '') {
                $ctsCase->setHrnsToLink(null);
                $ctsCaseCurrent = $ctsCaseRepository->getCase($nodeRef);
                $ctsCase->setLinkedCases($ctsCaseCurrent->getLinkedCases());
            }
        } else {
            $ctsCase = $ctsCaseRepository->getCase($nodeRef);
        }

        $featureToggle = new CtsFeaturesToggle(new Session);
        if ($featureToggle->get($ctsCase)) {
            // Use the jumper to target the correct progress page.
            return $this->redirect($this->generateUrl('homeoffice_cts_ctscase_jump', ['nodeRef' => $nodeRef]));
        }

        // do this as early as possible to avoid doing too much stuff
        // if the user does not have edit permissions
        if (false === $this->get('security.context')->isGranted('edit', $ctsCase)) {
            $this->setSessionParameter('errorMsg', 'You do not have permission to edit this case');
            return
                $this->redirect(
                    $this->generateUrl(
                        'homeoffice_cts_case_view',
                        array('nodeRef' => $nodeRef)
                    )
                );
        }

        $ctsHelper->setCaseOwner($ctsCase);

        // the PIT extension tickbox is only allowed to be ticked once.
        // keep track of the original value in case it was ticked and the
        // user added a minute or a document.
        if (method_exists($ctsCase, 'getPitExtension')) {
            $originalPitExtension = $ctsCase->getPitExtension();
            if ($request->request->get('ctsFoiCase') != null &&
                array_key_exists('originalPitExtension', $request->request->get('ctsFoiCase'))
            ) {
                $originalPitExtension = $request->request->get('ctsFoiCase')['originalPitExtension'];
            }
        }

        // the FTC (internal review time complaint) complex tickbox is only allowed to be ticked once.
        // keep track of the original value in case it was ticked and the user added a minute or a document.
        if (method_exists($ctsCase, 'getComplex')) {
            $originalComplex = $ctsCase->getComplex();
            if ($request->request->get('ctsFoiComplaintCase') != null &&
                array_key_exists('originalComplex', $request->request->get('ctsFoiComplaintCase'))
            ) {
                $originalComplex = $request->request->get('ctsFoiComplaintCase')['originalComplex'];
            }
        }

        $addDocumentError = $this->getSessionParameter('documentErrorMsg');
        if (isset($addDocumentError)) {
            $this->setSessionParameter('documentErrorMsg', null);
        }

        $groupedCaseError = $this->getSessionParameter('groupedCaseError');
        if (isset($groupedCaseError)) {
            $this->setSessionParameter('groupedCaseError', null);
        }

        $linkedCaseError = $this->getSessionParameter('linkedCaseError');
        if (isset($linkedCaseError)) {
            $this->setSessionParameter('linkedCaseError', null);
        }

        $ctsCase->setNewDocument(new CtsCaseDocument($workspace, $store));
        $ctsCase->setCaseDocuments($ctsCaseDocumentRepository->getDocumentsForCase($ctsCase->getNodeId()));
        $documentTemplates = $this->getDocumentTemplates($ctsCase->getCorrespondenceType());

        $ctsCase->setNewMinute(new CtsCaseMinute());
        $ctsCase->setCaseMinutes($ctsCaseMinuteRepository->getMinutesForCase($ctsCase->getNodeId(), $filters));

        $formTypeClass             = $ctsHelper->getFormTypeClassFromType($ctsCase->getCorrespondenceType());
        $displayMemberStopListIcon = false;
        $displayTopicStopListIcon  = false;

        $correspondenceTypeGroup = strtolower($ctsHelper->getCorrespondenceTypeGroup(
            $ctsCase->getCorrespondenceType()
        ));

        $hasCorrespondentStoplist = TypeClassMaps::hasCorrespondentStoplist($ctsCase->getCorrespondenceType());
        $stopList                 = $hasCorrespondentStoplist ?
            $ctsListHandler->getList($correspondenceTypeGroup . 'CorrespondentStopList') :
            array();

        $pqTopicStopList = [];
        $topicList = [];

        $formClass = new $formTypeClass('edit', $ctsListHandler, $ctsHelper, false);

        if (true === method_exists($formClass, 'setRequiredFields')) {
            $formClass->setRequiredFields($ctsCase->getCaseMandatoryFields());
        }

        if (method_exists($formClass, 'setOriginalPitExtension')) {
            $formClass->setOriginalPitExtension($originalPitExtension);
        }
        if (method_exists($formClass, 'setOriginalComplex')) {
            $formClass->setOriginalComplex($originalComplex);
        }
        $ctsCaseForm = $this->createForm($formClass, $ctsCase);

        $ctsCaseForm->handleRequest($request);

        /**
         * We do the following here: -
         * • if 'Save1' or 'Save2' clicked, validate $ctsCaseForm only, no cascading to embedded forms
         * • if 'Save document' clicked, validate $ctsCaseForm->get('newDocument') manually, and save if valid
         * • if 'Save minute' clicked, validate $ctsCaseForm->get('newMinute') manually, and save if valid
         */
        if ($request->getMethod() == 'POST') {
            if ($ctsCaseForm->get('newDocument')->get('addDocument')->isClicked()) {
                $ctsCaseDocument = $ctsCaseForm->get('newDocument')->getData();
                $errors          = $this->validate($ctsCaseDocument);
                if (!$errors) {
                    $result = $ctsCaseDocumentRepository->create(
                        $ctsCaseDocument,
                        $ctsCase->getId(),
                        $ctsCase->getNodeId()
                    );
                    if (is_string($result)) {
                        $this->setSessionParameter('documentErrorMsg', $result);
                    }
                    // set the PIT extension tickbox back to what it was originally
                    // this is because it is only editable when it is not ticked,
                    // but if it is ticked and a minute is added it would become
                    // read only
                    if (method_exists($ctsCase, 'setPitExtension')) {
                        $ctsCase->setPitExtension($originalPitExtension);
                    }
                    // same as above for the complex tickbox
                    if (method_exists($ctsCase, 'setComplex')) {
                        $ctsCase->setComplex($originalComplex);
                    }
                    // if we are only adding a doc, add the ctsCase to the session and reload it on redirect
                    $this->setSessionParameter('ctsCase_' . $nodeRef, $ctsCase);
                    return $this->redirect($this->generateUrl(
                        'homeoffice_cts_case_edit',
                        array('nodeRef' => $nodeRef)
                    ));
                } else {
                    $this->populateCaseDocumentFormErrors($ctsCaseDocument, $ctsCaseForm->get('newDocument'));
                }
            }

            if ($ctsCaseForm->get('newMinute')->get('saveMinute')->isClicked()) {
                $ctsCaseMinute = $ctsCaseForm->getData()->getNewMinute();
                $errors        = $this->validate($ctsCaseMinute);
                if (!$errors) {
                    $ctsCaseMinuteRepository->create($ctsCaseMinute, $ctsCase->getNodeId());
                    // set the PIT extension tickbox back to what it was originally
                    // this is because it is only editable when it is not ticked,
                    // but if it is ticked and a minute is added it would become
                    // read only
                    if (method_exists($ctsCase, 'setPitExtension')) {
                        $ctsCase->setPitExtension($originalPitExtension);
                    }
                    // same as above for the complex tickbox
                    if (method_exists($ctsCase, 'setComplex')) {
                        $ctsCase->setComplex($originalComplex);
                    }
                    // if we are only adding a minute, add the ctsCase to the session and reload it on redirect
                    $this->setSessionParameter('ctsCase_' . $nodeRef, $ctsCase);
                    return $this->redirect($this->generateUrl(
                        'homeoffice_cts_case_edit',
                        array('nodeRef' => $nodeRef)
                    ));
                } else {
                    $this->populateCaseMinuteFormErrors($ctsCaseMinute, $ctsCaseForm->get('newMinute'));
                }
            }

            if (($ctsCaseForm->get('Save1')->isClicked() || $ctsCaseForm->get('Save2')->isClicked())) {
                $correspondenceType = $ctsCase->getCorrespondenceType();

                /* If the case type is UKVI 'M' or UKVI 'B'
                        and the markup decision is 'no reply needed' or 'OGD'
                            then check the mandatory fields */
                if ($correspondenceType == 'IMCM' || $correspondenceType == 'IMCB') {
                    $markupDecision = $ctsCase->getMarkupDecision();
                    if ($markupDecision == 'No reply needed' || $markupDecision == 'Refer to OGD') {
                        $mandatoryFields = $ctsCase->getCaseWorkflowStatus()->getMandatoryFields();

                        if ($mandatoryFields != null) {
                            $error = '';

                            foreach ($mandatoryFields as $mandatoryField) {
                                $getter = 'get' . ucfirst($mandatoryField->getName());

                                if (method_exists($ctsCase, $getter)) {
                                    if ($ctsCase->$getter() == null) {
                                        $error .= $mandatoryField->getMessage() . "\n";
                                    }
                                }
                            }

                            if ($error != '') {
                                $this->setSessionParameter($ctsCase->getNodeId() . 'mandatoryFieldError', $error);

                                $correspondenceTypeGroup = strtolower(
                                    $ctsHelper->getCorrespondenceTypeGroup($ctsCase->getCorrespondenceType())
                                );

                                // shows the entered data on view page.
                                $ctsCaseRepository->update($ctsCase, "required_for_$correspondenceTypeGroup");
                                return $this->redirect(
                                    $this->generateUrl(
                                        'homeoffice_cts_case_edit',
                                        array(
                                            'nodeRef' => $nodeRef
                                        )
                                    )
                                );
                            }
                        }
                    }
                }

                $valid = (0 === count($ctsCase->getCaseMandatoryFields()))
                    ? $ctsCaseForm->isValid()
                    : (
                        $ctsCaseForm->isValid() &
                        $formClass->hasRequiredFormFields(
                            $ctsCaseForm,
                            $ctsCase->getCaseStatus(),
                            $ctsCase->getCaseTask()
                        )
                    );

                if (true === (bool)$valid) {
                    $unsavedChanges = $this->checkForUnsavedChanges($ctsCaseForm);
                    if (count($unsavedChanges) > 0) {
                        foreach ($unsavedChanges as $value) {
                            $saveWarningMessage = $saveWarningMessage . ' - ' . $value . "\n";
                        }
                        if (isset($unsavedChanges['GroupedCasesMessage'])) {
                            $groupedCaseError = $unsavedChanges['GroupedCasesMessage'];
                        }
                        if (isset($unsavedChanges['LinkedCasesMessage'])) {
                            $linkedCaseError = $unsavedChanges['LinkedCasesMessage'];
                        }
                        if (isset($unsavedChanges['UploadedDocumentMessage'])) {
                            $error = new FormError($unsavedChanges['UploadedDocumentMessage']);
                            $ctsCaseForm->get('newDocument')->get('file')->addError($error);
                        }
                        if (isset($unsavedChanges['MinuteMessage'])) {
                            $error = new FormError($unsavedChanges['MinuteMessage']);
                            $ctsCaseForm->get('newMinute')->get('minuteContent')->addError($error);
                        }
                    } else {
                        //If it's a PQ case and the answering minister has been selected
                        // then get their member ID to save an answer to the PQ API
                        $ctsListHandler->calculatePqAnsweringMinisiterId($formTypeClass, $ctsCase);

                        $correspondenceTypeGroup = strtolower(
                            $ctsHelper->getCorrespondenceTypeGroup($ctsCase->getCorrespondenceType())
                        );
                        if (isset($originalComplex) && $originalComplex == 'true') {
                            $ctsCase->setComplex(true);
                        }
                        $ctsCaseRepository->update($ctsCase, "required_for_$correspondenceTypeGroup");
                        return $this->redirect($this->generateUrl(
                            'homeoffice_cts_case_view',
                            array('nodeRef' => $nodeRef)
                        ));
                    }
                }
            }

            try {
                if ($ctsCaseForm->get('addGroupedCases')->isClicked()) {
                    $result = $ctsCaseRepository->addGroupedCases($ctsCase);
                    if (is_bool($result) && $result) {
                        // if we are only adding grouped cases, add the ctsCase to the session and reload it on redirect
                        $this->setSessionParameter('ctsCase_' . $nodeRef, $ctsCase);
                    } else {
                        $this->setSessionParameter('groupedCaseError', $result);
                    }
                    return $this->redirect($this->generateUrl(
                        'homeoffice_cts_case_edit',
                        array('nodeRef' => $nodeRef)
                    ));
                }
            } catch (\OutOfBoundsException $e) {
            }

            if (method_exists($ctsCase, 'getGroupedCases')) {
                foreach ($ctsCase->getGroupedCases() as $idx => $groupedCase) {
                    if ($ctsCaseForm->get('removeGroupedCase_' . $groupedCase->getNodeId())->isClicked()) {
                        $result = $ctsCaseRepository->removeGroupedCases($ctsCase, array($groupedCase));
                        if (is_bool($result) && $result) {
                            // if we are only removing a grouped case, add the ctsCase
                            // to the session and reload it on redirect
                            // remove the grouped case from the array
                            // to avoid having to re-fetch from alfresco
                            $groupedCases = $ctsCase->getGroupedCases();
                            unset($groupedCases[$idx]);
                            $ctsCase->setGroupedCases($groupedCases);
                            $this->setSessionParameter('ctsCase_' . $nodeRef, $ctsCase);
                        } else {
                            $this->setSessionParameter('groupedCaseError', $result);
                        }
                        return $this->redirect($this->generateUrl(
                            'homeoffice_cts_case_edit',
                            array('nodeRef' => $nodeRef)
                        ));
                    }
                }
            }

            if ($ctsCaseForm->get('addLinkedCases')->isClicked()) {
                $result = $ctsCaseRepository->addLinkedCases($ctsCase);
                if (is_bool($result) && $result) {
                    // if we are only adding grouped cases, add the ctsCase to the session and reload it on redirect
                    $this->setSessionParameter('ctsCase_' . $nodeRef, $ctsCase);
                } else {
                    $this->setSessionParameter('linkedCaseError', $result);
                }
                return $this->redirect($this->generateUrl(
                    'homeoffice_cts_case_edit',
                    array('nodeRef' => $nodeRef)
                ));
            }
        }

        return $this->render(
            $featureToggle->get()
                ? 'HomeOfficeCtsBundle:Case/Guft:edit.html.twig'
                : 'HomeOfficeCtsBundle:Case:edit.html.twig',
            [
                'ctsCase'                   => $ctsCase,
                'ctsCaseForm'               => $ctsCaseForm->createView(),
                'ctsHelper'                 => $ctsHelper,
                'formPurpose'               => 'edit',
                'displayMemberStopListIcon' => $displayMemberStopListIcon,
                'displayTopicStopListIcon'  => $displayTopicStopListIcon,
                'stopList'                  => $stopList,
                'topicList'                 => $topicList,
                'pqTopicStopList'           => $pqTopicStopList,
                'errorMsg'                  => $errorMsg,
                'navClass'                  => 'case_creation',
                'documentTemplates'         => $documentTemplates,
                'documentErrorMsg'          => $addDocumentError,
                'groupedCaseError'          => $groupedCaseError,
                'linkedCaseError'           => $linkedCaseError,
                'saveWarningMessage'        => $saveWarningMessage,
                'memberList'                => $ctsListHandler->getList('ctsMemberList'),
                'caseTypeText'              => TypeClassMaps::getName($ctsCase->getCorrespondenceType()),
                'ctsCaseMinuteFilters'      => array_flip($filters),
                'nodeRef'                   => $nodeRef
            ]
        );
    }

    /**
     * @param CtsCaseForm $ctsCaseForm
     * @return array
     */
    private function checkForUnsavedChanges($ctsCaseForm)
    {
        $unsavedChanges = array();
        if ($ctsCaseForm->getData()->getNewMinute()->getMinuteContent() != null) {
            $unsavedChanges['MinuteMessage'] = 'Unsaved minute';
        }
        if ($ctsCaseForm->getData()->getNewDocument()->getFile() != null) {
            $unsavedChanges['UploadedDocumentMessage'] = 'Unsaved uploaded document';
        }
        if (method_exists($ctsCaseForm->getData(), 'getUinsToGroup')) {
            if ($ctsCaseForm->getData()->getUinsToGroup() != null) {
                $unsavedChanges['GroupedCasesMessage'] = 'Unsaved UINs';
            }
        }
        if (method_exists($ctsCaseForm->getData(), 'getHrnsToLink')) {
            if ($ctsCaseForm->getData()->getHrnsToLink() != null) {
                $unsavedChanges['LinkedCasesMessage'] = 'Unsaved linked cases';
            }
        }
        return $unsavedChanges;
    }

    /**
     * @Template
     * @Route("/cases/upload")
     * @Method({"GET", "POST"})
     */
    public function uploadAction(Request $request)
    {
        if (false === $this->get('security.context')->isGranted('create', $this->getUser())) {
            $this->setSessionParameter('errorMsg', 'You do not have permission to upload a feed');
            return $this->redirect($this->generateUrl('homeoffice_cts_home_home'));
        }
        $validationMessage = null;
        $validationCssClass = null;
        $ctsTsoFeed = new CtsTsoFeed();
        $ctsCaseUploadForm = $this->createForm(new CtsCaseUploadType(), $ctsTsoFeed);

        $ctsCaseUploadForm->handleRequest($request);

        if ($ctsCaseUploadForm->isValid()) {
            $ctsCaseTsoFeedRepository = $this->get('home_office_alfresco_api.cts_case_tso_feed_upload.repository');

            $response = $ctsCaseTsoFeedRepository->upload($ctsTsoFeed);
            $responseBody = json_decode($response->getBody()->__toString());

            if ($response->getStatusCode() != "201") {
                 $validationCssClass = 'error';
                $validationMessage = $responseBody->message;
            } else {
                $validationCssClass = 'success';
                $validationMessage = implode("\n", $responseBody->URN);
            }
        }

        return array(
            'uploadForm' => $ctsCaseUploadForm->createView(),
            'validationMessage' =>  $validationMessage,
            'validationCssClass' => $validationCssClass
        );
    }
}

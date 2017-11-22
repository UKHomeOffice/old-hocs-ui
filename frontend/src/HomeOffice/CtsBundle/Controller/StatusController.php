<?php

namespace HomeOffice\CtsBundle\Controller;

use HomeOffice\CtsBundle\Controller\CtsController as Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use HomeOffice\CtsBundle\Form\Type\CtsViewCaseType;
use HomeOffice\CtsBundle\Form\Type\AllocateUserType;
use GuzzleHttp\Exception\RequestException;

class StatusController extends Controller
{

    const ALLOCATION_NO_UNIT_OR_TEAM_ERROR = "Allocation not allowed, this user is not part of a unit or team";

    /**
     * @Template
     * @Route("/status/allocate")
     * @Method({"POST"})
     */
    public function allocateAction(Request $request)
    {
        $workspace = $this->container->getParameter('home_office_alfresco_api.workspace');
        $store = $this->container->getParameter('home_office_alfresco_api.store');
        $ctsListHandler = $this->get('home_office_list.handler');
        $ctsHelper = $this->get('home_office_alfresco_api.cts_case.cts_helper');
        $ctsWorkflowRepository = $this->get('home_office_alfresco_api.cts_workflow.repository');

        $allocateTo = $request->request->get('allocation')['allocateTo'];
        $unit = $request->request->get('allocation')['assignedUnit'];
        $team = $request->request->get('allocation')['assignedTeam'];
        $user = $request->request->get('allocation')['assignedUser'];

        // pick this out the request so we can create the correct case class type
        $correspondenceType = $request->request->get('allocation')['correspondenceType'];
        $transition = $request->request->get('allocation')['statusChange'];

        $caseClass = $ctsHelper->getCaseClassFromType($correspondenceType);
        $ctsCase = new $caseClass($workspace, $store);

        $ctsCase->setAssignedUnit($unit);
        $ctsCase->setAssignedTeam($team);
        $ctsCase->setAssignedUser($user);

        $userTeams = $this->getUser()->getTeams();
        $userUnits = $this->getUser()->getUnits();

        $allocateUserForm = $this->createForm(
            new AllocateUserType($ctsListHandler, $userUnits, $userTeams),
            $ctsCase,
            array('action' => $this->generateUrl('homeoffice_cts_status_allocate'))
        );

        $allocateUserForm->handleRequest($request);

        // Allocation cancelled, return to case view
        if ($allocateUserForm->get('CancelAllocateButton')->isClicked()) {
            return $this->redirect($this->generateUrl(
                'homeoffice_cts_case_view',
                array('nodeRef' => $ctsCase->getNodeId())
            ));
        }

        if ($allocateUserForm->get('SetUnit')->isClicked()) {
            $this->setSessionParameter('allocateUnitValue', $ctsCase->getAssignedUnit());
        }

        if ($allocateUserForm->get('SetTeam')->isClicked()) {
            $this->setSessionParameter('allocateUnitValue', $ctsCase->getAssignedUnit());
            $this->setSessionParameter('allocateTeamValue', $ctsCase->getAssignedTeam());
        }

        //Fast ajax allocation from the team queues
        if ('Me' === $allocateTo && $request->isXmlHttpRequest()) {
            $isAllocated = $this->setCaseAssignedUnitAndTeam($ctsCase);
            if (!$isAllocated) {
                $this->setSessionParameter('errorMsg', self::ALLOCATION_NO_UNIT_OR_TEAM_ERROR);
                return $this->redirect(
                    $this->generateUrl(
                        'homeoffice_cts_case_view',
                        array('nodeRef' => $ctsCase->getNodeId(), 'actionType' => 'allocate')
                    )
                );
            }

            $ctsWorkflowRepository->updateWorkflow($ctsCase, $transition);

            return new JsonResponse(array('success' => true));
        }

        if ($allocateUserForm->get('AllocateButton')->isClicked()) {
            if ($allocateTo == 'Me') {
                $isAllocated = $this->setCaseAssignedUnitAndTeam($ctsCase);
                if (!$isAllocated) {
                    $this->setSessionParameter('errorMsg', self::ALLOCATION_NO_UNIT_OR_TEAM_ERROR);
                    return $this->redirect(
                        $this->generateUrl(
                            'homeoffice_cts_case_view',
                            array('nodeRef' => $ctsCase->getNodeId(), 'actionType' => 'allocate')
                        )
                    );
                }
            }
            // call update even if it's Reallocate, in order to update the assigned unit/team/user
            $ctsWorkflowRepository->updateWorkflow($ctsCase, $transition);
            if ($allocateTo == 'Me') {
                return $this->redirect($this->generateUrl(
                    'homeoffice_cts_case_view',
                    array('nodeRef' => $ctsCase->getNodeId())
                ));
            } else {
                return $this->redirect($this->generateUrl('homeoffice_cts_home_home'));
            }
        }
        // if the user does not click on cancel or allocate buttons we must
        // go back to the allocate screen, maintaining the query parameters
        $urlParams = array(
            'nodeRef' => $ctsCase->getNodeId(),
            'actionType' => 'allocate'
        );
        if ($transition != null) {
            $urlParams['statusChange'] = $transition;
        }
        return $this->redirect($this->generateUrl('homeoffice_cts_case_view', $urlParams));
    }

    /**
     * @Template
     * @Route("/status/change")
     * @Method({"POST"})
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function updateStatusAction(Request $request)
    {
        $workspace = $this->container->getParameter('home_office_alfresco_api.workspace');
        $store = $this->container->getParameter('home_office_alfresco_api.store');
        $ctsWorkflowRepository = $this->get('home_office_alfresco_api.cts_workflow.repository');
        $ctsHelper = $this->get('home_office_alfresco_api.cts_case.cts_helper');
        $ctsCaseRepository             = $this->get('home_office_alfresco_api.cts_case.repository');

        $deleteButtonExists = false;
        $nextStateButtonExists = false;
        $rejectButtonExists = false;

        $nextStateButtonPosition = $this->getClickedNextStateButtonPosition($request->request->get('view_case_type'));

        if (array_key_exists('DeleteButton', $request->request->get('view_case_type'))) {
            $deleteButtonExists = true;
        }
        if ($nextStateButtonPosition !== false) {
            $nextStateButtonExists = true;
        }
        if (array_key_exists('RejectStateButton', $request->request->get('view_case_type'))) {
            $rejectButtonExists = true;
        }

        $correspondenceType = $request->request->get('view_case_type')['correspondenceType'];
        $transition = null;
        if ($nextStateButtonExists &&
            isset($request->request->get('view_case_type')['nextStateTransition'.$nextStateButtonPosition])) {
            $transition = $request->request->get('view_case_type')['nextStateTransition'.$nextStateButtonPosition];
        } elseif ($rejectButtonExists && isset($request->request->get('view_case_type')['rejectStateTransition'])) {
            $transition = $request->request->get('view_case_type')['rejectStateTransition'];
        }

        $caseClass = $ctsHelper->getCaseClassFromType($correspondenceType);
        $ctsCase = new $caseClass($workspace, $store);

        $ctsCase->setCorrespondenceType($correspondenceType);
        $ctsCase->setCaseStatus($request->request->get('view_case_type')['caseStatus']);
        $ctsCase->setCaseTask($request->request->get('view_case_type')['caseTask']);
        $ctsCase->setAssignedUser($request->request->get('view_case_type')['assignedUser']);

        $canDelete = $request->request->get('view_case_type')['canDeleteObject'];
        if ($canDelete == 1) {
            $ctsCase->setCanDeleteObject(true);
        }

        $ctsViewCaseForm = $this->createForm(
            new CtsViewCaseType($this->get('security.context'), $request->query->get('statusChange')),
            $ctsCase,
            array('action' => $this->generateUrl('homeoffice_cts_status_updatestatus'))
        );

        $ctsViewCaseForm->handleRequest($request);

        if ($deleteButtonExists && $ctsViewCaseForm->get('DeleteButton')->isClicked()) {
            $ctsCase->setAssignedUser(null);
            $transition = 'Deleted';
        }

        $nodeRef = $ctsCase->getNodeId();
        $ctsCaseObj = $ctsCaseRepository->getCase($nodeRef);

        $mandatoryFields = $ctsCaseObj->getCaseWorkflowStatus()->getMandatoryFields();

        if ($mandatoryFields != null) {
            $error = '';

            foreach ($mandatoryFields as $mandatoryField) {
                $getter = 'get' . ucfirst($mandatoryField->getName());

                if ($ctsCaseObj->$getter() == null) {
                    $error .= $mandatoryField->getMessage() . "\n";
                }
            }

            if ($error != '') {
                $this->setSessionParameter($ctsCaseObj->getNodeId() . 'mandatoryFieldError', $error);

                return $this->redirect(
                    $this->generateUrl(
                        'homeoffice_cts_case_view',
                        array(
                            'nodeRef' => $ctsCaseObj->getNodeId()
                        )
                    )
                );
            }
        }

        if ($transition != null) {
            try {
                $ctsWorkflowRepository->updateWorkflow($ctsCase, $transition);
            } catch (RequestException $exception) {
                $response = json_decode($exception->getResponse()->getBody()->__toString());
                $exceptionText = $response->callstack[1];
                $position = strpos($exceptionText, 'PQApiException');
                if ($position !== false) {
                    $message = substr($exceptionText, $position + 16);
                    $this->setSessionParameter('errorMsg', $message);

                    return $this->redirect($this->generateUrl(
                        'homeoffice_cts_case_view',
                        array('nodeRef' => $ctsCase->getNodeId())
                    ));
                }
            }
        }

        return $this->redirect(
            $this->generateUrl('homeoffice_cts_home_home')
        );
    }

    /**
     * Work out the value of the position of the next state button that was clicked
     * @param type $values
     * @return boolean|int
     */
    private function getClickedNextStateButtonPosition($values)
    {
        $i = 1;
        foreach ($values as $value) {
            if (strpos(key($values), 'NextStateButton') !== false) {
                if (key($values) == 'NextStateButton'.$i) {
                    return $i;
                }
                $i++;
            }
        }
        return false;
    }

    /**
     * @param CtsCase $ctsCase
     * @return boolean
     */
    private function setCaseAssignedUnitAndTeam($ctsCase)
    {
        $listHandler = $this->get('home_office_list.handler');
        $myTeam = null;
        $myUnit = null;
        if (!empty($this->getUser()->getTeams())) {
            $myTeam = $this->getUser()->getTeams()[0];
            $ctsCase->setAssignedTeam($myTeam->getAuthorityName());
        }
        if (!empty($this->getUser()->getUnits())) {
            $myUnit = $this->getUser()->getUnits()[0];
        }
        if ($myTeam != null && $myUnit == null) {
            // If user is in a team but dont know the unit
            $ctsCase->setAssignedUnit($listHandler->getUnitFromTeam($myTeam)->getAuthorityName());
        } elseif ($myUnit != null) {
            // If user is only in a unit and not a team
            $ctsCase->setAssignedUnit($myUnit->getAuthorityName());
        } else {
            //User is not part of a unit or team so don't do anything
            return false;
        }
        $ctsCase->setAssignedUser($this->getUser()->getUserName());

        return true;
    }
}

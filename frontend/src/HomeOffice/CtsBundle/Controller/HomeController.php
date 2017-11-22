<?php

namespace HomeOffice\CtsBundle\Controller;

use HomeOffice\AlfrescoApiBundle\Repository\CtsCaseRepository;
use HomeOffice\AlfrescoApiBundle\Service\Paginator;
use HomeOffice\CtsBundle\Controller\CtsController as Controller;
use HomeOffice\CtsBundle\Form\GuftType\FilterQueueType as GuftFilterQueueType;
use HomeOffice\CtsBundle\Form\Type\FilterQueueType;
use HomeOffice\ListBundle\Service\ListHandler;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class HomeController
 *
 * @package HomeOffice\CtsBundle\Controller
 */
class HomeController extends Controller
{
    /**
     * @param Request $request
     * @return string
     */
    private function getQueueValueFromRequest(Request $request)
    {
        $queueValue = $request->query->get('filterQueue')['queue'];
        if (!isset($queueValue)) {
            $queueValue = 'todoQueue';
        }
        return $queueValue;
    }

    /**
     * Get the users teams.
     * @param array $teams
     * @return array
     */
    private function getUserTeamsInUnit($teams = [])
    {
        $teamsInUnit = array();

        if (empty($teams)) {
            $teams = $this->getUser()->getTeams();
        }

        if (!empty($teams)) {
            foreach ($teams as $team) {
                $teamsInUnit[$team->getAuthorityName()] = $team->getDisplayName();
            }
        }
        return $teamsInUnit;
    }

    /**
     * Get the teams under the user units.
     * @param ListHandler $listHandler
     * @return array
     */
    private function getUserUnitsAndTeams($listHandler)
    {
        $teamsInUnit = array();
        $units = $this->getUser()->getUnits();
        if (!empty($units)) {
            foreach ($units as $unit) {
                foreach ($listHandler->getTeamsFromUnit($unit->getAuthorityName()) as $team) {
                    $teamsInUnit[$team->getAuthorityName()] = $team->getDisplayName();
                }
            }
        }
        return $teamsInUnit;
    }

    /**
     * Get the teams under the user units.
     * @param ListHandler $listHandler
     * @return array
     */
    private function authorityGroupsToArray($authorityGroups)
    {
        $teamsInUnit = array();
        $rejectionArray = ["GROUP_Manager", "GROUP_Units",  "GROUP_NO EMAILS", "GROUP_ALFRESCO_ADMINISTRATORS"];

        if (!empty($authorityGroups)) {
            foreach ($authorityGroups as $group) {
                if (!in_array($group->getAuthorityName(), $rejectionArray)) {
                    $teamsInUnit[$group->getAuthorityName()] = $group->getDisplayName();
                }
            }
        }
        return $teamsInUnit;
    }

    /**
     *
     * @param CtsCaseRepository $ctsCaseRepository
     * @param string $queueValue
     * @param array $ctsCaseStatusArray
     * @param array $ctsTaskStatusArray
     * @param array $ctsTeamArray
     * @param array $ctsAssignedUserArray
     * @param array $ctsCorrespondenceTypeArray
     * @param Paginator $paginator
     * @param string $orderByField
     * @param string $orderByDirection
     * @return array
     */
    private function getQueue(
        $ctsCaseRepository,
        $queueValue,
        $ctsCaseStatusArray,
        $ctsTaskStatusArray,
        $ctsTeamArray,
        $ctsAssignedUserArray,
        $ctsCorrespondenceTypeArray,
        $paginator,
        $orderByField,
        $orderByDirection
    ) {
        $user = $this->getUser();
        $ctsQueue = array();
        switch ($queueValue) {
            case 'todoQueue':
                $ctsQueue = $ctsCaseRepository->getTodoQueue(
                    $ctsCaseStatusArray,
                    $ctsTaskStatusArray,
                    $ctsCorrespondenceTypeArray,
                    $paginator,
                    $orderByField,
                    $orderByDirection
                );
                break;
            case 'teamQueue':
                $ctsQueue = $ctsCaseRepository->getTeamQueue(
                    $user,
                    $ctsCaseStatusArray,
                    $ctsTaskStatusArray,
                    $ctsTeamArray,
                    $ctsAssignedUserArray,
                    $ctsCorrespondenceTypeArray,
                    $paginator,
                    $orderByField,
                    $orderByDirection
                );
                break;
            case 'unitQueue':
                $ctsQueue = $ctsCaseRepository->getUnitQueue(
                    $user,
                    $ctsCaseStatusArray,
                    $ctsTaskStatusArray,
                    $ctsTeamArray,
                    $ctsAssignedUserArray,
                    $ctsCorrespondenceTypeArray,
                    $paginator,
                    $orderByField,
                    $orderByDirection
                );
                break;
        }
        return $ctsQueue;
    }

    /**
     *
     * @param CtsCaseRepository $ctsCaseRepository
     * @param string $queueValue
     * @param array $ctsCaseStatusArray
     * @param array $ctsTaskStatusArray
     * @param array $ctsTeamArray
     * @param array $ctsAssignedUserArray
     * @param array $ctsCorrespondenceTypeArray
     * @return mixed
     */
    private function getQueueExport(
        $ctsCaseRepository,
        $queueValue,
        $ctsCaseStatusArray,
        $ctsTaskStatusArray,
        $ctsTeamArray,
        $ctsAssignedUserArray,
        $ctsCorrespondenceTypeArray
    ) {
        $user = $this->getUser();
        $userName = $user->getUserName();
        $timestamp = new \DateTime();

        switch ($queueValue) {
            case 'todoQueue':
                $fileName = 'todoQueueReport' . '_' . $userName . '_' . $timestamp->format('dmYHis');
                $searchQuery = $this->getSessionParameter("todoQueueQuery_$userName");
                if ($searchQuery == null) {
                    $exportResult = $ctsCaseRepository->exportTodoQueue(
                        $ctsCaseStatusArray,
                        $ctsTaskStatusArray,
                        $ctsCorrespondenceTypeArray,
                        $fileName
                    );
                } else {
                    $exportResult = $ctsCaseRepository->exportQueueFromQuery($searchQuery, $fileName);
                }
                break;
            case 'teamQueue':
                $fileName = 'teamQueueReport' . '_' . $userName . '_' . $timestamp->format('dmYHis');
                $searchQuery = $this->getSessionParameter("teamQueueQuery_$userName");
                if ($searchQuery == null) {
                    $exportResult = $ctsCaseRepository->exportTeamQueue(
                        $user,
                        $ctsCaseStatusArray,
                        $ctsTaskStatusArray,
                        $ctsTeamArray,
                        $ctsAssignedUserArray,
                        $ctsCorrespondenceTypeArray,
                        $fileName
                    );
                } else {
                    $exportResult = $ctsCaseRepository->exportQueueFromQuery($searchQuery, $fileName);
                }
                break;
            case 'unitQueue':
                $fileName = 'unitQueueReport' . '_' . $userName . '_' . $timestamp->format('dmYHis');
                $searchQuery = $this->getSessionParameter("unitQueueQuery_$userName");
                if ($searchQuery == null) {
                    $exportResult = $ctsCaseRepository->exportUnitQueue(
                        $user,
                        $ctsCaseStatusArray,
                        $ctsTaskStatusArray,
                        $ctsTeamArray,
                        $ctsAssignedUserArray,
                        $ctsCorrespondenceTypeArray,
                        $fileName
                    );
                } else {
                    $exportResult = $ctsCaseRepository->exportQueueFromQuery($searchQuery, $fileName);
                }
                break;
        }
        return $exportResult;
    }

    /**
     *
     * @param array $ctsQueue
     * @param array $unitList
     */
    private function decodeValues(& $ctsQueue, $unitList)
    {
        foreach ($ctsQueue as $ctsCase) {
            if (array_key_exists($ctsCase->getMarkupUnit(), $unitList)) {
                $ctsCase->setMarkupUnit($unitList[$ctsCase->getMarkupUnit()]);
            }
        }
    }

    /**
     * @return array
     */
    private function getUnitAndTeamList()
    {
        $ctsHelper = $this->get('home_office_alfresco_api.cts_case.cts_helper');
        $listHandler = $this->get('home_office_list.handler');
        $unitAndTeamList = $ctsHelper->makeFlatMap($listHandler->getList('ctsUnitAndTeamList'));
        return $unitAndTeamList;
    }

    /**
     *
     * @Template
     * @Route("/home")
     * @Method({"GET","POST"})
     *
     * @param Request $request
     * @return array|BinaryFileResponse
     */
    public function homeAction(Request $request)
    {
        return $this->todoListAction($request);
    }

    /**
     *
     * @Template
     * @Route("/allocateToMe")
     * @Method({"GET","POST"})
     *
     * @param Request $request
     * @return array|BinaryFileResponse
     */
    public function allocateToMeAction(Request $request)
    {

        $nodeRef = $request->query->get('nodeRef');

        $ctsCaseRepository = $this->get('home_office_alfresco_api.cts_case.repository');
        $ctsWorkflowRepository = $this->get('home_office_alfresco_api.cts_workflow.repository');

        $ctsCase = $ctsCaseRepository->getCase($nodeRef);
        $ctsCase = $ctsCaseRepository->assignCaseToPerson($ctsCase, $this->getUser());
        $ctsWorkflowRepository->updateWorkflow(
            $ctsCase,
            'Reallocate'
        );

        $params = $request->query->all();

        unset($params['nodeRef']);

        $redirectUrl = $this->generateUrl('homeoffice_cts_home_home', $params);

        if ($request->isXmlHttpRequest()) {
            $response = new Response(json_encode([
                'success'  => true,
                'redirect' => $redirectUrl,
                'message'  => '',
            ]));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        } else {
            return $this->redirect($redirectUrl);
        }
    }

    /**
     * New Home Action
     *
     * This still requires plenty of refactoring to move away from the "Fat controller" principle, but due
     * to time pressures, we've had to leave around half of the old code in here.
     *
     * @since 2016-08-05
     * @author Adam Lewis <adam.lewis@
     * @param Request $request
     * @return Response
     * @todo Refactor out unrelated code to make this method thin and comply with SOLID and DRY principles.
     */
    private function todoListAction(Request $request)
    {

        list($listHandler, $ctsListsRepository, $ctsCaseRepository, $ctsHelper, $todoConsumer) = [
            $this->get('home_office_list.handler'),
            $this->get('home_office_alfresco_api.cts_lists.repository'),
            $this->get('home_office_alfresco_api.cts_case.repository'),
            $this->get('home_office_alfresco_api.cts_case.cts_helper'),
            $this->get('home_office_alfresco_api.consumer.todo_list')
        ];

        $queueValue = $request->query->get('filterQueue[queue]', 'todoQueue', true);

        $listHandler->loadListsOnLogin();

        $assignedUnits = null;

        $ctsCaseStatusArray = $ctsTaskStatusArray = $ctsTeamArray = $ctsAssignedUserArray
            = $ctsCorrespondenceTypeArray = [];

        $teamsList = $this->authorityGroupsToArray($this->getUser()->getTeams());
        $unitsList = $this->authorityGroupsToArray($this->getUser()->getUnits());
        $currentGroupList = null;

        if ($queueValue === 'unitQueue') {
            $assignedUnits = array_keys($unitsList);
            $currentGroupList = $unitsList;
        }


        if ($queueValue === 'teamQueue') {
            $ctsTeamArray = array_keys($teamsList);
            $currentGroupList = $teamsList;
        }

        if ($queueValue === 'todoQueue') {
            $ctsAssignedUserArray = [$this->getUser()->getUserName()];
        }

        $paginator = $this->setupPaginator($request->query, 'homeoffice_cts_home_home', 'paginate_todo');

        $teamFilterValues = array();
        $teamMemberFilterValues = array();

        if ($queueValue == 'teamQueue') {
            $teamFilterValues = $teamsList;
            if (empty($teamFilterValues)) {
                $teamFilterValues = $this->getUserTeamsInUnit();
            }
            foreach ($ctsListsRepository->getPeopleInUsersTeams() as $person) {
                $teamMemberFilterValues[$person->getUserName()] = $person->getFullName();
            }
        }

        $queueFilterType = new GuftFilterQueueType($queueValue, $teamFilterValues, $teamMemberFilterValues);

        $ctsHomeFiltersForm = $this->createForm($queueFilterType)->handleRequest($request);

        $filterData = $ctsHomeFiltersForm->getData();

        // unset the queue filter so we can test if any filters are set - @todo wot?
        unset($filterData['queue']);

        $showFilters = $filterData != null && !empty(array_filter($filterData));

        if ($ctsHomeFiltersForm->get('clear')->isClicked()) {
            $ctsHomeFiltersForm = $this->createForm($queueFilterType);
            $filterData = [];
        } else {
            $ctsCaseStatusArray = isset($filterData['status']) ? $filterData['status'] : [];
            $ctsTaskStatusArray = isset($filterData['task']) ? $filterData['task'] : [];
            $ctsTeamArray = !empty($filterData['team']) ? $filterData['team'] : $ctsTeamArray;
            $ctsAssignedUserArray = isset($filterData['assignedUser']) ? $filterData['assignedUser'] : $ctsAssignedUserArray;
            $ctsCorrespondenceTypeArray = isset($filterData['correspondenceType']) ? $filterData['correspondenceType'] : [];
        }

        if ($ctsHomeFiltersForm->get('exportQueue')->isClicked()) {
            $exportResult = $this->getQueueExport(
                $ctsCaseRepository,
                $queueValue,
                $ctsCaseStatusArray,
                $ctsTaskStatusArray,
                $ctsTeamArray,
                $ctsAssignedUserArray,
                $ctsCorrespondenceTypeArray
            );
            if (is_bool($exportResult) && !$exportResult) {
                $errorMsg = 'Unable to generate report for queue.';
            } else {
                return $this->generateExportFileResponse($exportResult);
            }
        }

        // @todo abstract this.
        $orderByField = 'caseResponseDeadline';
        $orderByDirection = 'ASC';

        if ($request->query->get('orderBy') === 'deadlineDate') {
            $orderByDirection = $request->query->get('orderDirection');
        }

        if ($queueValue === 'unitQueue' && empty($assignedUnits)) {
            $ctsQueue = null;
        } elseif ($queueValue === 'teamQueue' && empty($ctsTeamArray)) {
            $ctsQueue = null;
        } else {
            // @todo move to consumer.
            $ctsQueue = $todoConsumer->get([
                'offset' => $paginator->calculateSkipCount(),
                'limit' => $paginator->getPageSize(),
                'sortingOrder' => $orderByDirection,
                'sortingOrderBy' => $orderByField,
                'caseStatus' => empty($ctsCaseStatusArray) ? false : implode(',', $ctsCaseStatusArray),
                'userNames' => empty($ctsAssignedUserArray) ? false : implode(',', $ctsAssignedUserArray),
                'caseTasks' => empty($ctsTaskStatusArray) ? false : implode(',', $ctsTaskStatusArray),
                'caseTypes' => empty($ctsCorrespondenceTypeArray) ? false : implode(',', $ctsCorrespondenceTypeArray),
                'assignedUnits' => empty($assignedUnits) ? false : implode(',', $assignedUnits),
                'assignedTeams' =>  empty($ctsTeamArray) ? false : implode(',', $ctsTeamArray),
            ], $listHandler);

            $paginator->setTotalResults($ctsQueue['totalResults']);
            $paginator->setPageNumber($request->query->get('pageNumber'));

        }

        // @todo make this a global json thing.
        if ($request->isXmlHttpRequest()) {
            $unitAndTeamList = $this->getUnitAndTeamList();
            $response = new Response(json_encode([
                'ctsQueue' => $this->renderView(
                    'HomeOfficeCtsBundle:Queue:guftCaseTable.html.twig',
                    [
                        'ctsQueue' => $ctsQueue,
                        'orderByField' => $orderByField,
                        'orderByDirection' => $orderByDirection,
                        'queueValue' => $queueValue,
                        'ctsHelper' => $ctsHelper,
                        'paginator' => $paginator,
                        'userAndTeamList' => $unitAndTeamList,
                    ]),
            ]));

            // @BHERC-1731 Back Button breaks the page
            // http://stackoverflow.com/questions/16753142/redirecting-and-preventing-cache-in-symfony2
            $response->setPrivate();
            $response->setMaxAge(0);
            $response->setSharedMaxAge(0);
            $response->headers->addCacheControlDirective('must-revalidate', true);
            $response->headers->addCacheControlDirective('no-store', true);
            // END @BHERC-1731

            $response->headers->set('Content-Type', 'application/json');
            
            return $response;

        } else {
            $unitAndTeamList = $this->getUnitAndTeamList();
            return $this->render(
                'HomeOfficeCtsBundle:Home:guftHome.html.twig',
                [
                    'queueValue' => $queueValue,
                    'ctsHelper' => $ctsHelper,
                    'ctsQueue' => $ctsQueue,
                    'ctsHomeFiltersForm' => $ctsHomeFiltersForm->createView(),
                    'navClass' => 'to_do_list',
                    'showFilters' => $showFilters,
                    'currentUser' => $this->getUser(),
                    'paginator' => $paginator,
                    'errorMsg' => isset($errorMsg) ? $errorMsg: null,
                    'successMsg' => false, // This is never used in this context?
                    'orderByField' => $orderByField,
                    'orderByDirection' => $orderByDirection,
                    'userAndTeamList' => $unitAndTeamList,
                    'currentGroupList' => $currentGroupList
                ]
            );
        }
    }

    /**
     * @param Request $request
     * @return \HomeOffice\CtsBundle\Controller\BinaryFileResponse|Response
     * @throws \Exception
     * @deprecated
     */
    private function oldHomeAction(Request $request)
    {
        $errorMsg = $this->getAndClearValueInSession('errorMsg');
        $successMsg = $this->getAndClearValueInSession('successMsg');

        // load services
        $listHandler = $this->get('home_office_list.handler');
        $queryHelper = $this->get('home_office_alfresco_api.cts_case.query_helper');
        $ctsListsRepository = $this->get('home_office_alfresco_api.cts_lists.repository');
        $ctsCaseRepository = $this->get('home_office_alfresco_api.cts_case.repository');
        $ctsHelper = $this->get('home_office_alfresco_api.cts_case.cts_helper');

        $listHandler->loadListsOnLogin();
        $paginator = $this->setupPaginator($request->query, 'homeoffice_cts_home_home', 'paginate_todo');

        $queueValue = $this->getQueueValueFromRequest($request);

        $teamFilterValues = array();
        $teamMemberFilterValues = array();

        if ($queueValue == 'teamQueue') {
            $teamFilterValues = $this->getUserUnitsAndTeams($listHandler);
            if (empty($teamFilterValues)) {
                $teamFilterValues = $this->getUserTeamsInUnit();
            }
            foreach ($ctsListsRepository->getPeopleInUsersTeams() as $person) {
                $teamMemberFilterValues[$person->getUserName()] = $person->getFullName();
            }
        }

        $queueFilterType = new FilterQueueType($queueValue, $teamFilterValues, $teamMemberFilterValues);

        $ctsHomeFiltersForm = $this->createForm($queueFilterType);
        $ctsHomeFiltersForm->handleRequest($request);

        $filterData = $ctsHomeFiltersForm->getData();
        // unset the queue filter so we can test if any filters are set
        unset($filterData['queue']);
        $showFilters = $filterData != null && !empty(array_filter($filterData));

        $ctsCaseStatusArray = array();
        $ctsTaskStatusArray = array();
        $ctsTeamArray = array();
        $ctsAssignedUserArray = array();
        $ctsCorrespondenceTypeArray = array();

        if ($ctsHomeFiltersForm->get('clear')->isClicked()) {
            $ctsHomeFiltersForm = $this->createForm($queueFilterType);
        } else {
            if (isset($filterData['status'])) {
                $ctsCaseStatusArray = $filterData['status'];
            }
            if (isset($filterData['task'])) {
                $ctsTaskStatusArray = $filterData['task'];
            }
            if (isset($filterData['team'])) {
                $ctsTeamArray = $filterData['team'];
            }
            if (isset($filterData['assignedUser'])) {
                $ctsAssignedUserArray = $filterData['assignedUser'];
            }
            if (isset($filterData['correspondenceType'])) {
                $ctsCorrespondenceTypeArray = $filterData['correspondenceType'];
            }
        }

        if ($ctsHomeFiltersForm->get('exportQueue')->isClicked()) {
            $exportResult = $this->getQueueExport(
                $ctsCaseRepository,
                $queueValue,
                $ctsCaseStatusArray,
                $ctsTaskStatusArray,
                $ctsTeamArray,
                $ctsAssignedUserArray,
                $ctsCorrespondenceTypeArray
            );
            if (is_bool($exportResult) && !$exportResult) {
                $errorMsg = 'Unable to generate report for queue.';
            } else {
                return $this->generateExportFileResponse($exportResult);
            }
        }

        $orderArray = $queryHelper->orderByDateColumnHandler(
            $request->query->get('orderBy'),
            $request->query->get('orderDirection')
        );
        $orderByField = $orderArray[0];
        $orderByDirection = $orderArray[1];

        $ctsQueue = $this->getQueue(
            $ctsCaseRepository,
            $queueValue,
            $ctsCaseStatusArray,
            $ctsTaskStatusArray,
            $ctsTeamArray,
            $ctsAssignedUserArray,
            $ctsCorrespondenceTypeArray,
            $paginator,
            $orderByField,
            $orderByDirection

        );

        $this->decodeValues($ctsQueue, $listHandler->getList('ctsUnitList'));

        return $this->render(
            'HomeOfficeCtsBundle:Home:home.html.twig',
            [
                'queueValue' => $queueValue,
                'ctsHelper' => $ctsHelper,
                'ctsQueue' => $ctsQueue,
                'ctsHomeFiltersForm' => $ctsHomeFiltersForm->createView(),
                'navClass' => 'to_do_list',
                'showFilters' => $showFilters,
                'currentUser' => $this->getUser(),
                'paginator' => $paginator,
                'errorMsg' => $errorMsg,
                'successMsg' => $successMsg,
                'orderByField' => $orderByField,
                'orderByDirection' => $orderByDirection
            ]);
    }
}

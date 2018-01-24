<?php

namespace HomeOffice\AlfrescoApiBundle\Repository;

use GuzzleHttp\Client as Guzzle;
use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use HomeOffice\AlfrescoApiBundle\Entity\Person;
use HomeOffice\AlfrescoApiBundle\Exception\SavePermissionException;
use HomeOffice\AlfrescoApiBundle\Service\Paginator;
use HomeOffice\ListBundle\Service\ListHandler;
use HomeOffice\ProcessManagerAuthenticatorBundle\Security\SessionTicketStorage;
use HomeOffice\AlfrescoApiBundle\Service\AtomHelper;
use HomeOffice\AlfrescoApiBundle\Service\CTSHelper;
use HomeOffice\AlfrescoApiBundle\Service\QueryHelper;
use HomeOffice\AlfrescoApiBundle\Service\CaseStatus;
use HomeOffice\AlfrescoApiBundle\Factory\CtsCaseFactory;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class CtsCaseRepository
 *
 * @package HomeOffice\AlfrescoApiBundle\Repository
 */
class CtsCaseRepository
{
    /**
     * @var Guzzle
     */
    private $apiClient;

    /**
     * @var CtsCaseFactory
     */
    private $factory;

    /**
     * @var SessionTicketStorage
     */
    private $tokenStorage;

    /**
     * @var AtomHelper
     */
    private $atomHelper;

    /**
     * @var CTSHelper
     */
    private $ctsHelper;

    /**
     * @var QueryHelper
     */
    private $queryHelper;

    /**
     * @var string
     */
    private $workspace;

    /**
     * @var string
     */
    private $store;

    /**
     * @var array
     */
    private $caseProperties;

    /**
     * @var array
     */
    private $casePermissions;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var ListHandler
     */
    private $listHandler;

    /**
     * Constructor
     *
     * @param Guzzle               $apiClient
     * @param CtsCaseFactory       $ctsCaseFactory
     * @param SessionTicketStorage $tokenStorage
     * @param AtomHelper           $atomHelper
     * @param CTSHelper            $ctsHelper
     * @param QueryHelper          $queryHelper
     * @param string               $workspace
     * @param string               $store
     * @param array                $caseProperties
     * @param array                $casePermissions
     * @param Session              $session
     * @param ListHandler          $listHandler
     */
    public function __construct(
        Guzzle $apiClient,
        CtsCaseFactory $ctsCaseFactory,
        SessionTicketStorage $tokenStorage,
        AtomHelper $atomHelper,
        CTSHelper $ctsHelper,
        QueryHelper $queryHelper,
        $workspace,
        $store,
        $caseProperties,
        $casePermissions,
        Session $session,
        ListHandler $listHandler
    ) {
        $this->apiClient = $apiClient;
        $this->factory = $ctsCaseFactory;
        $this->tokenStorage = $tokenStorage;
        $this->atomHelper = $atomHelper;
        $this->queryHelper = $queryHelper;
        $this->ctsHelper = $ctsHelper;
        $this->workspace = $workspace;
        $this->store = $store;
        $this->caseProperties = $caseProperties;
        $this->casePermissions = $casePermissions;
        $this->session = $session;
        $this->listHandler = $listHandler;
    }

    /**
     * @param CtsCase $ctsCase
     *
     * @return bool
     */
    public function create(CtsCase $ctsCase)
    {
        $response = $this->apiClient->post('s/cmis/p/CTS/Cases/children', [
            'headers' => ['Content-Type' => 'application/atom+xml;type=entry'],
            'query'   => ['alf_ticket' => $this->tokenStorage->getAuthenticationTicket()],
            'body'    => $this->atomHelper->generateAtomEntry(
                $ctsCase,
                $ctsCase->getFolderName(),
                $this->caseProperties
            ),
        ]);

        if ($response->getStatusCode() != '201') {
            return false;
        }

        $case = $this->atomHelper->singleEntryFeedToArray(
            $response->getBody()->__toString(),
            'cmisra:object',
            $this->caseProperties,
            $this->casePermissions
        );

        $ctsCase->setId($case['id']);

        return true;
    }

    /**
     * @param CtsCase $ctsCase
     *
     * @return bool
     */
    public function update(CtsCase $ctsCase)
    {
        $nodeId = $ctsCase->getNodeId();

        $body = $this->atomHelper->generateAtomEntry(
            $ctsCase,
            $ctsCase->getFolderName(),
            $this->caseProperties
        );

        try {
            $response = $this->apiClient->put("s/api/node/$this->workspace/$this->store/$nodeId", [
                'headers' => ['Content-Type' => 'application/atom+xml;type=entry'],
                'query'   => ['alf_ticket' => $this->tokenStorage->getAuthenticationTicket()],
                'body'    => $body,
            ]);
        } catch (RequestException $e) {
            if ($e->getResponse()->getStatusCode() == 403) {
                throw new SavePermissionException('You do not have permissions to save this case');
            }
            return false;
        }

        if ($response->getStatusCode() != '201') {
            return false;
        }

        return true;
    }

    /**
     *
     * @param array $ctsCaseStatusArray
     * @param array $ctsTaskStatusArray
     * @param array $ctsCaseTypeArray
     * @param Paginator $paginator
     * @param string $orderByField
     * @param string $orderByDirection
     * @return boolean|array
     */
    public function getTodoQueue(
        $ctsCaseStatusArray,
        $ctsTaskStatusArray,
        $ctsCaseTypeArray,
        $paginator,
        $orderByField,
        $orderByDirection
    ) {
        $query = $this->buildToDoQueueQuery(
            $ctsCaseStatusArray,
            $ctsTaskStatusArray,
            $ctsCaseTypeArray,
            $orderByField,
            $orderByDirection
        );

        $sessionVarName =  'todoQueueQuery_' . $this->ctsHelper->getLoggedInUserName();
        $this->session->set($sessionVarName, $query);

        $response = $this->apiClient->get('s/cmis/query', [
            'query' => [
                'alf_ticket' => $this->tokenStorage->getAuthenticationTicket(),
                'q' => $query,
                'includeAllowableActions' => 'true',
                'maxItems' => $paginator->getPageSize(),
                'skipCount' => $paginator->calculateSkipCount()
            ],
        ]);

        if ($response->getStatusCode() != '200') {
            return false;
        }

        $responseBody = $response->getBody()->__toString();

        $cases = $this->atomHelper->multiEntryFeedToArray(
            $responseBody,
            'cmisra:object',
            $this->caseProperties,
            'required_for_case_queue',
            $this->casePermissons,
            $paginator
        );

        $ctsTodoQueue = array();
        foreach ($cases as $ctsCase) {
            $caseClass = $this->ctsHelper->getCaseClassFromType($ctsCase['correspondenceType']);
            array_push($ctsTodoQueue, $this->factory->build($ctsCase, $caseClass));
        }
        return $ctsTodoQueue;
    }

    /**
     *
     * @param array $ctsCaseStatusArray
     * @param array $ctsTaskStatusArray
     * @param array $ctsCaseTypeArray
     * @param string $fileName
     * @return boolean|array
     */
    public function exportTodoQueue($ctsCaseStatusArray, $ctsTaskStatusArray, $ctsCaseTypeArray, $fileName)
    {
        $query = $this->buildToDoQueueQuery(
            $ctsCaseStatusArray,
            $ctsTaskStatusArray,
            $ctsCaseTypeArray,
            null,
            null
        );
        return $this->exportQueueFromQuery($query, $fileName);
    }

    /**
     *
     * @param string $query
     * @param string $fileName
     * @return boolean|string
     */
    public function exportQueueFromQuery($query, $fileName)
    {
        try {
            $response = $this->apiClient->get('service/homeoffice/cts/exportSearchResults', [
                'query' => [
                    'alf_ticket' => $this->tokenStorage->getAuthenticationTicket(),
                    'q' => $query
                ],
                'save_to' => "/tmp/$fileName"
            ]);
        } catch (RequestException $exception) {
            return false;
        }

        if ($response->getStatusCode() != '200') {
            return false;
        }

        return $fileName;
    }

    /**
     *
     * @param array $ctsCaseStatusArray
     * @param array $ctsTaskStatusArray
     * @param array $ctsCaseTypeArray
     * @param string $orderByField
     * @param string $orderByDirection
     * @return string
     */
    private function buildToDoQueueQuery(
        $ctsCaseStatusArray,
        $ctsTaskStatusArray,
        $ctsCaseTypeArray,
        $orderByField,
        $orderByDirection
    ) {
        $userName = $this->ctsHelper->getLoggedInUserName();
        $select = $this->queryHelper->getQueueSelectStatement();
        $from = QueryHelper::getQueueFromStatement();

        $whereArray = array();
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:assignedUser', '=', $userName);
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:caseStatus', ' <> ', CaseStatus::COMPLETED);
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:caseStatus', ' <> ', CaseStatus::DELETED);
        $this->queryHelper->addToWhereStatement($whereArray, 'gs.cts:isGroupedSlave', ' = ', 'false');
        $this->queryHelper->addWhereInToWhereStatement($whereArray, 'c.cts:caseStatus', $ctsCaseStatusArray);
        $this->queryHelper->addWhereInToWhereStatement($whereArray, 'c.cts:caseTask', $ctsTaskStatusArray);
        $this->queryHelper->addWhereInToWhereStatement($whereArray, 'c.cts:correspondenceType', $ctsCaseTypeArray);

        if ($orderByField != null && $orderByDirection != null) {
            $orderBy = $this->queryHelper->getQueueOrderByStatement($orderByField, $orderByDirection);
        } else {
            $orderBy = array();
        }

        return $this->queryHelper->constructQueryWithMultipleFilters(
            $select,
            $from,
            $whereArray,
            array(),
            array(),
            $orderBy
        );
    }

    /**
     *
     * @param Person $user
     * @param array $ctsCaseStatusArray
     * @param array $ctsTaskStatusArray
     * @param array $ctsTeamArray
     * @param array $ctsAssignedUserArray
     * @param array $ctsCaseTypeArray
     * @param Paginator $paginator
     * @param string $orderByField
     * @param string $orderByDirection
     * @return boolean|array
     */
    public function getTeamQueue(
        $user,
        $ctsCaseStatusArray,
        $ctsTaskStatusArray,
        $ctsTeamArray,
        $ctsAssignedUserArray,
        $ctsCaseTypeArray,
        $paginator,
        $orderByField,
        $orderByDirection
    ) {

        $query = $this->buildTeamQueueQuery(
            $user,
            $ctsCaseStatusArray,
            $ctsTaskStatusArray,
            $ctsTeamArray,
            $ctsAssignedUserArray,
            $ctsCaseTypeArray,
            $orderByField,
            $orderByDirection
        );

        $sessionVarName =  'teamQueueQuery_' . $this->ctsHelper->getLoggedInUserName();
        $this->session->set($sessionVarName, $query);

        $response = $this->apiClient->get('s/cmis/query', [
            'query' => [
                'alf_ticket' => $this->tokenStorage->getAuthenticationTicket(),
                'q' => $query,
                'includeAllowableActions' => 'true',
                'maxItems' => $paginator->getPageSize(),
                'skipCount' => $paginator->calculateSkipCount()
            ],
        ]);

        if ($response->getStatusCode() != '200') {
            return false;
        }

        $responseBody = $response->getBody()->__toString();
        $cases = $this->atomHelper->multiEntryFeedToArray(
            $responseBody,
            'cmisra:object',
            $this->caseProperties,
            'required_for_case_queue',
            $this->casePermissions,
            $paginator
        );

        $ctsTodoQueue = array();
        foreach ($cases as $ctsCase) {
            $caseClass = $this->ctsHelper->getCaseClassFromType($ctsCase['correspondenceType']);
            $case = $this->factory->build($ctsCase, $caseClass);
            $this->ctsHelper->setCaseOwner($case);
            array_push($ctsTodoQueue, $case);
        }

        return $ctsTodoQueue;
    }

    /**
     *
     * @param string $user
     * @param array $ctsCaseStatusArray
     * @param array $ctsTaskStatusArray
     * @param array $ctsTeamArray
     * @param array $ctsAssignedUserArray
     * @param array $ctsCaseTypeArray
     * @param string $orderByField
     * @param string $orderByDirection
     * @return string
     */
    private function buildTeamQueueQuery(
        $user,
        $ctsCaseStatusArray,
        $ctsTaskStatusArray,
        $ctsTeamArray,
        $ctsAssignedUserArray,
        $ctsCaseTypeArray,
        $orderByField,
        $orderByDirection
    ) {

        $select = $this->queryHelper->getQueueSelectStatement();
        $from = QueryHelper::getQueueFromStatement();
        $whereArray = array();

        $userUnits = $this->queryHelper->getUnitsTeamsForQuery($user->getUnits());
        $userTeams = $this->queryHelper->getUnitsTeamsForQuery($user->getTeams());

        if ($userUnits != '' && $userTeams != '') {
            array_push($whereArray, "( c.cts:assignedUnit IN $userUnits OR c.cts:assignedTeam IN $userTeams )");
        } elseif ($userUnits != '') {
            array_push($whereArray, "c.cts:assignedUnit IN $userUnits");
        } elseif ($userTeams != '') {
            array_push($whereArray, "c.cts:assignedTeam IN $userTeams");
        } else {
            array_push($whereArray, "c.cts:assignedUnit = 'NON_EXISTANT_TEAM'");
        }

        $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:caseStatus', ' <> ', CaseStatus::COMPLETED);
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:caseStatus', ' <> ', CaseStatus::DELETED);
        $this->queryHelper->addToWhereStatement($whereArray, 'gs.cts:isGroupedSlave', ' = ', 'false');
        $this->queryHelper->addWhereInToWhereStatement($whereArray, 'c.cts:caseStatus', $ctsCaseStatusArray);
        $this->queryHelper->addWhereInToWhereStatement($whereArray, 'c.cts:caseTask', $ctsTaskStatusArray);
        $this->queryHelper->addWhereInToWhereStatement($whereArray, 'c.cts:correspondenceType', $ctsCaseTypeArray);
        $this->queryHelper->addWhereInToWhereStatement($whereArray, 'c.cts:assignedTeam', $ctsTeamArray);
        $this->queryHelper->addWhereInToWhereStatement($whereArray, 'c.cts:assignedUser', $ctsAssignedUserArray);

        if ($orderByField != null && $orderByDirection != null) {
            $orderBy = $this->queryHelper->getQueueOrderByStatement($orderByField, $orderByDirection);
        } else {
            $orderBy = array();
        }

        return $this->queryHelper->constructQueryWithMultipleFilters(
            $select,
            $from,
            $whereArray,
            array(),
            array(),
            $orderBy
        );
    }

    /**
     *
     * @param string $user
     * @param array $ctsCaseStatusArray
     * @param array $ctsTaskStatusArray
     * @param array $ctsTeamArray
     * @param array $ctsAssignedUserArray
     * @param array $ctsCaseTypeArray
     * @param string $fileName
     * @return boolean|string
     */
    public function exportTeamQueue(
        $user,
        $ctsCaseStatusArray,
        $ctsTaskStatusArray,
        $ctsTeamArray,
        $ctsAssignedUserArray,
        $ctsCaseTypeArray,
        $fileName
    ) {
        $query = $this->buildTeamQueueQuery(
            $user,
            $ctsCaseStatus,
            $ctsTaskStatus,
            $ctsTeam,
            $ctsAssignedUser,
            $ctsCaseType,
            null,
            null
        );

        return $this->exportQueueFromQuery($query, $fileName);
    }

    /**
     *
     * @param Person $user
     * @param array $ctsCaseStatusArray
     * @param array $ctsTaskStatusArray
     * @param array $ctsTeamArray
     * @param array $ctsAssignedUserArray
     * @param array $ctsCaseTypeArray
     * @param Paginator $paginator
     * @param string $orderByField
     * @param string $orderByDirection
     * @return boolean|array
     */
    public function getUnitQueue(
        $user,
        $ctsCaseStatusArray,
        $ctsTaskStatusArray,
        $ctsTeamArray,
        $ctsAssignedUserArray,
        $ctsCaseTypeArray,
        $paginator,
        $orderByField,
        $orderByDirection
    ) {
        $query = $this->buildUnitQueueQuery(
            $user,
            $ctsCaseStatusArray,
            $ctsTaskStatusArray,
            $ctsTeamArray,
            $ctsAssignedUserArray,
            $ctsCaseTypeArray,
            $orderByField,
            $orderByDirection
        );

        $sessionVarName =  'unitQueueQuery_' . $this->ctsHelper->getLoggedInUserName();
        $this->session->set($sessionVarName, $query);

        $response = $this->apiClient->get('s/cmis/query', [
            'query' => [
                'alf_ticket' => $this->tokenStorage->getAuthenticationTicket(),
                'q' => $query,
                'includeAllowableActions' => 'true',
                'maxItems' => $paginator->getPageSize(),
                'skipCount' => $paginator->calculateSkipCount()
            ],
        ]);

        if ($response->getStatusCode() != '200') {
            return false;
        }

        $responseBody = $response->getBody()->__toString();
        $cases = $this->atomHelper->multiEntryFeedToArray(
            $responseBody,
            'cmisra:object',
            $this->caseProperties,
            'required_for_case_queue',
            $this->casePermissions,
            $paginator
        );

        $ctsTodoQueue = array();
        foreach ($cases as $ctsCase) {
            $caseClass = $this->ctsHelper->getCaseClassFromType($ctsCase['correspondenceType']);
            $case = $this->factory->build($ctsCase, $caseClass);
            $this->ctsHelper->setCaseOwner($case);
            array_push($ctsTodoQueue, $case);
        }
        return $ctsTodoQueue;
    }

    /**
     *
     * @param string $user
     * @param array $ctsCaseStatusArray
     * @param array $ctsTaskStatusArray
     * @param array $ctsTeamArray
     * @param array $ctsAssignedUserArray
     * @param array $ctsCaseTypeArray
     * @param string $orderByField
     * @param string $orderByDirection
     * @return string
     */
    private function buildUnitQueueQuery(
        $user,
        $ctsCaseStatusArray,
        $ctsTaskStatusArray,
        $ctsTeamArray,
        $ctsAssignedUserArray,
        $ctsCaseTypeArray,
        $orderByField,
        $orderByDirection
    ) {
        $select = $this->queryHelper->getQueueSelectStatement();
        $from = QueryHelper::getQueueFromStatement();
        $whereArray = array();

        $caseTypes = array();
        $stages = array();
        $unitQueueCaseTypes = $this->queryHelper->getCaseTypesForQuery($user->getUnits());

        foreach ($unitQueueCaseTypes as $group) {
            $typesAndStages = $this->ctsHelper->getGroupCaseTypes($group);
            if (array_key_exists("TYPES", $typesAndStages)) {
                $caseTypes = array_merge($caseTypes, $typesAndStages["TYPES"]);
            }
            if (array_key_exists("STAGES", $typesAndStages)) {
                $stages = array_merge($stages, $typesAndStages["STAGES"]);
            }
        }

        $this->queryHelper->addWhereInToWhereStatement($whereArray, 'c.cts:correspondenceType', $caseTypes);
        $this->queryHelper->addWhereInToWhereStatement($whereArray, 'c.cts:hmpoStage', $stages, true);
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:caseStatus', ' <> ', CaseStatus::COMPLETED);
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:caseStatus', ' <> ', CaseStatus::DELETED);
        $this->queryHelper->addToWhereStatement($whereArray, 'gs.cts:isGroupedSlave', ' = ', 'false');
        $this->queryHelper->addWhereInToWhereStatement($whereArray, 'c.cts:caseStatus', $ctsCaseStatusArray);
        $this->queryHelper->addWhereInToWhereStatement($whereArray, 'c.cts:caseTask', $ctsTaskStatusArray);
        $this->queryHelper->addWhereInToWhereStatement($whereArray, 'c.cts:correspondenceType', $ctsCaseTypeArray);
        $this->queryHelper->addWhereInToWhereStatement($whereArray, 'c.cts:assignedTeam', $ctsTeamArray);
        $this->queryHelper->addWhereInToWhereStatement($whereArray, 'c.cts:assignedUser', $ctsAssignedUserArray);

        if ($orderByField != null && $orderByDirection != null) {
            $orderBy = $this->queryHelper->getQueueOrderByStatement($orderByField, $orderByDirection);
        } else {
            $orderBy = array();
        }

        return $this->queryHelper->constructQueryWithMultipleFilters(
            $select,
            $from,
            $whereArray,
            array(),
            array(),
            $orderBy
        );
    }

    /**
     *
     * @param string $user
     * @param array $ctsCaseStatusArray
     * @param array $ctsTaskStatusArray
     * @param array $ctsTeamArray
     * @param array $ctsAssignedUserArray
     * @param array $ctsCaseTypeArray
     * @param string $fileName
     * @return boolean|string
     */
    public function exportUnitQueue(
        $user,
        $ctsCaseStatusArray,
        $ctsTaskStatusArray,
        $ctsTeamArray,
        $ctsAssignedUserArray,
        $ctsCaseTypeArray,
        $fileName
    ) {
        $query = $this->buildUnitQueueQuery(
            $user,
            $ctsCaseStatusArray,
            $ctsTaskStatusArray,
            $ctsTeamArray,
            $ctsAssignedUserArray,
            $ctsCaseTypeArray,
            null,
            null
        );

        return $this->exportQueueFromQuery($query, $fileName);
    }

    /**
     * @param string $nodeRef
     *
     * @return CtsCase|bool
     */
    public function getCase($nodeRef, $audit = "")
    {
        if($audit !== "") {
            $this->auditView($audit, $nodeRef);
        }

        $response = $this->apiClient->get("s/homeoffice/ctsv2/case?nodeRef=$nodeRef", [
            'query' => [
                'alf_ticket' => $this->tokenStorage->getAuthenticationTicket()
            ],
        ]);

        if ($response->getStatusCode() != '200') {
            return false;
        }

        $case = json_decode($response->getBody()->__toString());

        return $this->factory->build($case,
            $this->ctsHelper->getCaseClassFromType($case->ctsCase->correspondenceType)
        );
    }

    public function auditView($audit, $caseNodeRef)
    {
        $body = json_encode(
            array(
                'content'                => array (
                    'content' => $audit,
                    'minuteQaReviewOutcomes' => "",
                    'task'                   => ""
                )
            )
        );

        $response = $this
            ->apiClient
            ->post(
                "service/homeoffice/cts/api/node/$this->workspace/$this->store/$caseNodeRef/comments",
                array(
                    'query' => array(
                        'alf_ticket' => $this->tokenStorage->getAuthenticationTicket()
                    ),
                    'headers' => array(
                        'Content-Type' => 'application/json'
                    ),
                    'body' => $body,
                )
            );

        if ($response->getStatusCode() != "200") {
            $this->logger->addDebug('Non 200 Code from ' . __METHOD__ . ' returned ' . $response->getStatusCode());
            return false;
        }

        return true;
    }

    /**
     * @param CtsCase $ctsCase
     *
     * @return bool|string
     */
    public function addGroupedCases(CtsCase $ctsCase)
    {
        try {
            $this->apiClient->post("s/homeoffice/cts/groupCases", [
                'query' => [
                    'alf_ticket' => $this->tokenStorage->getAuthenticationTicket()
                ],
                'body' => [
                    'masterNodeRef' => $ctsCase->getId(),
                    'slaveUinList' => $ctsCase->getUinsToGroup()
                ],
            ]);
        } catch (RequestException $exception) {
            $response = json_decode($exception->getResponse()->getBody()->__toString());

            return $response->message;
        }

        return true;
    }

    /**
     * @param CtsCase $masterCtsCase
     * @param array   $slaveCtsCases
     *
     * @return bool|string
     */
    public function removeGroupedCases(CtsCase $masterCtsCase, array $slaveCtsCases)
    {
        try {
            $slaveNodeRefs = [];
            foreach ($slaveCtsCases as $slaveCtsCase) {
                array_push($slaveNodeRefs, $slaveCtsCase->getId());
            }

            $this->apiClient->post("s/homeoffice/cts/ungroupCases", [
                'query' => ['alf_ticket' => $this->tokenStorage->getAuthenticationTicket()],
                'body' => [
                    'masterNodeRef' => $masterCtsCase->getId(),
                    'slaveNodeRefList' => implode($slaveNodeRefs, ',')
                ],
            ]);
        } catch (RequestException $exception) {
            $response = json_decode($exception->getResponse()->getBody()->__toString());

            return $response->message;
        }

        return true;
    }

    /**
     * @param  CtsCase $ctsCase
     *
     * @return bool|string
     */
    public function addLinkedCases(CtsCase $ctsCase)
    {
        try {
            $this->apiClient->post("s/homeoffice/cts/linkCases", [
                'query' => [
                    'alf_ticket' => $this->tokenStorage->getAuthenticationTicket()
                ],
                'body' => [
                    'masterNodeRef' => $ctsCase->getId(),
                    'linkedHrnList' => $ctsCase->getHrnsToLink()
                ],
            ]);
        } catch (RequestException $exception) {
            $response = json_decode($exception->getResponse()->getBody()->__toString());

            return $response->message;
        }

        return true;
    }

    /**
     * @param CtsCase $ctsCase
     * @param CtsCase $childCase
     *
     * @return bool|string
     */
    public function removeLinkedCase(CtsCase $ctsCase, CtsCase $childCase)
    {
        try {
            $this->apiClient->post("s/homeoffice/cts/unlinkCases", [
                'query' => [
                    'alf_ticket' => $this->tokenStorage->getAuthenticationTicket(),
                ],
                'body' => [
                    'masterNodeRef' => $ctsCase->getId(),
                    'linkedHrnList' => $childCase->getId()
                ],
            ]);
        } catch (RequestException $ex) {
            $response = json_encode($ex->getResponse()->getBody()->__toString());

            return $response;
        }

        return true;
    }

    /**
     * Assign Case to Person
     *
     * @param CtsCase $case
     * @param Person  $person
     *
     * @return CtsCase
     */
    public function assignCaseToPerson(CtsCase $case, Person $person)
    {
        $team = $person->getFirstTeam();
        $unit = $person->getFirstUnit() ?: $this->listHandler->getUnitFromTeam($team);

        if (is_null($unit) && is_null($team)) {
            // Don't assign a person if they don't have a unit or team
            return $case;
        }

        if (!is_null($unit)) {
            $case->setAssignedUnit($unit->getAuthorityName());
        }

        if (!is_null($team)) {
            $case->setAssignedTeam($team->getAuthorityName());
        }

        $case->setAssignedUser($person->getUserName());

        return $case;
    }
}

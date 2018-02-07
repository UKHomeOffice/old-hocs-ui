<?php

namespace HomeOffice\AlfrescoApiBundle\Repository;

use GuzzleHttp\Client as Guzzle;
use HomeOffice\CtsBundle\Utils\CtsBundleLogger;
use HomeOffice\ProcessManagerAuthenticatorBundle\Security\SessionTicketStorage;
use HomeOffice\AlfrescoApiBundle\Service\QueryHelper;
use HomeOffice\AlfrescoApiBundle\Service\AtomHelper;
use HomeOffice\AlfrescoApiBundle\Service\CTSHelper;
use HomeOffice\AlfrescoApiBundle\Service\DateHelper;
use HomeOffice\AlfrescoApiBundle\Factory\CtsCaseFactory;
use HomeOffice\AlfrescoApiBundle\Service\CaseStatus;
use Symfony\Component\HttpFoundation\Session\Session;

class CtsCaseSearchRepository
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
     * @var QueryHelper
     */
    private $queryHelper;
 
    /**
     * @var CtsHelper
     */
    private $ctsHelper;
 
    /**
     * @var array
     */
    private $caseProperties;
 
    /**
     * @var array
     */
    private $casePermissions;
 
    /**
     *
     * @var Session
     */
    private $session;
 
    /**
     *
     * @param \GuzzleHttp\Client $apiClient
     * @param \HomeOffice\AlfrescoApiBundle\Factory\CtsCaseFactory $ctsCaseFactory
     * @param \HomeOffice\ProcessManagerAuthenticatorBundle\Security\SessionTicketStorage $tokenStorage
     * @param \HomeOffice\AlfrescoApiBundle\Service\AtomHelper $atomHelper
     * @param \HomeOffice\AlfrescoApiBundle\Service\QueryHelper $queryHelper
     * @param \HomeOffice\AlfrescoApiBundle\Service\CTSHelper $ctsHelper
     * @param array $caseProperties
     * @param array $casePermissions
     * @param Session $session
     */
    public function __construct(
        Guzzle $apiClient,
        CtsCaseFactory $ctsCaseFactory,
        SessionTicketStorage $tokenStorage,
        AtomHelper $atomHelper,
        QueryHelper $queryHelper,
        CTSHelper $ctsHelper,
        $caseProperties,
        $casePermissions,
        Session $session
    ) {
        $this->apiClient = $apiClient;
        $this->factory = $ctsCaseFactory;
        $this->tokenStorage = $tokenStorage;
        $this->atomHelper = $atomHelper;
        $this->queryHelper = $queryHelper;
        $this->ctsHelper = $ctsHelper;
        $this->caseProperties = $caseProperties;
        $this->casePermissions = $casePermissions;
        $this->session = $session;
    }
 
    /**
     *
     * @param string $urnSearch
     * @param date $dateCreatedFromSearch
     * @param date $dateCreatedToSearch
     * @param date $dateDeadlineFromSearch
     * @param date $dateDeadlineToSearch
     * @param array $caseTypeArray
     * @param array $decisionArray
     * @param array $unitArray
     * @param string $topicSearch
     * @param array $ministerArray
     * @param array $statusArray
     * @param array $taskArray
     * @param string $ownerSearch
     * @param string $advancedSearchType
     * @param array $advancedSearchFields
     * @param string $paginator
     * @param string $orderByField
     * @param string $orderByDirection
     * @return boolean|array
     */
    public function getGlobalSearchResults(
        $urnSearch,
        $dateCreatedFromSearch,
        $dateCreatedToSearch,
        $dateDeadlineFromSearch,
        $dateDeadlineToSearch,
        $caseTypeArray,
        $decisionArray,
        $unitArray,
        $topicSearch,
        $ministerArray,
        $statusArray,
        $taskArray,
        $ownerSearch,
        $advancedSearchType,
        $advancedSearchFields,
        $paginator,
        $orderByField,
        $orderByDirection
    ) {
     
        $query = $this->buildGlobalSearchQuery(
            $urnSearch,
            $dateCreatedFromSearch,
            $dateCreatedToSearch,
            $dateDeadlineFromSearch,
            $dateDeadlineToSearch,
            $caseTypeArray,
            $decisionArray,
            $unitArray,
            $topicSearch,
            $ministerArray,
            $statusArray,
            $taskArray,
            $ownerSearch,
            $advancedSearchType,
            $advancedSearchFields,
            $orderByField,
            $orderByDirection
        );

        CtsBundleLogger::getLogger()->debug("CMIS Query = ".$query);
     
        $sessionVarName =  'searchQuery_' . $this->ctsHelper->getLoggedInUserName();
        $this->session->set($sessionVarName, $query);
     
        $response = $this->apiClient->get('s/cmis/query', [
            'query' => [
                'alf_ticket' => $this->tokenStorage->getAuthenticationTicket(),
                'q' => $query,
                'maxItems' => $paginator->getPageSize(),
                'skipCount' => $paginator->calculateSkipCount()
            ],
            'cookies' => ['alf_ticket' => $this->tokenStorage->getAuthenticationTicket()->getTicket()]
        ]);

        if ($response->getStatusCode() != "200") {
            return false;
        }
        $responseBody = $response->getBody()->__toString();

        $cases = $this
            ->atomHelper
            ->multiEntryFeedToArray(
                $responseBody,
                'cmisra:object',
                $this->caseProperties,
                'required_for_search_results',
                $this->casePermissions,
                $paginator
            );

        $ctsSearchQueue = array();

        foreach ($cases as $ctsCase) {
            if ($ctsCase['caseStatus'] === 'Deleted') {
                continue;
            }
            $caseClass = $this->ctsHelper->getCaseClassFromType($ctsCase['correspondenceType']);
            array_push($ctsSearchQueue, $this->factory->build($ctsCase, $caseClass));
        }
        return $ctsSearchQueue;
    }

    /**
     *
     * @param string $urnSearch
     * @param date $dateCreatedFromSearch
     * @param date $dateCreatedToSearch
     * @param date $dateDeadlineFromSearch
     * @param date $dateDeadlineToSearch
     * @param array $caseTypeArray
     * @param array $decisionArray
     * @param array $unitArray
     * @param string $topicSearch
     * @param array $ministerArray
     * @param array $statusArray
     * @param array $taskArray
     * @param string $ownerSearch
     * @param string $advancedSearchType
     * @param array $advancedSearchFields
     * @param string $orderByField
     * @param string $orderByDirection
     * @return boolean|array
     */
    public function exportGlobalSearchResults(
        $urnSearch,
        $dateCreatedFromSearch,
        $dateCreatedToSearch,
        $dateDeadlineFromSearch,
        $dateDeadlineToSearch,
        $caseTypeArray,
        $decisionArray,
        $unitArray,
        $topicSearch,
        $ministerArray,
        $statusArray,
        $taskArray,
        $ownerSearch,
        $advancedSearchType,
        $advancedSearchFields,
        $orderByField,
        $orderByDirection,
        $fileName
    ) {
        $query = $this->buildGlobalSearchQuery(
            $urnSearch,
            $dateCreatedFromSearch,
            $dateCreatedToSearch,
            $dateDeadlineFromSearch,
            $dateDeadlineToSearch,
            $caseTypeArray,
            $decisionArray,
            $unitArray,
            $topicSearch,
            $ministerArray,
            $statusArray,
            $taskArray,
            $ownerSearch,
            $advancedSearchType,
            $advancedSearchFields,
            $orderByField,
            $orderByDirection
        );
     
        return $this->exportGlobalSearchResultsFromQuery($query, $fileName);
    }

    /**
     * @param $searchParams
     */
    public function pqSearchResults($searchParams)
    {
        try {
            $response = $this->apiClient->post('service/homeoffice/cts/pqSearch', [
                'query' => ['alf_ticket' => $this->tokenStorage->getAuthenticationTicket()],
                'headers' => ['Content-Type' => 'application/json'],
                'body' => $searchParams,
            ]);
        } catch (RequestException $exception) {
            $response = $exception->getResponse();
            return $response;
        }

        $responseBody = $response->getBody()->__toString();

        return $responseBody;
    }
 
    /**
     *
     * @param string $query
     */
    public function exportGlobalSearchResultsFromQuery($query, $fileName)
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
            $response = $exception->getResponse();
            return false;
        }
     
        if ($response->getStatusCode() != '200') {
            return false;
        }
     
        return $fileName;
    }
 
    /**
     *
     * @param type $urnSearch
     * @param type $dateCreatedFromSearch
     * @param type $dateCreatedToSearch
     * @param type $dateDeadlineFromSearch
     * @param type $dateDeadlineToSearch
     * @param type $caseTypeArray
     * @param type $decisionArray
     * @param type $unitArray
     * @param type $topicSearch
     * @param type $ministerArray
     * @param type $statusArray
     * @param type $taskArray
     * @param type $ownerSearch
     * @param type $advancedSearchType
     * @param type $advancedSearchFields
     * @param type $orderByField
     * @param type $orderByDirection
     * @return type
     */
    private function buildGlobalSearchQuery(
        $urnSearch,
        $dateCreatedFromSearch,
        $dateCreatedToSearch,
        $dateDeadlineFromSearch,
        $dateDeadlineToSearch,
        $caseTypeArray,
        $decisionArray,
        $unitArray,
        $topicSearch,
        $ministerArray,
        $statusArray,
        $taskArray,
        $ownerSearch,
        $advancedSearchType,
        $advancedSearchFields,
        $orderByField,
        $orderByDirection
    ) {
        $select = $this->queryHelper->getSearchSelectStatement();
        $from = QueryHelper::getSearchFromStatement();
        $whereArray = array();

        $urnForQuery = $this->queryHelper->extractUrnForQuery($urnSearch);
        //@codingStandardsIgnoreStart
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:correspondenceType', ' LIKE ', $urnForQuery["urnPrefix"]);
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:urnSuffix', ' LIKE ', $urnForQuery["urnSuffix"], true);
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cmis:creationDate', ' >= ',
            DateHelper::fromNativeOrNullToIso($dateCreatedFromSearch));
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cmis:creationDate', ' < ',
            DateHelper::fromNativeOrNullToIso($dateCreatedToSearch, true));
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:caseResponseDeadline', ' >= ',
            DateHelper::fromNativeOrNullToIso($dateDeadlineFromSearch));
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:caseResponseDeadline', ' < ',
            DateHelper::fromNativeOrNullToIso($dateDeadlineToSearch, true));
        if ($ownerSearch != '') {
            $ownerSearch = addslashes($ownerSearch);
            array_push($whereArray, "(c.cts:assignedUnit LIKE '%$ownerSearch%' OR c.cts:assignedTeam LIKE '%$ownerSearch%' OR c.cts:assignedUser LIKE '%$ownerSearch%')");
        }
        //@codingStandardsIgnoreEnd
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:markupTopic', ' LIKE ', $topicSearch, true, true);
        $this->queryHelper->addWhereInToWhereStatement($whereArray, 'c.cts:correspondenceType', $caseTypeArray);
        $this->queryHelper->addWhereInToWhereStatement($whereArray, 'c.cts:markupDecision', $decisionArray);
        $this->queryHelper->addWhereInToWhereStatement($whereArray, 'c.cts:markupUnit', $unitArray);
        $this->queryHelper->addWhereInToWhereStatement($whereArray, 'c.cts:markupMinister', $ministerArray);
        $this->queryHelper->addWhereInToWhereStatement($whereArray, 'c.cts:caseTask', $taskArray);
        if (count($statusArray) > 0) {
            $this->queryHelper->addWhereInToWhereStatement($whereArray, 'c.cts:caseStatus', $statusArray);
        } else {
            $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:caseStatus', ' <> ', CaseStatus::DELETED);
        }
     
        if ($advancedSearchType != null) {
            $this->handleAdvancedSearchFilters($advancedSearchType, $advancedSearchFields, $whereArray);
        }
     
        $orderBy = $this->queryHelper->getQueueOrderByStatement($orderByField, $orderByDirection);
     
        $query = $this->queryHelper->constructQueryWithMultipleFilters(
            $select,
            $from,
            $whereArray,
            array(),
            array(),
            $orderBy
        );
     
        return $query;
    }
 
    /**
     *
     * @param string $advancedSearchType
     * @param array $whereArray
     */
    private function handleAdvancedSearchFilters($advancedSearchType, $advancedSearchFields, & $whereArray)
    {
        switch ($advancedSearchType) {
            case 'TRO':
                $this->handleTroSearchFilters($advancedSearchFields, $whereArray);
                break;
            case 'MIN':
                $this->handleMinSearchFilters($advancedSearchFields, $whereArray);
                break;
            case 'FOI':
                $this->handleFoiSearchFilters($advancedSearchFields, $whereArray);
                break;
            case 'FTC':
            case 'FTCI':
            case 'FSC':
            case 'FSCI':
            case 'FLT':
            case 'FUT':
                $this->handleFoiComplaintsSearchFilters($advancedSearchFields, $whereArray);
                break;
            case 'LPQ':
            case 'NPQ':
            case 'OPQ':
                $this->handlePqSearchFilters($advancedSearchFields, $whereArray);
                break;
            case 'IMCB':
            case 'IMCM':
                $this->handleUkviSearchFilters($advancedSearchFields, $whereArray);
                break;
            case 'DTEN':
            case 'UTEN':
                $this->handleNo10SearchFilters($advancedSearchFields, $whereArray);
                break;
            case 'GEN':
                $this->handleHmpoGenSearchFilters($advancedSearchFields, $whereArray);
                break;
            case 'COM':
            case 'COL':
                $this->handleHmpoComSearchFilters($advancedSearchFields, $whereArray);
                break;
            default:
                // do nothin as no other filters are required
                break;
        }
    }
 
    /**
     *
     * @param array $advancedSearchFields
     * @param array $whereArray
     */
    private function handleTroSearchFilters($advancedSearchFields, & $whereArray)
    {
        //@codingStandardsIgnoreStart
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:dateReceived', ' >= ',
            DateHelper::fromNativeOrNullToIso($advancedSearchFields['troReceivedDateFrom']));
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:dateReceived', ' < ',
            DateHelper::fromNativeOrNullToIso($advancedSearchFields['troReceivedDateTo']));
        $this->queryHelper->generateWhereForYesNoBoolean($advancedSearchFields['troPriority'], 'c.cts:priority', $whereArray);
        $this->queryHelper->generateWhereForYesNoBoolean($advancedSearchFields['troCopyToNo10'], 'c.cts:replyToNumberTenCopy', $whereArray);
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:correspondentForename', ' LIKE ', $advancedSearchFields['troCorrespondentFirstName'], false, true);
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:correspondentSurname', ' LIKE ', $advancedSearchFields['troCorrespondentSurname'], false, true);
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:correspondentPostcode', ' LIKE ', $advancedSearchFields['troCorrespondentPostcode'], false, true);
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:correspondentEmail', ' LIKE ', $advancedSearchFields['troCorrespondentEmail']);
        //@codingStandardsIgnoreEnd
    }
 
    /**
     *
     * @param array $advancedSearchFields
     * @param array $whereArray
     */
    private function handleMinSearchFilters($advancedSearchFields, & $whereArray)
    {
        //@codingStandardsIgnoreStart
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:dateReceived', ' >= ',
            DateHelper::fromNativeOrNullToIso($advancedSearchFields['minReceivedDateFrom']));
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:dateReceived', ' < ',
            DateHelper::fromNativeOrNullToIso($advancedSearchFields['minReceivedDateTo'], true));
        $this->queryHelper->generateWhereForYesNoBoolean($advancedSearchFields['minPriority'], 'c.cts:priority', $whereArray);
        $this->queryHelper->generateWhereForYesNoBoolean($advancedSearchFields['minAdvice'], 'c.cts:advice', $whereArray);
        $this->queryHelper->generateWhereForYesNoBoolean($advancedSearchFields['minHomeSecReply'], 'c.cts:homeSecretaryReply', $whereArray);
        $this->queryHelper->generateWhereForYesNoBoolean($advancedSearchFields['minCopyToNo10'], 'c.cts:replyToNumberTenCopy', $whereArray);
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:mpRef',' LIKE ', $advancedSearchFields['minMpRef']);
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:correspondentForename', ' LIKE ', $advancedSearchFields['minConstituentFirstName'], false, true);
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:correspondentSurname', ' LIKE ', $advancedSearchFields['minConstituentSurname'], false, true);
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:correspondentPostcode', ' LIKE ', $advancedSearchFields['minConstituentPostcode'], false, true);
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:correspondentEmail',' LIKE ', $advancedSearchFields['minConstituentEmail']);
        $this->queryHelper->addWhereInToWhereStatement($whereArray, 'c.cts:member', $advancedSearchFields['minMember']);
        //@codingStandardsIgnoreEnd
    }
 
    /**
     *
     * @param array $advancedSearchFields
     * @param array $whereArray
     */
    private function handleFoiSearchFilters($advancedSearchFields, & $whereArray)
    {
        //@codingStandardsIgnoreStart
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:dateReceived', ' >= ',
            DateHelper::fromNativeOrNullToIso($advancedSearchFields['foiReceivedDateFrom']));
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:dateReceived', ' < ',
            DateHelper::fromNativeOrNullToIso($advancedSearchFields['foiReceivedDateTo'], true));
        $this->queryHelper->generateWhereForYesNoBoolean($advancedSearchFields['foiIsEir'], 'c.cts:foiIsEir', $whereArray);
        $this->queryHelper->generateWhereForYesNoBoolean($advancedSearchFields['foiMinisterSignOff'], 'c.cts:foiMinisterSignOff', $whereArray);
        $this->queryHelper->generateWhereForYesNoBoolean($advancedSearchFields['foiDisclosure'], 'c.cts:foiDisclosure', $whereArray);
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:correspondentForename', ' LIKE ', $advancedSearchFields['foiRequestorFirstName'], false, true);
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:correspondentSurname', ' LIKE ', $advancedSearchFields['foiRequestorSurname'], false, true);
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:correspondentPostcode', ' LIKE ', $advancedSearchFields['foiRequestorPostcode'], false, true);
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:hoCaseOfficer', ' LIKE ', $advancedSearchFields['foiHoCaseOfficer'], false, true);
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:correspondentEmail',' LIKE ', $advancedSearchFields['foiRequestorEmail']);
        //@codingStandardsIgnoreEnd
    }
 
    /**
     *
     * @param array $advancedSearchFields
     * @param array $whereArray
     */
    private function handleFoiComplaintsSearchFilters($advancedSearchFields, & $whereArray)
    {
        //@codingStandardsIgnoreStart
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:dateReceived', ' >= ',
            DateHelper::fromNativeOrNullToIso($advancedSearchFields['foiComplaintReceivedDateFrom']));
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:dateReceived', ' < ',
            DateHelper::fromNativeOrNullToIso($advancedSearchFields['foiComplaintReceivedDateTo'], true));
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:responseDate', ' >= ',
            DateHelper::fromNativeOrNullToIso($advancedSearchFields['foiComplaintResponseDateFrom']));
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:responseDate', ' < ',
            DateHelper::fromNativeOrNullToIso($advancedSearchFields['foiComplaintResponseDateTo'], true));
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:correspondentForename', ' LIKE ', $advancedSearchFields['foiComplaintRequestorFirstName'], false, true);
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:correspondentSurname', ' LIKE ', $advancedSearchFields['foiComplaintRequestorSurname'], false, true);
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:correspondentPostcode', ' LIKE ', $advancedSearchFields['foiComplaintRequestorPostcode'], false, true);
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:hoCaseOfficer', ' LIKE ', $advancedSearchFields['foiComplaintHoCaseOfficer'], false, true);
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:correspondentEmail',' LIKE ', $advancedSearchFields['foiComplaintRequestorEmail']);
        //@codingStandardsIgnoreEnd
    }

    /**
     *
     * @param array $advancedSearchFields
     * @param array $whereArray
     */
    private function handleUkviSearchFilters($advancedSearchFields, & $whereArray)
    {
        //@codingStandardsIgnoreStart
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:dateReceived', ' >= ',
            DateHelper::fromNativeOrNullToIso($advancedSearchFields['ukviReceivedDateFrom']));
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:dateReceived', ' < ',
            DateHelper::fromNativeOrNullToIso($advancedSearchFields['ukviReceivedDateTo'], true));
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:caseRef',' LIKE ', $advancedSearchFields['ukviCaseRef']);
        $this->queryHelper->addWhereInToWhereStatement($whereArray, 'c.cts:member', $advancedSearchFields['ukviMember']);
        $this->queryHelper->generateWhereForYesNoBoolean($advancedSearchFields['ukviCopyToNo10'], 'c.cts:replyToNumberTenCopy', $whereArray);
        if ($advancedSearchFields['ukviFirstName'] != '') {
            $firstNameSearch = addslashes($advancedSearchFields['ukviFirstName']);
            array_push($whereArray, "(c.cts:correspondentForename LIKE '$firstNameSearch%' OR c.cts:thirdPartyCorrespondentForename LIKE '$firstNameSearch%')");
        }
        if ($advancedSearchFields['ukviSurname'] != '') {
            $surnameSearch = addslashes($advancedSearchFields['ukviSurname']);
            array_push($whereArray, "(c.cts:correspondentSurname LIKE '$surnameSearch%' OR c.cts:thirdPartyCorrespondentSurname LIKE '$surnameSearch%')");
        }
        if ($advancedSearchFields['ukviPostcode'] != '') {
            $postcodeSearch = addslashes($advancedSearchFields['ukviPostcode']);
            array_push($whereArray, "(c.cts:correspondentPostcode LIKE '$postcodeSearch%' OR c.cts:thirdPartyCorrespondentPostcode LIKE '$postcodeSearch%')");
        }
        if ($advancedSearchFields['ukviEmail'] != '') {
            $emailSearch = addslashes($advancedSearchFields['ukviEmail']);
            array_push($whereArray, "(c.cts:correspondentEmail = '$emailSearch' OR c.cts:thirdPartyCorrespondentEmail = '$emailSearch')");
        }
        //@codingStandardsIgnoreEnd
    }

    /**
     *
     * @param array $advancedSearchFields
     * @param array $whereArray
     */
    private function handlePqSearchFilters($advancedSearchFields, & $whereArray)
    {
        //@codingStandardsIgnoreStart

        // For some reason opDate only works with the following style of query TIMESTAMP '2013-05-15T00:00:00.000+00:00'
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:opDate', ' = TIMESTAMP ',
            DateHelper::fromNativeOrNullToIso($advancedSearchFields['pqOpDate'], false, true));
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:receivedType',' LIKE ', $advancedSearchFields['pqReceivedType']);
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:uin',' LIKE ', $advancedSearchFields['pqUin']);
        $this->queryHelper->addWhereInToWhereStatement($whereArray, 'c.cts:member', $advancedSearchFields['pqMember']);
        $this->queryHelper->generateWhereForYesNoBoolean($advancedSearchFields['pqRoundRobin'], 'c.cts:roundRobin', $whereArray);
        //@codingStandardsIgnoreEnd
        if (!in_array('ANY_ALLOWED', $advancedSearchFields['pqSignedBy'])) {
            if (in_array('Home Sec', $advancedSearchFields['pqSignedBy'])) {
                $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:signedByHomeSec',' LIKE ', 'true');
            } else {
                $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:signedByHomeSec',' LIKE ', 'false');
            }
            if (in_array('Lords Minister', $advancedSearchFields['pqSignedBy'])) {
                $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:signedByLordsMinister',' LIKE ', 'true');
            } else {
                $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:signedByLordsMinister',' LIKE ', 'false');
            }
        }
        if (!in_array('ANY_ALLOWED', $advancedSearchFields['pqReviewedBy'])) {
            if (in_array('Perm Sec', $advancedSearchFields['pqReviewedBy'])) {
                $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:reviewedByPermSec',' LIKE ', 'true');
            } else {
                $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:reviewedByPermSec',' LIKE ', 'false');
            }
            if (in_array('SpAds', $advancedSearchFields['pqReviewedBy'])) {
                $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:reviewedBySpads',' LIKE ', 'true');
            } else {
                $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:reviewedBySpads',' LIKE ', 'false');
            }
        }
    }

    /**
     *
     * @param array $advancedSearchFields
     * @param array $whereArray
     */
    private function handleNo10SearchFilters($advancedSearchFields, & $whereArray)
    {
        //@codingStandardsIgnoreStart
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:dateReceived', ' >= ',
            DateHelper::fromNativeOrNullToIso($advancedSearchFields['tenReceivedDateFrom']));
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:dateReceived', ' < ',
            DateHelper::fromNativeOrNullToIso($advancedSearchFields['tenReceivedDateTo'], true));
        $this->queryHelper->generateWhereForYesNoBoolean($advancedSearchFields['tenPriority'], 'c.cts:priority', $whereArray);
        $this->queryHelper->generateWhereForYesNoBoolean($advancedSearchFields['tenAdvice'], 'c.cts:advice', $whereArray);
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:mpRef',' LIKE ', $advancedSearchFields['tenMemberRef']);
        $this->queryHelper->addWhereInToWhereStatement($whereArray, 'c.cts:member', $advancedSearchFields['tenMember']);
        //@codingStandardsIgnoreEnd
    }

    /**
     *
     * @param array $advancedSearchFields
     * @param array $whereArray
     */
    private function handleHmpoGenSearchFilters($advancedSearchFields, & $whereArray)
    {
        //@codingStandardsIgnoreStart
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:dateReceived', ' >= ',
            DateHelper::fromNativeOrNullToIso($advancedSearchFields['genReceivedDateFrom']));
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:dateReceived', ' < ',
            DateHelper::fromNativeOrNullToIso($advancedSearchFields['genReceivedDateTo'], true));
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:hmpoPassportNumber',' LIKE ', $advancedSearchFields['genPassportNumber']);
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:hmpoApplicationNumber',' LIKE ', $advancedSearchFields['genApplicationNumber']);
        if ($advancedSearchFields['genFirstName'] != '') {
            $firstNameSearch = addslashes($advancedSearchFields['genFirstName']);
            array_push($whereArray, "(c.cts:correspondentForename LIKE '$firstNameSearch%' OR c.cts:applicantForename LIKE '$firstNameSearch%')");
        }
        if ($advancedSearchFields['genSurname'] != '') {
            $surnameSearch = addslashes($advancedSearchFields['genSurname']);
            array_push($whereArray, "(c.cts:correspondentSurname LIKE '$surnameSearch%' OR c.cts:applicantSurname LIKE '$surnameSearch%')");
        }
        if ($advancedSearchFields['genPostcode'] != '') {
            $postcodeSearch = addslashes($advancedSearchFields['genPostcode']);
            array_push($whereArray, "(c.cts:correspondentPostcode LIKE '$postcodeSearch%' OR c.cts:applicantPostcode LIKE '$postcodeSearch%')");
        }
        if ($advancedSearchFields['genEmail'] != '') {
            $emailSearch = addslashes($advancedSearchFields['genEmail']);
            array_push($whereArray, "(c.cts:correspondentEmail = '$emailSearch' OR c.cts:applicantEmail = '$emailSearch')");
        }
        //@codingStandardsIgnoreEnd
    }

    /**
     *
     * @param array $advancedSearchFields
     * @param array $whereArray
     */
    private function handleHmpoComSearchFilters($advancedSearchFields, & $whereArray)
    {
        //@codingStandardsIgnoreStart
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:dateReceived', ' >= ',
            DateHelper::fromNativeOrNullToIso($advancedSearchFields['comReceivedDateFrom']));
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:dateReceived', ' < ',
            DateHelper::fromNativeOrNullToIso($advancedSearchFields['comReceivedDateTo'], true));
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:hmpoPassportNumber',' LIKE ', $advancedSearchFields['comPassportNumber']);
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:hmpoApplicationNumber',' LIKE ', $advancedSearchFields['comApplicationNumber']);
        $this->queryHelper->addWhereInToWhereStatement($whereArray, 'c.cts:hmpoStage', $advancedSearchFields['comComplaintStage']);
        if ($advancedSearchFields['comFirstName'] != '') {
            $firstNameSearch = addslashes($advancedSearchFields['comFirstName']);
            array_push($whereArray, "(c.cts:correspondentForename LIKE '$firstNameSearch%' OR c.cts:complainantForename LIKE '$firstNameSearch%' OR c.cts:applicantForename LIKE '$firstNameSearch%')");
        }
        if ($advancedSearchFields['comSurname'] != '') {
            $surnameSearch = addslashes($advancedSearchFields['comSurname']);
            array_push($whereArray, "(c.cts:correspondentSurname LIKE '$surnameSearch%' OR c.cts:complainantSurname LIKE '$surnameSearch%' OR c.cts:applicantSurname LIKE '$surnameSearch%')");
        }
        if ($advancedSearchFields['comPostcode'] != '') {
            $postcodeSearch = addslashes($advancedSearchFields['comPostcode']);
            array_push($whereArray, "(c.cts:correspondentPostcode LIKE '$postcodeSearch%' OR c.cts:complainantPostcode LIKE '$postcodeSearch%' OR c.cts:applicantPostcode LIKE '$postcodeSearch%')");
        }
        if ($advancedSearchFields['comEmail'] != '') {
            $emailSearch = addslashes($advancedSearchFields['comEmail']);
            array_push($whereArray, "(c.cts:correspondentEmail = '$emailSearch' OR c.cts:complainantEmail = '$emailSearch' OR c.cts:applicantEmail = '$emailSearch')");
        }
        //@codingStandardsIgnoreEnd
    }

    /**
     *
     *
     * @param date $dateCreatedFromSearch
     * @param date $dateCreatedToSearch
     * @param string $forenameSearch
     * @param string $surnameSearch
     * @param string $postcodeSearch
     * @param string $emailSearch
     * @param string $paginator
     * @return boolean|array
     */
    public function getCorrespondentSearchResults(
        $dateCreatedFromSearch,
        $dateCreatedToSearch,
        $forenameSearch,
        $surnameSearch,
        $postcodeSearch,
        $emailSearch,
        $paginator
    ) {
        $select = $this->queryHelper->getSearchSelectStatement();
        $from = QueryHelper::getSearchFromStatement();

        $whereArray = array();
        $whereOrArray = array();
        $whereAndArray = array();

        $this->queryHelper->addToWhereStatement($whereArray, 'c.cts:caseStatus', ' <> ', CaseStatus::DELETED);
        //@codingStandardsIgnoreStart
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cmis:creationDate', ' >= ',
            DateHelper::fromNativeOrNullToIso($dateCreatedFromSearch));
        $this->queryHelper->addToWhereStatement($whereArray, 'c.cmis:creationDate', ' < ',
            DateHelper::fromNativeOrNullToIso($dateCreatedToSearch, true));
        //@codingStandardsIgnoreEnd
     
        $corrrespondentOrArray = array();
        $corrrespondentAndArray = array();
     
        //@codingStandardsIgnoreStart
        $this->queryHelper->addToWhereStatement($corrrespondentOrArray, 'c.cts:correspondentPostcode', ' LIKE ', $postcodeSearch, true, true);
        $this->queryHelper->addToWhereStatement($corrrespondentOrArray, 'c.cts:correspondentEmail',' LIKE ', $emailSearch);
        $this->queryHelper->addToWhereStatement($corrrespondentAndArray, 'c.cts:correspondentForename',' LIKE ', $forenameSearch);
        $this->queryHelper->addToWhereStatement($corrrespondentAndArray, 'c.cts:correspondentSurname',' LIKE ', $surnameSearch);
        //@codingStandardsIgnoreEnd
     
        if (count($corrrespondentOrArray) > 0) {
            array_push($whereOrArray, $corrrespondentOrArray);
        }
        if (count($corrrespondentAndArray) > 0) {
            array_push($whereAndArray, $corrrespondentAndArray);
        }
     
        $query = $this->queryHelper->constructQueryWithMultipleFilters(
            $select,
            $from,
            $whereArray,
            $whereOrArray,
            $whereAndArray
        );

        $response = $this->apiClient->get('s/cmis/query', [
            'query' => [
                'alf_ticket' => $this->tokenStorage->getAuthenticationTicket(),
                'q' => $query,
                'maxItems' => $paginator->getPageSize(),
                'skipCount' => $paginator->calculateSkipCount()
            ],
        ]);

        if ($response->getStatusCode() != "200") {
            return false;
        }
        $responseBody = $response->getBody()->__toString();
             
        $cases = $this->atomHelper->multiEntryFeedToArray(
            $responseBody,
            'cmisra:object',
            $this->caseProperties,
            'required_for_search_results',
            $this->casePermissions,
            $paginator
        );
        $ctsSearchQueue = array();
        foreach ($cases as $ctsCase) {
            $caseClass = $this->ctsHelper->getCaseClassFromType($ctsCase['correspondenceType']);
            array_push($ctsSearchQueue, $this->factory->build($ctsCase, $caseClass));
        }
        return $ctsSearchQueue;
    }
}

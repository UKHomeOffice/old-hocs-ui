<?php

namespace HomeOffice\CtsBundle\Controller;

use HomeOffice\AlfrescoApiBundle\Repository\CtsCaseRepository;
use HomeOffice\AlfrescoApiBundle\Repository\CtsCaseSearchRepository;
use HomeOffice\AlfrescoApiBundle\Service\CTSHelper;
use HomeOffice\AlfrescoApiBundle\Service\QueryHelper;
use HomeOffice\ListBundle\Service\ListHandler;
use Symfony\Component\HttpFoundation\Request;
use HomeOffice\CtsBundle\Controller\CtsController as Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use HomeOffice\CtsBundle\Form\Type\CtsCaseSearchByFieldType;
use HomeOffice\CtsBundle\Form\Type\CorrespondentSearchByFieldType;

class SearchController extends Controller
{

    /**
     * @Template
     * @Route("/globalSearch")
     * @Method({"GET", "POST"})
     */
    public function globalSearchAction(Request $request)
    {
        /** @var ListHandler $ctsListHander */
        $ctsListHander           = $this->get('home_office_list.handler');
        /** @var CtsCaseSearchRepository $ctsCaseSearchRepository */
        $ctsCaseSearchRepository = $this->get('home_office_alfresco_api.cts_case_search.repository');
        /** @var CTSHelper $ctsHelper */
        $ctsHelper               = $this->get('home_office_alfresco_api.cts_case.cts_helper');
        /** @var QueryHelper $queryHelper */
        $queryHelper             = $this->get('home_office_alfresco_api.cts_case.query_helper');
        /** @var CtsCaseSearchByFieldType $searchByFieldType */
        $searchByFieldType       = new CtsCaseSearchByFieldType($ctsListHander, false);
        $orderBy                 = 'DeadlineDate';
        $currentOrderDirection   = 'ASC';

        $orderArray              = array();

        if ($request->getMethod() == 'POST') {
            $currentPageNumber = 1;
            $totalResults = 0;

            if (array_key_exists('currentPageNumber', $request->request->get('gs'))) {
                $currentPageNumber = $request->request->get('gs')['currentPageNumber'];
            }

            if (array_key_exists('totalResults', $request->request->get('gs'))) {
                $totalResults = $request->request->get('gs')['totalResults'];
            }

            $paginator = $this
                ->setupPaginatorDirect($currentPageNumber, 'homeoffice_cts_search_globalsearch');

            $paginator
                ->setTotalResults($totalResults);

            $searchByFieldType
                ->setPaginator($paginator);

            if (array_key_exists('currentOrderDirection', $request->request->get('gs'))) {
                $currentOrderDirection = $request->request->get('gs')['currentOrderDirection'];
            }

            $orderArray = $queryHelper
                ->orderByDateColumnHandler(
                    $orderBy,
                    $currentOrderDirection
                );

            $orderByField     = $orderArray[0];
            $orderByDirection = $orderArray[1];
            $searchByFieldType->setOrderByDirection($currentOrderDirection);
        }

        $searchByForm = $this->createForm($searchByFieldType);
        $ctsSearchResults = array();
        $searchPerformed = false;

        $searchByForm->handleRequest($request);

        if ($request->getMethod() == 'POST') {
            if ($searchByForm->get('clear')->isClicked() ||
                $searchByForm->get('clearTro')->isClicked() ||
                $searchByForm->get('clearMin')->isClicked() ||
                $searchByForm->get('clearFoi')->isClicked() ||
                $searchByForm->get('clearPq')->isClicked() ||
                $searchByForm->get('clearUkvi')->isClicked()
            ) {
                return $this->redirect($this->generateUrl('homeoffice_cts_search_globalsearch'));
            }

            if (isset($searchByForm['pageNext']) &&
                $searchByForm->get('pageNext')->isClicked()
            ) {
                $paginator->setPageNumber($paginator->getPageNumber()+1);
            }

            if (isset($searchByForm['pagePrevious']) &&
                $searchByForm->get('pagePrevious')->isClicked()
            ) {
                $paginator->setPageNumber($paginator->getPageNumber()-1);
            }

            foreach ($paginator->getPages() as $page) {
                if (isset($searchByForm["page_$page"]) && $searchByForm->get("page_$page")->isClicked()) {
                    $paginator->setPageNumber($page);
                }
            }

            $searchPerformed = true;
            $searchValues = $searchByForm->getData();

            if (isset($searchByForm['orderByDeadline']) && $searchByForm->get('orderByDeadline')->isClicked()) {
                if ($searchValues['currentOrderDirection'] == 'ASC') {
                    $orderByDirection = 'DESC';
                }

                if ($searchValues['currentOrderDirection'] == 'DESC') {
                    $orderByDirection = 'ASC';
                }
            }

            $correspondenceTypeArray = array();
            $decisionArray = array();
            $unitArray = array();
            $ministerArray = array();
            $statusArray = array();
            $taskArray = array();

            if (isset($searchValues['correspondenceType'])) {
                $correspondenceTypeArray = $searchValues['correspondenceType'];
            }
            if (isset($searchValues['decision'])) {
                $decisionArray = $searchValues['decision'];
            }
            if (isset($searchValues['unit'])) {
                $unitArray = $searchValues['unit'];
            }
            if (isset($searchValues['minister'])) {
                $ministerArray = $searchValues['minister'];
            }
            if (isset($searchValues['status'])) {
                $statusArray = $searchValues['status'];
            }
            if (isset($searchValues['task'])) {
                $taskArray = $searchValues['task'];
            }
            $superTypes = $ctsHelper->getSearchSuperTypes($correspondenceTypeArray);
            if (count($correspondenceTypeArray) == 1 || count($superTypes) == 1) {
                $advancedSearchType = $correspondenceTypeArray[0];
                $advancedSearchFields = $this->handleAdvancedSearchFilters($advancedSearchType, $searchValues);
            } else {
                $advancedSearchType = null;
                $advancedSearchFields = array();
            }
            if ($searchByForm->get('exportButton')->isClicked()) {
                $userName = $ctsHelper->getLoggedInUserName();
                $timestamp = new \DateTime();
                $fileName = 'searchReport' . '_' . $userName . '_' . $timestamp->format('dmYHis');

                $sessionVarName =  "searchQuery_$userName";
                $searchQuery = $this->getSessionParameter($sessionVarName);

                if ($searchQuery != null) {
                    $exportResult = $ctsCaseSearchRepository->exportGlobalSearchResultsFromQuery(
                        $searchQuery,
                        $fileName
                    );
                } else {
                    $exportResult = $ctsCaseSearchRepository->exportGlobalSearchResults(
                        $searchValues['urn'],
                        $searchValues['dateCreatedFrom'],
                        $searchValues['dateCreatedTo'],
                        $searchValues['dateDeadlineFrom'],
                        $searchValues['dateDeadlineTo'],
                        $correspondenceTypeArray,
                        $decisionArray,
                        $unitArray,
                        $searchValues['topic'],
                        $ministerArray,
                        $statusArray,
                        $taskArray,
                        $searchValues['owner'],
                        $advancedSearchType,
                        $advancedSearchFields,
                        $orderByField,
                        $orderByDirection,
                        $fileName
                    );
                }

                if (is_bool($exportResult) && !$exportResult) {
                    $errorMsg = 'Unable to generate report of search results.';
                } else {
                    return $this->generateExportFileResponse($exportResult);
                }
            } else {
                $ctsSearchResults = $ctsCaseSearchRepository->getGlobalSearchResults(
                    $searchValues['urn'],
                    $searchValues['dateCreatedFrom'],
                    $searchValues['dateCreatedTo'],
                    $searchValues['dateDeadlineFrom'],
                    $searchValues['dateDeadlineTo'],
                    $correspondenceTypeArray,
                    $decisionArray,
                    $unitArray,
                    $searchValues['topic'],
                    $ministerArray,
                    $statusArray,
                    $taskArray,
                    $searchValues['owner'],
                    $advancedSearchType,
                    $advancedSearchFields,
                    $paginator,
                    $orderByField,
                    $orderByDirection
                );
            }

            $searchByFieldType->setPaginator($paginator);
            $searchByFieldType->setOrderByDirection($orderByDirection);
            $searchByForm = $this->createForm($searchByFieldType, $searchValues);
        }

        $unitList = $ctsListHander->getList('ctsUnitList');
        $ministerList = $ctsListHander->getList('ctsMinisterList');
        foreach ($ctsSearchResults as $ctsCase) {
            if (array_key_exists($ctsCase->getMarkupUnit(), $unitList)) {
                $ctsCase->setMarkupUnit($unitList[$ctsCase->getMarkupUnit()]);
            }
            if (array_key_exists($ctsCase->getMarkupMinister(), $ministerList)) {
                $ctsCase->setMarkupMinister($ministerList[$ctsCase->getMarkupMinister()]);
            }

            $ctsHelper->setCaseOwner($ctsCase);
        }

        return array(
            'ctsSearchResults' => $ctsSearchResults,
            'ctsHelper' => $ctsHelper,
            'searchByForm' => $searchByForm->createView(),
            'searchPerformed' => $searchPerformed,
            'paginator' => isset($paginator) ? $paginator : null,
            'paginatorMethod' => 'POST',
            'orderByField' => isset($orderByField) ? $orderByField : null,
            'orderByDirection' => isset($orderByDirection) ? $orderByDirection : null,
            'advancedSearchType' => isset($advancedSearchType) ? $advancedSearchType : null
        );
    }

    /**
     *
     * @param string $advancedSearchType
     * @param array $searchValues
     * @return array
     */
    private function handleAdvancedSearchFilters($advancedSearchType, $searchValues)
    {
        switch ($advancedSearchType) {
            case 'TRO':
                return $this->handleTroSearchFilters($searchValues);
            case 'MIN':
                return $this->handleMinSearchFilters($searchValues);
            case 'FOI':
                return $this->handleFoiSearchFilters($searchValues);
            case 'FTC':
            case 'FTCI':
            case 'FSC':
            case 'FSCI':
            case 'FLT':
            case 'FUT':
                return $this->handleFoiComplaintsSearchFilters($searchValues);
            case 'LPQ':
            case 'NPQ':
            case 'OPQ':
                return $this->handlePqSearchFilters($searchValues);
            case 'IMCB':
            case 'IMCM':
                return $this->handleUkviSearchFilters($searchValues);
            case 'DTEN':
            case 'UTEN':
                return $this->handleNo10SearchFilters($searchValues);
            case 'GEN':
                return $this->handleHmpoGenSearchFilters($searchValues);
            case 'COM':
                return $this->handleHmpoComSearchFilters($searchValues);
            default:
                // do nothin as no other filters are required
                return array();
        }
    }

    /**
     *
     * @param array $searchValues
     * @return array
     */
    private function handleTroSearchFilters($searchValues)
    {
        $advancedSearchFields = array();
        $advancedSearchFields['troReceivedDateFrom'] = $searchValues['troReceivedDateFrom'];
        $advancedSearchFields['troReceivedDateTo'] = $searchValues['troReceivedDateTo'];
        $advancedSearchFields['troPriority'] = $searchValues['troPriority'];
        $advancedSearchFields['troCopyToNo10'] = $searchValues['troCopyToNo10'];
        $advancedSearchFields['troCorrespondentFirstName'] = $searchValues['troCorrespondentFirstName'];
        $advancedSearchFields['troCorrespondentSurname'] = $searchValues['troCorrespondentSurname'];
        $advancedSearchFields['troCorrespondentPostcode'] = $searchValues['troCorrespondentPostcode'];
        $advancedSearchFields['troCorrespondentEmail'] = $searchValues['troCorrespondentEmail'];
        return $advancedSearchFields;
    }

    /**
     *
     * @param array $searchValues
     * @return array
     */
    private function handleMinSearchFilters($searchValues)
    {
        $advancedSearchFields = array();
        $advancedSearchFields['minReceivedDateFrom'] = $searchValues['minReceivedDateFrom'];
        $advancedSearchFields['minReceivedDateTo'] = $searchValues['minReceivedDateTo'];
        $advancedSearchFields['minPriority'] = $searchValues['minPriority'];
        $advancedSearchFields['minAdvice'] = $searchValues['minAdvice'];
        $advancedSearchFields['minMember'] = $searchValues['minMember'];
        $advancedSearchFields['minMpRef'] = $searchValues['minMpRef'];
        $advancedSearchFields['minHomeSecReply'] = $searchValues['minHomeSecReply'];
        $advancedSearchFields['minCopyToNo10'] = $searchValues['minCopyToNo10'];
        $advancedSearchFields['minConstituentFirstName'] = $searchValues['minConstituentFirstName'];
        $advancedSearchFields['minConstituentSurname'] = $searchValues['minConstituentSurname'];
        $advancedSearchFields['minConstituentPostcode'] = $searchValues['minConstituentPostcode'];
        $advancedSearchFields['minConstituentEmail'] = $searchValues['minConstituentEmail'];
        return $advancedSearchFields;
    }

    /**
     *
     * @param array $searchValues
     * @return array
     */
    private function handleFoiSearchFilters($searchValues)
    {
        $advancedSearchFields = array();
        $advancedSearchFields['foiReceivedDateFrom'] = $searchValues['foiReceivedDateFrom'];
        $advancedSearchFields['foiReceivedDateTo'] = $searchValues['foiReceivedDateTo'];
        $advancedSearchFields['foiIsEir'] = $searchValues['foiIsEir'];
        $advancedSearchFields['foiMinisterSignOff'] = $searchValues['foiMinisterSignOff'];
        $advancedSearchFields['foiDisclosure'] = $searchValues['foiDisclosure'];
        $advancedSearchFields['foiRequestorFirstName'] = $searchValues['foiRequestorFirstName'];
        $advancedSearchFields['foiRequestorSurname'] = $searchValues['foiRequestorSurname'];
        $advancedSearchFields['foiRequestorPostcode'] = $searchValues['foiRequestorPostcode'];
        $advancedSearchFields['foiRequestorEmail'] = $searchValues['foiRequestorEmail'];
        $advancedSearchFields['foiHoCaseOfficer'] = $searchValues['foiHoCaseOfficer'];
        return $advancedSearchFields;
    }

    /**
     *
     * @param array $searchValues
     * @return array
     */
    private function handleFoiComplaintsSearchFilters($searchValues)
    {
        $advancedSearchFields = array();
        $advancedSearchFields['foiComplaintReceivedDateFrom'] = $searchValues['foiComplaintReceivedDateFrom'];
        $advancedSearchFields['foiComplaintReceivedDateTo'] = $searchValues['foiComplaintReceivedDateTo'];
        $advancedSearchFields['foiComplaintResponseDateFrom'] = $searchValues['foiComplaintResponseDateFrom'];
        $advancedSearchFields['foiComplaintResponseDateTo'] = $searchValues['foiComplaintResponseDateTo'];
        $advancedSearchFields['foiComplaintRequestorFirstName'] = $searchValues['foiComplaintRequestorFirstName'];
        $advancedSearchFields['foiComplaintRequestorSurname'] = $searchValues['foiComplaintRequestorSurname'];
        $advancedSearchFields['foiComplaintRequestorPostcode'] = $searchValues['foiComplaintRequestorPostcode'];
        $advancedSearchFields['foiComplaintRequestorEmail'] = $searchValues['foiComplaintRequestorEmail'];
        $advancedSearchFields['foiComplaintHoCaseOfficer'] = $searchValues['foiComplaintHoCaseOfficer'];
        return $advancedSearchFields;
    }

    /**
     *
     * @param array $searchValues
     * @return array
     */
    private function handlePqSearchFilters($searchValues)
    {
        $advancedSearchFields = array();
        $advancedSearchFields['pqUin'] = $searchValues['pqUin'];
        $advancedSearchFields['pqOpDate'] = $searchValues['pqOpDate'];
        $advancedSearchFields['pqRoundRobin'] = $searchValues['pqRoundRobin'];
        $advancedSearchFields['pqMember'] = $searchValues['pqMember'];
        $advancedSearchFields['pqReceivedType'] = $searchValues['pqReceivedType'];
        //@codingStandardsIgnoreStart
        $advancedSearchFields['pqSignedBy'] = isset($searchValues['pqSignedBy']) ? $searchValues['pqSignedBy'] : array('ANY_ALLOWED');
        $advancedSearchFields['pqReviewedBy'] = isset($searchValues['pqReviewedBy']) ? $searchValues['pqReviewedBy'] : array('ANY_ALLOWED');
        //@codingStandardsIgnoreStart
        return $advancedSearchFields;
    }

    /**
     *
     * @param array $searchValues
     * @return array
     */
    private function handleUkviSearchFilters($searchValues)
    {
        $advancedSearchFields = array();
        $advancedSearchFields['ukviReceivedDateFrom'] = $searchValues['ukviReceivedDateFrom'];
        $advancedSearchFields['ukviReceivedDateTo'] = $searchValues['ukviReceivedDateTo'];
        $advancedSearchFields['ukviCaseRef'] = $searchValues['ukviCaseRef'];
        $advancedSearchFields['ukviMember'] = $searchValues['ukviMember'];
        $advancedSearchFields['ukviCopyToNo10'] = $searchValues['ukviCopyToNo10'];
        $advancedSearchFields['ukviFirstName'] = $searchValues['ukviFirstName'];
        $advancedSearchFields['ukviSurname'] = $searchValues['ukviSurname'];
        $advancedSearchFields['ukviPostcode'] = $searchValues['ukviPostcode'];
        $advancedSearchFields['ukviEmail'] = $searchValues['ukviEmail'];
        return $advancedSearchFields;
    }

    /**
     *
     * @param array $searchValues
     * @return array
     */
    private function handleNo10SearchFilters($searchValues)
    {
        $advancedSearchFields = array();
        $advancedSearchFields['tenReceivedDateFrom'] = $searchValues['tenReceivedDateFrom'];
        $advancedSearchFields['tenReceivedDateTo'] = $searchValues['tenReceivedDateTo'];
        $advancedSearchFields['tenPriority'] = $searchValues['tenPriority'];
        $advancedSearchFields['tenAdvice'] = $searchValues['tenAdvice'];
        $advancedSearchFields['tenMemberRef'] = $searchValues['tenMemberRef'];
        $advancedSearchFields['tenMember'] = $searchValues['tenMember'];
        return $advancedSearchFields;
    }

    /**
     *
     * @param array $searchValues
     * @return array
     */
    private function handleHmpoGenSearchFilters($searchValues)
    {
        $advancedSearchFields = array();
        $advancedSearchFields['genReceivedDateFrom'] = $searchValues['genReceivedDateFrom'];
        $advancedSearchFields['genReceivedDateTo'] = $searchValues['genReceivedDateTo'];
        $advancedSearchFields['genPassportNumber'] = $searchValues['genPassportNumber'];
        $advancedSearchFields['genApplicationNumber'] = $searchValues['genApplicationNumber'];
        $advancedSearchFields['genFirstName'] = $searchValues['genFirstName'];
        $advancedSearchFields['genSurname'] = $searchValues['genSurname'];
        $advancedSearchFields['genPostcode'] = $searchValues['genPostcode'];
        $advancedSearchFields['genEmail'] = $searchValues['genEmail'];
        return $advancedSearchFields;
    }

    /**
     *
     * @param array $searchValues
     * @return array
     */
    private function handleHmpoComSearchFilters($searchValues)
    {
        $advancedSearchFields = array();
        $advancedSearchFields['comReceivedDateFrom'] = $searchValues['comReceivedDateFrom'];
        $advancedSearchFields['comReceivedDateTo'] = $searchValues['comReceivedDateTo'];
        $advancedSearchFields['comPassportNumber'] = $searchValues['comPassportNumber'];
        $advancedSearchFields['comApplicationNumber'] = $searchValues['comApplicationNumber'];
        $advancedSearchFields['comComplaintStage'] = $searchValues['comComplaintStage'];
        $advancedSearchFields['comFirstName'] = $searchValues['comFirstName'];
        $advancedSearchFields['comSurname'] = $searchValues['comSurname'];
        $advancedSearchFields['comPostcode'] = $searchValues['comPostcode'];
        $advancedSearchFields['comEmail'] = $searchValues['comEmail'];
        return $advancedSearchFields;
    }

    /**
     * @Template
     * @Route("/correspondentSearch")
     * @Method({"GET", "POST"})
     */
    public function correspondentSearchAction(Request $request)
    {
        $paginator = $this->setupPaginator($request->query, 'homeoffice_cts_search_correspondentsearch');
        $searchByForm = $this->createForm(new CorrespondentSearchByFieldType());
        $ctsCaseSearchRepository = $this->get('home_office_alfresco_api.cts_case_search.repository');
        $ctsHelper = $this->get('home_office_alfresco_api.cts_case.cts_helper');
        $ctsSearchResults = array();
        $searchPerformed = false;

        $searchByForm->handleRequest($request);

        if ($searchByForm->get('clear')->isClicked()) {
            return $this->redirect($this->generateUrl('homeoffice_cts_search_correspondentsearch'));
        }

        if ($searchByForm->get('searchButton')->isClicked()) {
            $searchPerformed = true;
            $values = $searchByForm->getData();

            $ctsSearchResults = $ctsCaseSearchRepository->getCorrespondentSearchResults(
                $values['dateCreatedFrom'],
                $values['dateCreatedTo'],
                $values['replyForename'],
                $values['replySurname'],
                $values['postcode'],
                $values['email'],
                $paginator
            );
        }

        return array(
            'ctsSearchResults' => $ctsSearchResults,
            'ctsHelper' => $ctsHelper,
            'searchByForm' => $searchByForm->createView(),
            'searchPerformed' => $searchPerformed,
            'paginator' => $paginator,
            'orderByField' => null,
            'orderByDirection' => null
        );
    }
}

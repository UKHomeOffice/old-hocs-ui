<?php

namespace HomeOffice\AlfrescoApiBundle\Repository;

use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Exception\ServerException;
use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use HomeOffice\AlfrescoApiBundle\Factory\CtsCaseFactory;
use HomeOffice\ProcessManagerAuthenticatorBundle\Security\SessionTicketStorage;
use HomeOffice\AlfrescoApiBundle\Service\AtomHelper;
use HomeOffice\AlfrescoApiBundle\Service\QueryHelper;
use HomeOffice\AlfrescoApiBundle\Service\CTSHelper;
use HomeOffice\AlfrescoApiBundle\Service\DateHelper;
use HomeOffice\AlfrescoApiBundle\Factory\CtsCaseDocumentTemplateFactory;
use HomeOffice\AlfrescoApiBundle\Entity\CtsCaseDocumentTemplate;
use GuzzleHttp\Exception\RequestException;

class CtsCaseDocumentTemplateRepository
{

    const FILE_ALREADY_EXISTS_ERROR = 'A template with that filename already exists.';
    const DEFAULT_ERROR = 'An error occurred trying to add the template, please try again later.';

    /**
     * @var Guzzle
     */
    private $apiClient;

    /**
     * @var CtsCaseDocumentTemplateFactory
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
     * @var CTSHelper
     */
    private $ctsHelper;

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
    private $caseDocumentTemplateProperties;

    /**
     * Constructor
     *
     * @param Guzzle                         $apiClient
     * @param CtsCaseDocumentTemplateFactory $ctsCaseDocumentTemplateFactory
     * @param SessionTicketStorage           $tokenStorage
     * @param AtomHelper                     $atomHelper
     * @param QueryHelper                    $queryHelper
     * @param CTSHelper                      $ctsHelper
     * @param string                         $workspace
     * @param string                         $store
     * @param array                          $caseDocumentTemplateProperties
     */
    public function __construct(
        Guzzle $apiClient,
        CtsCaseDocumentTemplateFactory $ctsCaseDocumentTemplateFactory,
        SessionTicketStorage $tokenStorage,
        AtomHelper $atomHelper,
        QueryHelper $queryHelper,
        CTSHelper $ctsHelper,
        $workspace,
        $store,
        $caseDocumentTemplateProperties
    ) {
        $this->apiClient = $apiClient;
        $this->apiClient->setDefaultOption('version', [
            'CURLOPT_HTTP_VERSION' => 'CURL_HTTP_VERSION_1_0',
        ]);
        $this->factory = $ctsCaseDocumentTemplateFactory;
        $this->tokenStorage = $tokenStorage;
        $this->atomHelper = $atomHelper;
        $this->queryHelper = $queryHelper;
        $this->ctsHelper = $ctsHelper;
        $this->workspace = $workspace;
        $this->store = $store;
        $this->caseDocumentTemplateProperties = $caseDocumentTemplateProperties;
    }

   /**
     * @param CtsCaseDocumentTemplate $ctsCaseDocumentTemplate
     * @return bool | array
     */
    public function create($ctsCaseDocumentTemplate)
    {
        $ctsCaseDocumentTemplate->upload();
        $body = array(
            'file' => fopen($ctsCaseDocumentTemplate->getWebPath(), 'r'),
            'name' => $ctsCaseDocumentTemplate->getName(),
            'appliesToCorrespondenceType' => $ctsCaseDocumentTemplate->getAppliesToCorrespondenceType(),
            'templateName' => $ctsCaseDocumentTemplate->getTemplateName(),
            //@codingStandardsIgnoreStart
            'validFromDate' => urlencode(DateHelper::fromNativeOrNullToIso($ctsCaseDocumentTemplate->getValidFromDate())),
            'validToDate' => urlencode(DateHelper::fromNativeOrNullToIso($ctsCaseDocumentTemplate->getValidToDate()))
            //@codingStandardsIgnoreEnd
        );

        try {
            $response = $this->apiClient->post('s/homeoffice/cts/documentTemplate', [
                'query' => ['alf_ticket' => $this->tokenStorage->getAuthenticationTicket()],
                'body' => $body,
            ]);
        } catch (RequestException $exception) {
            $response = $exception->getResponse();
            $responseArray = array();
            switch ($response->getStatusCode()) {
                case '409':
                    //@codingStandardsIgnoreStart
                    $responseArray = array('code' => 409, 'message' => CtsCaseDocumentTemplateRepository::FILE_ALREADY_EXISTS_ERROR);
                    //@codingStandardsIgnoreEnd
                    break;
                default:
                    $responseArray = array('code' => -1, 'message' => CtsCaseDocumentTemplateRepository::DEFAULT_ERROR);
            }
            return $responseArray;
        }

        if ($response->getStatusCode() != '200') {
            return false;
        }
        $responseBody = json_decode($response->getBody()->__toString());
        $ctsCaseDocumentTemplate->setId($responseBody->id);
        return true;
    }

    public function update($ctsDocumentTemplate)
    {
        $atomEntry = $this->atomHelper->generateAtomEntry(
            $ctsDocumentTemplate,
            $ctsDocumentTemplate->getName(),
            $this->caseDocumentTemplateProperties,
            'required_for_update'
        );
        $nodeId = $ctsDocumentTemplate->getNodeId();
        $response = $this->apiClient->put("s/api/node/$this->workspace/$this->store/$nodeId", [
            'headers' => ['Content-Type' => 'application/atom+xml;type=entry'],
            'query' => ['alf_ticket' => $this->tokenStorage->getAuthenticationTicket()],
            'body' => $atomEntry,
        ]);
        if ($response->getStatusCode() != '200') {
            return false;
        }

        return true;
    }

    /**
     * @param string $correspondenceType
     * @param bool   $restrictToValidToday
     *
     * @return array|bool
     */
    public function getDocumentTemplates($correspondenceType = null, $restrictToValidToday = true)
    {
        $select = array(
            'cmis:objectId',
            'cmis:name',
            'cmis:creationDate',
            'cmis:contentStreamMimeType',
            'cts:validFromDate',
            'cts:validToDate',
            'cts:templateName',
            'cts:appliesToCorrespondenceType'
        );
        $from = 'cts:caseDocumentTemplate';
        $where = array();
        if ($correspondenceType != null) {
            array_push($where, "cts:appliesToCorrespondenceType LIKE '".$correspondenceType."'");
        }
        if ($restrictToValidToday) {
            $today = DateHelper::now()->midnight()->toIso();
            array_push($where, "(cts:validFromDate <= '$today')");
            array_push($where, "(cts:validToDate >= '$today')");
        }
        $query = $this->queryHelper->constructSimpleCmisQuery($select, $from, $where);
        $response = $this->apiClient->get("s/cmis/query", [
            'query' => [
                'alf_ticket' => $this->tokenStorage->getAuthenticationTicket(),
                'q' => $query,
            ]
        ]);
        if ($response->getStatusCode() != '200') {
            return [];
        }
        $responseBody = $response->getBody()->__toString();
        $documentTemplates = $this->atomHelper->multiEntryFeedToArray(
            $responseBody,
            'cmisra:object',
            $this->caseDocumentTemplateProperties,
            null,
            null
        );
        return $documentTemplates;
    }

    /**
     * @param CtsCase $case
     * @param bool    $restrictToValidToday
     *
     * @return CtsCaseDocumentTemplate[]
     */
    public function getDocumentTemplateObjects(CtsCase $case = null, $restrictToValidToday = true)
    {
        $correspondentType = $case ? $case->getCorrespondenceType() : null;

        $documentTemplates = [];
        foreach ($this->getDocumentTemplates($correspondentType, $restrictToValidToday) as $template) {
            array_push($documentTemplates, $this->factory->build($template));
        }
        return $documentTemplates;
    }

    /**
     * @param CtsCase $case
     * @param bool    $restrictToValidToday
     * @param string  $name
     *
     * @return CtsCaseDocumentTemplate|null
     */
    public function getDocumentTemplateByNameMatch(CtsCase $case, $restrictToValidToday, $name)
    {
        foreach ($this->getDocumentTemplateObjects($case, $restrictToValidToday) as $documentTemplate) {
            if (strpos($documentTemplate->getName(), $name) !== false) {
                return $documentTemplate;
            }
        }

        return null;
    }

    /**
     *
     * @param string $nodeRef
     * @return boolean | CtsCaseDocumentTemplate
     */
    public function getDocumentTemplate($nodeRef)
    {
        $response = $this->apiClient->get("s/api/node/$this->workspace/$this->store/$nodeRef", [
            'query' => ['alf_ticket' => $this->tokenStorage->getAuthenticationTicket()],
        ]);
        if ($response->getStatusCode() != '200') {
            return false;
        }
        $responseBody = $response->getBody()->__toString();
        $document = $this->atomHelper->singleEntryFeedToArray(
            $responseBody,
            'cmisra:object',
            $this->caseDocumentTemplateProperties,
            null
        );
        return $this->factory->build($document);
    }

    /**
     * Permanently deletes a document template.
     * @param string $nodeRef
     * @return boolean
     */
    public function deleteDocumentTemplate($nodeRef)
    {
        $ctsCaseDocumentTemplate = new CtsCaseDocumentTemplate($this->workspace, $this->store);
        $ctsCaseDocumentTemplate->setId($nodeRef);
        $nodeId = $ctsCaseDocumentTemplate->getNodeId();
        $response = $this->apiClient->delete("s/api/node/$this->workspace/$this->store/$nodeId", [
            'query' => ['alf_ticket' => $this->tokenStorage->getAuthenticationTicket()],
        ]);
        if ($response->getStatusCode() != '204') {
            return false;
        }
        return true;
    }

    /**
     * Given a CtsCaseDocumentTemplate object, this function will download the file to /tmp/{nodeId}
     * and return the CtsCaseDocumentTemplate object.
     * @param string $documentNodeRef
     * @param string $caseNodeRef
     * @return boolean | CtsCaseDocumentTemplate
     */
    public function getDocumentTemplateFile($documentNodeRef, $caseNodeRef)
    {
        try {
            $response = $this->apiClient->get("s/homeoffice/cts/template", [
                'query' => [
                    'alf_ticket'      => $this->tokenStorage->getAuthenticationTicket(),
                    'documentNodeRef' => $documentNodeRef,
                    'caseNodeRef'     => $caseNodeRef
                ],
                'save_to' => '/tmp/'.$documentNodeRef,
            ]);
        } catch (\Exception $e) {
            return false;
        }

        if ($response->getStatusCode() != '200') {
            return false;
        }

        return $this->getDocumentTemplate($documentNodeRef);
    }
}

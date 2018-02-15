<?php

namespace HomeOffice\AlfrescoApiBundle\Repository;

use GuzzleHttp\Client as Guzzle;
use HomeOffice\AlfrescoApiBundle\Service\DateHelper;
use HomeOffice\ProcessManagerAuthenticatorBundle\Security\SessionTicketStorage;
use HomeOffice\AlfrescoApiBundle\Service\AtomHelper;
use HomeOffice\AlfrescoApiBundle\Service\QueryHelper;
use HomeOffice\AlfrescoApiBundle\Service\CTSHelper;
use HomeOffice\AlfrescoApiBundle\Factory\CtsCaseStandardLineFactory;
use HomeOffice\AlfrescoApiBundle\Entity\CtsCaseStandardLine;
use GuzzleHttp\Exception\RequestException;

/**
 * Class CtsCaseStandardLineRepository
 *
 * @package HomeOffice\AlfrescoApiBundle\Repository
 */
class CtsCaseStandardLineRepository
{
    const DEFAULT_ERROR = 'An error occurred trying to add the standard line, please try again later.';
    const VIRUS_ERROR = "A Virus was found in the file. Do not try again.";
 
    /**
     * @var Guzzle
     */
    private $apiClient;

    /**
     * @var CtsCaseStandardLineFactory
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
    private $caseStandardLineProperties;
 
    /**
     * @var CtsCaseDocumentRepository
     */
    private $ctsCaseDocumentRepository;

    /**
     * Constructor
     *
     * @param Guzzle                     $apiClient
     * @param CtsCaseStandardLineFactory $ctsCaseStandardLineFactory
     * @param SessionTicketStorage       $tokenStorage
     * @param AtomHelper                 $atomHelper
     * @param QueryHelper                $queryHelper
     * @param CTSHelper                  $ctsHelper
     * @param string                     $workspace
     * @param string                     $store
     * @param array                      $caseStandardLineProperties
     * @param CtsCaseDocumentRepository  $ctsCaseDocumentRepository
     */
    public function __construct(
        Guzzle $apiClient,
        CtsCaseStandardLineFactory $ctsCaseStandardLineFactory,
        SessionTicketStorage $tokenStorage,
        AtomHelper $atomHelper,
        QueryHelper $queryHelper,
        CTSHelper $ctsHelper,
        $workspace,
        $store,
        array $caseStandardLineProperties,
        CtsCaseDocumentRepository $ctsCaseDocumentRepository
    ) {
        $this->apiClient = $apiClient;
        $this->apiClient->setDefaultOption('version', [
            'CURLOPT_HTTP_VERSION' => 'CURL_HTTP_VERSION_1_0',
            "CURLOPT_SSL_VERIFYHOST" => "0",
            "CURLOPT_SSL_VERIFYPEER" => "0"
        ]);
        $this->factory = $ctsCaseStandardLineFactory;
        $this->tokenStorage = $tokenStorage;
        $this->atomHelper = $atomHelper;
        $this->queryHelper = $queryHelper;
        $this->ctsHelper = $ctsHelper;
        $this->workspace = $workspace;
        $this->store = $store;
        $this->caseStandardLineProperties = $caseStandardLineProperties;
        $this->ctsCaseDocumentRepository = $ctsCaseDocumentRepository;
    }

    /**
     * Create
     *
     * @param CtsCaseStandardLine $standardLine
     *
     * @return array|bool
     */
    public function create(CtsCaseStandardLine $standardLine)
    {
        try {
            $standardLine->upload();

            $file1 = fopen($standardLine->getWebPath(), 'r');
            $file2 = fopen($standardLine->getWebPath(), 'r');

            //Virus scan
            if ($this->environment != "dc") {

                $virusBody = array(
                    'file' => $file1,
                    'name' => $standardLine->getName()
                );

                $virusClient = new Guzzle();
                $virusClient->setDefaultOption('version', [
                    'CURLOPT_HTTP_VERSION' => 'CURL_HTTP_VERSION_1_0',
                    "CURLOPT_SSL_VERIFYHOST" => "0",
                    "CURLOPT_SSL_VERIFYPEER" => "0"
                ]);

                try {
                    $virusResponse = $virusClient->post('https://clamav.virus-scan.svc.cluster.local/scan',  [
                        'body' => $virusBody,
                        'verify' => false
                    ]);

                    if (strpos($virusResponse, 'Everything ok : false')) {
                        fclose($file1);
                        return $responseArray = [
                            'code' => 500,
                            'message' => BulkDocumentRepository::VIRUS_ERROR
                        ];
                    }
                } catch (RequestException $exception) {
                    fclose($file1);
                    return $responseArray = [
                        'code' => 500,
                        'message' => BulkDocumentRepository::DEFAULT_ERROR
                    ];
                }
            }

            $response = $this->apiClient->post('s/homeoffice/cts/standardLine', [
                'query' => [
                    'alf_ticket' => $this->tokenStorage->getAuthenticationTicket()
                ],
                'body'  => [
                    'file'            => $file2,
                    'name'            => rawurlencode($standardLine->getName()),
                    'associatedUnit'  => rawurlencode($standardLine->getAssociatedUnit()),
                    'associatedTopic' => rawurlencode($standardLine->getAssociatedTopic()),
                    'reviewDate'      => rawurlencode(DateHelper::fromNativeOrNullToIso($standardLine->getReviewDate())),
                    'updateVersion'   => $standardLine->isNew() ? "false" : "true",
                    'originalName'    => rawurlencode($standardLine->getOriginalName()),
                ],
            ]);
        } catch (RequestException $exception) {
            $response = $exception->getResponse();
        }

        if ($response->getStatusCode() !== 200) {
            switch ($response->getStatusCode()) {
                case '409':
                    $responseBody = json_decode($response->getBody()->__toString());
                    return ['code' => 409, 'message' => $responseBody->message];
                default:
                    return ['code' => -1, 'message' => self::DEFAULT_ERROR];
            }
        }

        return true;
    }

    /**
     * @param CtsCaseStandardLine $ctsCaseStandardLine
     *
     * @return bool
     */
    public function update(CtsCaseStandardLine $ctsCaseStandardLine)
    {
        $ctsCaseStandardLine->setMimeType($ctsCaseStandardLine->getMimeType());

        $nodeId = $ctsCaseStandardLine->getNodeId();
        $response = $this->apiClient->put("s/api/node/$this->workspace/$this->store/$nodeId", [
            'headers' => ['Content-Type' => 'application/atom+xml;type=entry'],
            'query' => ['alf_ticket' => $this->tokenStorage->getAuthenticationTicket()],
            'body' => $this->atomHelper->generateAtomEntry(
                $ctsCaseStandardLine,
                $ctsCaseStandardLine->getName(),
                $this->caseStandardLineProperties
            ),
        ]);

        return $response->getStatusCode() == '200';
    }
 
    /**
     * Get StandardLines
     *
     * @param string|null $unit
     * @param string|null $topic
     * @param string|null $documentName
     *
     * @return CtsCaseStandardLine[]|bool
     */
    public function getStandardLines($unit, $topic, $documentName = null)
    {
        $select = [
            'cmis:objectId',
            'cmis:name',
            'cmis:creationDate',
            'cmis:contentStreamMimeType',
            'cts:reviewDate',
            'cts:associatedTopic',
            'cts:associatedUnit'
        ];
        $from = 'cts:standardLine';
        $where = $orWhere = $andWhere = [];
     
        $this->queryHelper
            ->addToWhereStatement($where, 'cts:associatedUnit', '=', $unit)
            ->addToWhereStatement($where, 'cts:associatedTopic', '=', $topic)
            ->addToWhereStatement($where, 'cmis:name', ' LIKE ', $documentName, true, true);

        $orderBy = $this->queryHelper->getQueueOrderByStatement('cts:reviewDate', 'ASC');

        $query = $this->queryHelper
            ->constructQueryWithMultipleFilters($select, $from, $where, $orWhere, $andWhere, $orderBy);

        $response = $this->apiClient->get("s/cmis/query", ['query' => [
            'alf_ticket' => $this->tokenStorage->getAuthenticationTicket(),
            'q'          => $query
        ]]);

        if ($response->getStatusCode() != '200') {
            return false;
        }

        $standardLinesArray = $this->atomHelper->multiEntryFeedToArray(
            $response->getBody()->__toString(),
            'cmisra:object',
            $this->caseStandardLineProperties
        );

        $standardLines = [];
        foreach ($standardLinesArray as $standardLine) {
            array_push($standardLines, $this->factory->build($standardLine));
        }

        return $standardLines;
    }
     
    /**
     * Get Standard Line
     *
     * @param string $nodeRef
     *
     * @return bool|CtsCaseStandardLine
     */
    public function getStandardLine($nodeRef)
    {
        $response = $this->apiClient->get("s/api/node/$this->workspace/$this->store/$nodeRef", [
            'query' => ['alf_ticket' => $this->tokenStorage->getAuthenticationTicket()],
        ]);

        if ($response->getStatusCode() != '200') {
            return false;
        }

        return $this->factory->build($this->atomHelper->singleEntryFeedToArray(
            $response->getBody()->__toString(),
            'cmisra:object',
            $this->caseStandardLineProperties,
            null
        ));
    }
 
    /**
     * Get StandardLines By Topics
     *
     * @param string[]    $topics
     * @param string|null $unit
     *
     * @return CtsCaseStandardLine[]|bool
     */
    public function getStandardLinesByTopics(array $topics, $unit = null)
    {
        $select = [
            'cmis:objectId',
            'cmis:name',
            'cmis:creationDate',
            'cmis:contentStreamMimeType',
            'cts:reviewDate',
            'cts:associatedTopic',
            'cts:associatedUnit'
        ];
        $from = 'cts:standardLine';
        $where = $orWhere = $andWhere = [];

        if (is_null($unit) === false) {
            $this->queryHelper->addToWhereStatement($where, 'cts:associatedUnit', '=', $unit);
        }
        $this->queryHelper->addWhereInToWhereStatement($where, 'cts:associatedTopic', $topics);
        $orderBy = $this->queryHelper->getQueueOrderByStatement('cts:reviewDate', 'ASC');

        $query = $this->queryHelper
            ->constructQueryWithMultipleFilters($select, $from, $where, $orWhere, $andWhere, $orderBy);

        $response = $this->apiClient->get("s/cmis/query", ['query' => [
            'alf_ticket' => $this->tokenStorage->getAuthenticationTicket(),
            'q'          => $query
        ]]);

        if ($response->getStatusCode() != '200') {
            return false;
        }

        $standardLinesArray = $this->atomHelper->multiEntryFeedToArray(
            $response->getBody()->__toString(),
            'cmisra:object',
            $this->caseStandardLineProperties
        );

        $standardLines = [];
        foreach ($standardLinesArray as $standardLine) {
            array_push($standardLines, $this->factory->build($standardLine));
        }

        return $standardLines;
    }

    /**
     * Delete Standard Line
     *
     * @param string $nodeRef
     *
     * @return bool
     */
    public function deleteStandardLine($nodeRef)
    {
        $ctsCaseStandardLine = new CtsCaseStandardLine($this->workspace, $this->store);
        $ctsCaseStandardLine->setId($nodeRef);
        $nodeId = $ctsCaseStandardLine->getNodeId();

        $response = $this->apiClient->delete("s/api/node/$this->workspace/$this->store/$nodeId", [
            'query' => ['alf_ticket' => $this->tokenStorage->getAuthenticationTicket()],
        ]);

        return $response->getStatusCode() == '204';
    }
 
    /**
     * Get Standard Line File
     *
     * Given a CtsCaseStandardLine object, this function will download the file to /tmp/{nodeId}
     * and return the CtsCaseStandardLine object.
     *
     * @param string $nodeRef
     *
     * @return bool|CtsCaseStandardLine
     */
    public function getStandardLineFile($nodeRef)
    {
        $standardLine = $this->getStandardLine($nodeRef);
        $nodeId = $standardLine->getNodeId();

        $response = $this->apiClient->get("s/api/node/$this->workspace/$this->store/$nodeId/content", [
            'query' => ['alf_ticket' => $this->tokenStorage->getAuthenticationTicket()],
            'save_to' => "/tmp/$nodeId",
        ]);

        if ($response->getStatusCode() != '200') {
            return false;
        }

        return $standardLine;
    }
}

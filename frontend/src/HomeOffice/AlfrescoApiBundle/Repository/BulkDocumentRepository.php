<?php

namespace HomeOffice\AlfrescoApiBundle\Repository;

use GuzzleHttp\Client as Guzzle;
use HomeOffice\ProcessManagerAuthenticatorBundle\Security\SessionTicketStorage;
use HomeOffice\AlfrescoApiBundle\Service\AtomHelper;
use HomeOffice\AlfrescoApiBundle\Service\QueryHelper;
use HomeOffice\AlfrescoApiBundle\Service\CTSHelper;
use HomeOffice\AlfrescoApiBundle\Entity\BulkDocument;
use GuzzleHttp\Exception\RequestException;

class BulkDocumentRepository
{

    const FILE_ALREADY_EXISTS_ERROR = 'An auto create file with that filename already exists, please check errors.';
    const DEFAULT_ERROR = 'An error occurred trying to add the file, please try again later.';
    const VIRUS_ERROR = "A Virus was found in the file. Do not try again.";

    /**
     * @var Guzzle
     */
    private $apiClient;

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
    private $bulkDocumentProperties;

    /**
     *
     * @param \GuzzleHttp\Client $apiClient
     * @param SessionTicketStorage $tokenStorage
     * @param AtomHelper $atomHelper
     * @param QueryHelper $queryHelper
     * @param string $workspace
     * @param string $store
     */
    public function __construct(
        Guzzle $apiClient,
        SessionTicketStorage $tokenStorage,
        AtomHelper $atomHelper,
        QueryHelper $queryHelper,
        CTSHelper $ctsHelper,
        $workspace,
        $store,
        $bulkDocumentProperties
    ) {
        $this->apiClient = $apiClient;
        $this->apiClient->setDefaultOption(
            'version',
            array(
                "CURLOPT_HTTP_VERSION" => "CURL_HTTP_VERSION_1_0"
            )
        );
        $this->tokenStorage = $tokenStorage;
        $this->atomHelper = $atomHelper;
        $this->queryHelper = $queryHelper;
        $this->ctsHelper = $ctsHelper;
        $this->workspace = $workspace;
        $this->store = $store;
        $this->bulkDocumentProperties = $bulkDocumentProperties;
    }

    /**
     * @param BulkDocument $document
     * @param string       $caseType
     * @param string       $assignedUnit
     * @param string       $assignedTeam
     * @param string       $assignedUser
     *
     * @return array|bool
     */
    public function create(
        BulkDocument $document,
        $caseType,
        $assignedUnit = null,
        $assignedTeam = null,
        $assignedUser = null
    ) {
        $document->upload();

        $file1 = fopen($document->getWebPath(), 'r');
        $file2 = fopen($document->getWebPath(), 'r');

        //Virus scan
        if ($this->environment != "dc") {

            $virusBody = array(
                'file' => $file1,
                'name' => $document->getName()
            );

            $virusClient = new Guzzle();
            $virusClient->setDefaultOption('version', [
                'CURLOPT_HTTP_VERSION' => 'CURL_HTTP_VERSION_1_0',
                "CURLOPT_SSL_VERIFYHOST" => "0",
                "CURLOPT_SSL_VERIFYPEER" => "0"
            ]);

            try {
                $virusResponse = $virusClient->post('https://clam/scan',  [
                    'body' => $virusBody,
                    'verify' => false
                ]);

                if (strpos($virusResponse, 'Everything ok : false')) {
                    return $responseArray = [
                        'code' => 500,
                        'message' => BulkDocumentRepository::VIRUS_ERROR
                    ];
                }
            } catch (RequestException $exception) {
                return $responseArray = [
                    'code' => 500,
                    'message' => BulkDocumentRepository::DEFAULT_ERROR
                ];
            }
        }

        $body = [
            'file'         => $file2,
            'name'         => $document->getName(),
            'caseType'     => $caseType,
            'assignedUnit' => $assignedUnit,
            'assignedTeam' => $assignedTeam,
            'assignedUser' => $assignedUser
        ];

        try {
            $response = $this->apiClient->post('s/homeoffice/cts/autoCreateDocument', [
                'query' => ['alf_ticket' => $this->tokenStorage->getAuthenticationTicket()],
                'body'  => $body,
            ]);
        } catch (RequestException $exception) {
            $response = $exception->getResponse();

            switch ($response->getStatusCode()) {
                case '409':
                    $responseArray = [
                        'code' => 409,
                        'message' => BulkDocumentRepository::FILE_ALREADY_EXISTS_ERROR
                    ];
                    break;

                default:
                    $responseArray = [
                        'code' => -1,
                        'message' => BulkDocumentRepository::DEFAULT_ERROR,
                    ];
            }

            return $responseArray;
        }

        if ($response->getStatusCode() != '200') {
            return [
                'code'    => -1,
                'message' => BulkDocumentRepository::DEFAULT_ERROR,
            ];
        }
        $responseBody = json_decode($response->getBody()->__toString());

        $document->setId($responseBody->id);

        return true;
    }

    /**
     * @return array|bool
     */
    public function getBulkCreateErrors()
    {
        $select = array(
            'd.*',
            'a.cts:autoCreateFailureDateTime',
            'a.cts:autoCreateFailureMessage'
        );
        $from = 'cts:caseDocument d JOIN cts:autoCreateFailure a ON a.cmis:objectId = d.cmis:objectId';
        $whereArray = array();
        $orderBy = $this->queryHelper->getQueueOrderByStatement('a.cts:autoCreateFailureDateTime', 'DESC');

        $query = $this->queryHelper->constructQueryWithMultipleFilters(
            $select,
            $from,
            $whereArray,
            array(),
            array(),
            $orderBy
        );

        $response = $this->apiClient->get("s/cmis/query", [
            'query' => [
                'alf_ticket' => $this->tokenStorage->getAuthenticationTicket(),
                'q' => $query
            ]
        ]);
        if ($response->getStatusCode() != '200') {
            return false;
        }
        $responseBody = $response->getBody()->__toString();
        $bulkDocuments = $this->atomHelper->multiEntryFeedToArray(
            $responseBody,
            'cmisra:object',
            $this->bulkDocumentProperties,
            null,
            null
        );
        return $bulkDocuments;
    }

    /**
     * Permanently deletes a bulk document.
     * @param string $nodeRef
     * @return boolean
     */
    public function delete($nodeRef)
    {
        $bulkDocument = new BulkDocument($this->workspace, $this->store);
        $bulkDocument->setId($nodeRef);
        $nodeId = $bulkDocument->getNodeId();
        $response = $this->apiClient->delete("s/api/node/$this->workspace/$this->store/$nodeId", [
            'query' => ['alf_ticket' => $this->tokenStorage->getAuthenticationTicket()]
        ]);
        if ($response->getStatusCode() != '204') {
            return false;
        }
        return true;
    }
}

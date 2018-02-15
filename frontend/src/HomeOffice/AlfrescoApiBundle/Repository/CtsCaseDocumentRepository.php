<?php

namespace HomeOffice\AlfrescoApiBundle\Repository;

use GuzzleHttp\Client as Guzzle;
use HomeOffice\ProcessManagerAuthenticatorBundle\Security\SessionTicketStorage;
use HomeOffice\AlfrescoApiBundle\Service\AtomHelper;
use HomeOffice\AlfrescoApiBundle\Service\QueryHelper;
use HomeOffice\AlfrescoApiBundle\Repository\PersonRepository;
use HomeOffice\AlfrescoApiBundle\Factory\CtsCaseDocumentFactory;
use HomeOffice\AlfrescoApiBundle\Entity\CtsCaseDocument;
use GuzzleHttp\Exception\RequestException;
use Monolog\Logger;
use Tedivm\StashBundle\Service\CacheService;

class CtsCaseDocumentRepository
{
    /**
     * @var Guzzle
     */
    private $apiClient;

    /**
     * @var CtsCaseDocumentFactory
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
     * @var string
     */
    private $workspace;

    /**
     * @var string
     */
    private $store;

    /** @var  Logger */
    protected $logger;
    /**
     * @var array
     */
    private $caseDocumentProperties;

    /**
     * @var PersonRepository
     */
    private $personRepository;

    protected $cacheService;

    protected $cacheTimeout;

    private  $environment;

    /**
     *
     * @param \GuzzleHttp\Client $apiClient
     * @param CtsCaseDocumentFactory $ctsCaseDocumentFactory
     * @param SessionTicketStorage $tokenStorage
     * @param AtomHelper $atomHelper
     * @param QueryHelper $queryHelper
     * @param string $workspace
     * @param string $store
     * @param array $caseDocumentProperties
     * @param PersonRepository $personRepository
     * @param Logger $logger
     */
    public function __construct(
        $env,
        CacheService $cacheService,
        $cacheTimeout,
        Guzzle $apiClient,
        CtsCaseDocumentFactory $ctsCaseDocumentFactory,
        SessionTicketStorage $tokenStorage,
        AtomHelper $atomHelper,
        QueryHelper $queryHelper,
        $workspace,
        $store,
        $caseDocumentProperties,
        PersonRepository $personRepository,
        Logger $logger
    ) {
        $this->apiClient = $apiClient;
        $this->apiClient->setDefaultOption(
            'version',
            array(
                "CURLOPT_HTTP_VERSION" => "CURL_HTTP_VERSION_1_0",
                "CURLOPT_SSL_VERIFYHOST" => "0",
                "CURLOPT_SSL_VERIFYPEER" => "0"
            )
        );
        $this->factory = $ctsCaseDocumentFactory;
        $this->tokenStorage = $tokenStorage;
        $this->atomHelper = $atomHelper;
        $this->queryHelper = $queryHelper;
        $this->workspace = $workspace;
        $this->store = $store;
        $this->caseDocumentProperties = $caseDocumentProperties;
        $this->personRepository = $personRepository;
        $this->logger = $logger;

        $this->cacheService = $cacheService;
        $this->cacheTimeout = $cacheTimeout;
        $this->environment = $env;

    }

    /**
     * @param CtsCaseDocument $ctsCaseDocument
     * @param string $caseNodeRef
     * @param string $caseNodeId
     * @return bool
     */
    public function create($ctsCaseDocument, $caseNodeRef, $caseNodeId)
    {

        $ctsCaseDocument->upload($caseNodeId);

        $myFile = fopen($ctsCaseDocument->getWebPath($caseNodeId), 'r');
        $myFile2 = fopen($ctsCaseDocument->getWebPath($caseNodeId), 'r');

        //Virus scan
        if ($this->environment != "dc") {

            $virusBody = array(
                'file' => $myFile,
                'name' => $this->versionFileName($caseNodeId, $ctsCaseDocument)
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
                    return "virus";
                }
            } catch (RequestException $exception) {
                $virusResponse = $exception->getResponse()->getBody()->__toString();
                return false;
            }
        }

        $body = array(
            'file' => $myFile2,
            'name' => $this->versionFileName($caseNodeId, $ctsCaseDocument),
            'destination' => $caseNodeRef,
            'documenttype' => $ctsCaseDocument->getDocumentType(),
            'documentdescription' => $ctsCaseDocument->getDocumentDescription()
        );

        try {
            $response = $this->apiClient->post('s/homeoffice/cts/document', [
                'query' => ['alf_ticket' => $this->tokenStorage->getAuthenticationTicket()],
                'body' => $body
            ]);
        } catch (RequestException $exception) {
            $response = json_decode($exception->getResponse()->getBody()->__toString());
            return $response->message;
        }
        $responseBody = json_decode($response->getBody()->__toString());
        $ctsCaseDocument->setId($responseBody->id);

        return true;
    }

    /**
     * @param  $caseNodeId
     * @return array
     */
    private function reduceCaseDocuments($caseNodeId)
    {
        return array_map(
            function ($value) {
                return $value['name'];
            },
            $this->getDocumentsForCase($caseNodeId)
        );
    }

    /**
     * @param  $caseNodeId
     * @param  $ctsCaseDocument
     * @param  int              $counter
     * @return string
     */
    private function versionFileName($caseNodeId, $ctsCaseDocument, $counter = 0)
    {
        $newFilename  = $ctsCaseDocument->getName();
        $extension    = pathinfo($newFilename, PATHINFO_EXTENSION);
        $name         = pathinfo($newFilename, PATHINFO_FILENAME);

        while (in_array($newFilename, $this->reduceCaseDocuments($caseNodeId))) {
            $newFilename = $counter === 0 ? : $name . '_' . $counter . '.' . $extension;
            $counter++;
        }

        return $newFilename;
    }

    /**
     *
     * @param string $caseNodeId
     * @return array
     */
    public function getDocumentsForCase($caseNodeId, $hydrate = false)
    {
        $response = $this->apiClient->get("s/api/node/$this->workspace/$this->store/$caseNodeId/children", [
            'query' => [
                'alf_ticket' => $this->tokenStorage->getAuthenticationTicket(),
                'orderBy' => 'cmis:creationDate DESC'
            ],
        ]);
        if ($response->getStatusCode() != "200") {
            return false;
        }
        $responseBody = $response->getBody()->__toString();

        $documentArray = $this->atomHelper->multiEntryFeedToArray(
            $responseBody,
            'cmisra:object',
            $this->caseDocumentProperties,
            null,
            null
        );

        if ($hydrate === false) {
            // @todo refactor this out.
            foreach ($documentArray as $key => $doc) {
                $documentArray[$key]['shortDocumentNodeRef'] = preg_replace('/workspace\:\/\/SpacesStore\//', '', $doc['id']);
            }

            return $documentArray;
        }

        $factory = new CtsCaseDocumentFactory($this->workspace, $this->store);

        $documents = [];
        foreach ($documentArray as $doc) {
            $documents[] = $factory->build($doc);
        }

        return $documents;
    }

    /**
     *
     * @param string $nodeRef
     * @return boolean | CtsCaseDocument
     */
    public function getDocument($nodeRef)
    {
        $response = $this
            ->apiClient
            ->get(
                "s/api/node/$this->workspace/$this->store/$nodeRef",
                array(
                    'query' => array(
                        'alf_ticket' => $this->tokenStorage->getAuthenticationTicket()
                    ),
                )
            );
        if ($response->getStatusCode() != "200") {
            return false;
        }
        $responseBody = $response->getBody()->__toString();
        $document = $this->atomHelper->singleEntryFeedToArray(
            $responseBody,
            'cmisra:object',
            $this->caseDocumentProperties,
            null
        );
        return $this->factory->build($document);
    }

    /**
     * Given a CtsCaseDocument object, this function will download the file to /tmp/{nodeId}
     * and return the CtsCaseDocument object.
     * @param string $nodeRef
     * @return boolean | CtsCaseDocument
     */
    public function getDocumentFile($nodeRef)
    {
        return $this->getDocumentFileByUrl($nodeRef, "");
    }

    /**
     * Given a CtsCaseDocument object, this function will download the file to /tmp/{nodeId}
     * and return the CtsCaseDocument object.
     * This will download a pdf image rendition.
     * @param string $nodeRef
     * @return boolean | CtsCaseDocument
     */
    public function getDocumentPdf($nodeRef)
    {
        return $this->getDocumentFileByUrl($nodeRef, "/thumbnails/ctspreview?ph=true&c=force");
    }
    /**
     * Given a CtsCaseDocument object, this function will download the file to /tmp/{nodeId}
     * and return the CtsCaseDocument object.
     * This will download a jpeg image rendition.
     * @param string $nodeRef
     * @return boolean | CtsCaseDocument
     */
    public function getDocumentImg($nodeRef)
    {
        return $this->getDocumentFileByUrl($nodeRef, "/thumbnails/imgpreview?ph=true&c=force");
    }


    /**
     *
     * @param string $nodeRef
     * @param string $urlEnd
     * @return boolean | CtsCaseDocument
     */
    public function getDocumentFileByUrl($nodeRef, $urlEnd)
    {
        $ctsCaseDocument = $this->getDocument($nodeRef);
        $nodeId = $ctsCaseDocument->getNodeId();
        try {
            $response = $this->apiClient->get("s/api/node/$this->workspace/$this->store/$nodeId/content$urlEnd", [
                'query' => ['alf_ticket' => $this->tokenStorage->getAuthenticationTicket()],
                'save_to' => "/tmp/$nodeId",
            ]);
        } catch (RequestException $exception) {
            $this->logger->addDebug(
                'Exception raised while retrieving document from ' . __METHOD__,
                array(
                    'nodeRef' => $nodeRef,
                    'urlEnd'  => $urlEnd,
                    'message' => $exception->getMessage(),
                    'trace'   => $exception->getTraceAsString()
                )
            );
            return false;
        }
        if ($response->getStatusCode() != '200') {
            $this->logger->addDebug(
                'None 200 Code returned '  . __METHOD__,
                array(
                    $nodeRef,
                    $response->getStatusCode()
                )
            );
            return false;
        }
        return $ctsCaseDocument;
    }

    /**
     *
     * @param string $fileVersionUrl
     * @param string $nodeId
     * @return boolean
     */
    public function getDocumentVersionByUrl($fileVersionUrl, $nodeId)
    {
        try {
            $response = $this->apiClient->get($fileVersionUrl, [
                'query' => ['alf_ticket' => $this->tokenStorage->getAuthenticationTicket()],
                'save_to' => "/tmp/$nodeId",
            ]);
        } catch (RequestException $exception) {
            $this->logger->addDebug(
                'Exception raised while retrieving document ' . __METHOD__,
                array(
                    'nodeId'  => $nodeId,
                    'url'     => $fileVersionUrl,
                    'message' => $exception->getMessage(),
                    'trace'   => $exception->getTraceAsString()
                )
            );
            return false;
        }
        if ($response->getStatusCode() != '200') {
            return false;
        }
        return true;
    }

    /**
     *
     * @param CtsCaseDocument $ctsCaseDocument
     * @param string $caseNodeId
     * @return boolean
     */
    public function uploadNewDocumentVersion($ctsCaseDocument, $caseNodeId)
    {
        $ctsCaseDocument->upload($caseNodeId);

        $documentId = $ctsCaseDocument->getId();
        $body = array(
            'filedata' =>  fopen($ctsCaseDocument->getWebPath($caseNodeId), 'r'),
            'updateNodeRef' => $documentId,
            'majorVersion' => 'true'
        );

        $message = $this->uploadVersion($body);

        return $message;
    }

    public function uploadVersion($body)
    {
        try {
            $response = $this->apiClient->post("s/api/upload", [
                'query' => ['alf_ticket' => $this->tokenStorage->getAuthenticationTicket()],
                'body' => $body
            ]);
        } catch (RequestException $exception) {
            $response = json_decode($exception->getResponse()->getBody()->__toString());
            return $response->message;
        }
        return true;
    }

    /**
     * Permanently deletes a document.
     * @param string $documentId
     * @return boolean
     */
    public function deleteDocument($documentId)
    {
        $ctsCaseDocument = new CtsCaseDocument($this->workspace, $this->store);
        $ctsCaseDocument->setId($documentId);
        $nodeId = $ctsCaseDocument->getNodeId();
        $response = $this->apiClient->delete("s/api/node/$this->workspace/$this->store/$nodeId", [
            'query' => ['alf_ticket' => $this->tokenStorage->getAuthenticationTicket()],
        ]);
        if ($response->getStatusCode() != '204') {
            return false;
        }
        return true;
    }

    /**
     *
     * @param string $documentNodeId
     * @return boolean|array
     */
    public function getDocumentVersions($documentNodeId)
    {
        $response = $this->apiClient->get("s/cmis/i/$documentNodeId/versions", [
            'query' => ['alf_ticket' => $this->tokenStorage->getAuthenticationTicket(),
                'orderBy' => 'cmis:creationDate DESC'
            ]]);

        if ($response->getStatusCode() != "200") {
            return false;
        }

        $responseBody = $response->getBody()->__toString();
        $documents = $this->atomHelper->multiEntryFeedToArray(
            $responseBody,
            'entry',
            $this->caseDocumentProperties,
            null,
            null
        );

        $versionDocuments = array();
        foreach ($documents as $caseDocument) {
            $url = $caseDocument['fileVersionUrl'];
            $shortenedUrl = substr($url, strpos($url, 's/cmis/s')).'/content';
            $caseDocument['fileVersionUrl'] = $shortenedUrl;
            array_push($versionDocuments, $this->factory->build($caseDocument));
        }
        return $versionDocuments;
    }
}

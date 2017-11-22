<?php

namespace HomeOffice\AlfrescoApiBundle\Repository;

use GuzzleHttp\Client as Guzzle;
use HomeOffice\ProcessManagerAuthenticatorBundle\Security\SessionTicketStorage;
use HomeOffice\AlfrescoApiBundle\Service\AtomHelper;
use HomeOffice\AlfrescoApiBundle\Factory\CtsHelpDocumentFactory;

class CtsHelpDocumentRepository
{
 
    /**
     * @var SessionTicketStorage
     */
    private $tokenStorage;

    /**
     * @var Guzzle
     */
    private $apiClient;
 
    /**
     * @var AtomHelper
     */
    private $atomHelper;
 
    /**
     * @var CtsHelpDocumentFactory
     */
    private $ctsHelpDocumentFactory;
 
    /**
     * @var array
     */
    private $helpDocumentProperties;
 
 
    public function __construct(
        Guzzle $apiClient,
        SessionTicketStorage $tokenStorage,
        AtomHelper $atomHelper,
        CtsHelpDocumentFactory $ctsHelpDocumentFactory,
        $helpDocumentProperties
    ) {
        $this->apiClient = $apiClient;
        $this->tokenStorage = $tokenStorage;
        $this->atomHelper = $atomHelper;
        $this->ctsHelpDocumentFactory = $ctsHelpDocumentFactory;
        $this->helpDocumentProperties = $helpDocumentProperties;
    }
    /**
     *
     * @return array
     */
    public function getHelpDocuments()
    {
        $response = $this->apiClient->get("s/cmis/p/CTS/Help/children", [
            'query' => ['alf_ticket' => $this->tokenStorage->getAuthenticationTicket()]
        ]);
     
        if ($response->getStatusCode() != '200') {
            return false;
        }
     
        $responseBody = $response->getBody()->__toString();
     
        $documents = $this->atomHelper->multiEntryFeedToArray(
            $responseBody,
            'cmisra:object',
            $this->helpDocumentProperties,
            null,
            null
        );
     
        $helpDocuments = array();

        foreach ($documents as $document) {
            array_push($helpDocuments, $this->ctsHelpDocumentFactory->build($document));
        }
      
        return $helpDocuments;
    }
}

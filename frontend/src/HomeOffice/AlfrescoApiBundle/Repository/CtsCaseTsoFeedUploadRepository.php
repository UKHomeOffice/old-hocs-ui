<?php

namespace HomeOffice\AlfrescoApiBundle\Repository;

use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Exception\RequestException;
use HomeOffice\ProcessManagerAuthenticatorBundle\Security\SessionTicketStorage;

class CtsCaseTsoFeedUploadRepository
{
 
    /**
     * @var Guzzle
     */
    private $apiClient;
 
     /**
     * @var SessionTicketStorage
     */
    private $tokenStorage;
 
    /**
     *
     * @param \HomeOffice\AlfrescoApiBundle\Repository\Guzzle $apiClient
     * @param \HomeOffice\AlfrescoApiBundle\Repository\SessionTicketStorage $tokenStorage
     */
    public function __construct(
        Guzzle $apiClient,
        SessionTicketStorage $tokenStorage
    ) {
        $this->apiClient = $apiClient;
        $this->tokenStorage = $tokenStorage;
    }
 
    /**
     *
     * @param type $tsoFeed
     * @return $responseBody
     */
    public function upload($tsoFeed)
    {
        $tsoFeed->upload();
        $body = array(
            'file' => fopen($tsoFeed->getWebPath(), 'r'),
        );
        try {
            $response = $this->apiClient->post('service/cts/parsefeed', [
                'query' => ['alf_ticket' => $this->tokenStorage->getAuthenticationTicket()],
                'body' => $body,
            ]);
        } catch (RequestException $exception) {
            $response = $exception->getResponse();
        }
     
        return $response;
     
    }
}

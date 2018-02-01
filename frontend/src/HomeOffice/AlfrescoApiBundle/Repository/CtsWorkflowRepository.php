<?php

namespace HomeOffice\AlfrescoApiBundle\Repository;

use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Exception\RequestException;
use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use HomeOffice\ProcessManagerAuthenticatorBundle\Security\SessionTicketStorage;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Tedivm\StashBundle\Service\CacheService;

class CtsWorkflowRepository
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
     * @var string
     */
    private $qnamePrefix;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Constructor
     *
     * @param Guzzle               $apiClient
     * @param SessionTicketStorage $tokenStorage
     * @param string               $qnamePrefix
     * @param LoggerInterface      $logger
     */
    public function __construct(
        CacheService $cacheService,
        $cacheTimeout,
        Guzzle $apiClient,
        SessionTicketStorage $tokenStorage,
        $qnamePrefix,
        LoggerInterface $logger = null
    ) {
        $this->apiClient = $apiClient;
        $this->tokenStorage = $tokenStorage;
        $this->qnamePrefix = $qnamePrefix;

        $this->logger = $logger ?: new NullLogger();

        $this->cacheService = $cacheService;
        $this->cacheTimeout = $cacheTimeout;
    }
 
    /**
     * @param CtsCase $ctsCase
     * @param string  $transition
     *
     * @return bool
     */
    public function updateWorkflow($ctsCase, $transition)
    {
        $topicKey = "symfonyCase" . $ctsCase->getNodeId();
        $item = $this->cacheService->getItem($topicKey);
        $item->clear();
        try {
            $response = $this->apiClient->post('service/homeoffice/cts/updateWorkflow', [
                'headers' => ['Content-Type' => 'application/atom+xml;type=entry'],
                'query'   => ['alf_ticket' => $this->tokenStorage->getAuthenticationTicket()],
                'body'    => $this->buildWorkflowJson($ctsCase, $transition)
            ]);
        } catch (RequestException $e) {
            $this->logger->error($e->getResponse()->getBody()->getContents());
            throw $e;
        }
     
        return $response->getStatusCode() == '201';
    }
 
    /**
     *
     * @param CtsCase $ctsCase
     * @param string $transition
     * @return string
     */
    private function buildWorkflowJson($ctsCase, $transition)
    {
        $nodeRef = $ctsCase->getId();
        $assignedUnit = $ctsCase->getAssignedUnit();
        $assignedTeam = $ctsCase->getAssignedTeam();
        $assignedUser = $ctsCase->getAssignedUser();

        $bodyEntry = array(
            'nodeRef' => $nodeRef,
            'transition' => $transition,
            'properties' => array(
                array(
                    "$this->qnamePrefix"."assignedUnit" => $assignedUnit,
                    "$this->qnamePrefix"."assignedTeam" => $assignedTeam,
                    "$this->qnamePrefix"."assignedUser" => $assignedUser
                )
            )
        );
             
        $encodedJson = json_encode($bodyEntry);
        return $encodedJson;
    }
}

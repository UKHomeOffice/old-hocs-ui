<?php

namespace HomeOffice\AlfrescoApiBundle\Repository;

use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Message\Response;
use HomeOffice\AlfrescoApiBundle\Entity\CtsCaseMinute;
use HomeOffice\ProcessManagerAuthenticatorBundle\Security\SessionTicketStorage;
use HomeOffice\AlfrescoApiBundle\Service\CTSHelper;
use HomeOffice\AlfrescoApiBundle\Factory\CtsCaseMinuteFactory;
use Monolog\Logger;
use Tedivm\StashBundle\Service\CacheService;

class CtsCaseMinuteRepository
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
     * @var CtsHelper
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

    /** @var  Logger */
    protected $logger;

    protected $cacheService;

    protected $cacheTimeout;
    /**
     *
     * @param \GuzzleHttp\Client $apiClient
     * @param CtsCaseMinuteFactory $ctsCaseFactory
     * @param SessionTicketStorage $tokenStorage
     * @param CTSHelper $ctsHelper
     * @param string $workspace
     * @param string $store
     * @param Logger $logger
     */
    public function __construct(
        CacheService $cacheService,
        $cacheTimeout,
        Guzzle $apiClient,
        CtsCaseMinuteFactory $ctsCaseFactory,
        SessionTicketStorage $tokenStorage,
        CTSHelper $ctsHelper,
        $workspace,
        $store,
        $logger
    ) {
        $this->apiClient = $apiClient;
        $this->factory = $ctsCaseFactory;
        $this->tokenStorage = $tokenStorage;
        $this->ctsHelper = $ctsHelper;
        $this->workspace = $workspace;
        $this->store = $store;
        $this->logger = $logger;

        $this->cacheService = $cacheService;
        $this->cacheTimeout = $cacheTimeout;
    }

    /**
     * @param CtsCaseMinute $newMinute
     * @param string        $caseNodeRef
     * @return bool
     */
    public function create($newMinute, $caseNodeRef)
    {

        $topicKey = "symfonyMins" . $caseNodeRef;
        $item = $this->cacheService->getItem($topicKey);
        $item->clear();

        $body = json_encode(
            array(
                'content'                => array (
                    'content' =>$newMinute->getMinuteContent(),
                    'minuteQaReviewOutcomes' => $newMinute->getMinuteQaReviewOutcomes(),
                    'task'                   => $newMinute->getTask()
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
     *
     * @param string $caseNodeId
     * @param array  $minuteTypes
     * @return array
     */
    public function getMinutesForCase($caseNodeId, array $minuteTypes = array('manual'))
    {
        $topicKey = "symfonyMins" . $caseNodeId;
        return $this->getDocumentsFromCache($topicKey, $caseNodeId, $minuteTypes);}

    public function getDocumentsFromCache($listKey, $caseNodeId, $minuteTypes)
    {
        $cacheItem = $this->cacheService->getItem($listKey);
        $list = $cacheItem->get();
        print "CacheGet" . $listKey;
        if ($cacheItem->isMiss()) {
            print "CacheMiss" . $listKey;
            $this->getMinutesForCaseFromAlfresco($caseNodeId, $minuteTypes, $listKey);
            $list = $cacheItem->get();
        }
        return $list;
    }

    public function getMinutesForCaseFromAlfresco($caseNodeId, array $minuteTypes, $listName)
    {
        /** @var Response $response */
        $response = $this
            ->apiClient
            ->get(
                "s/cts/minutes",
                array (
                    'headers' => array (
                        'Content-Type' => 'application/json'),
                    'query' => array (
                        'alf_ticket' => $this->tokenStorage->getAuthenticationTicket(),
                        'nodeRef' => "workspace://$this->store/$caseNodeId"
                    ),
                )
            );

        if ($response->getStatusCode() != "200") {
            return false;
        }

        $responseBody = json_decode($response->getBody()->__toString());

        $ctsMinuteArray = array();

        foreach ($responseBody->minutes as $minute) {
            $minute->minuteContent = $this->ctsHelper->makeLink($minute->minuteContent);
            array_push($ctsMinuteArray, $this->factory->build($minute));
        }

        $this->storeListInCache($listName, $this->filterMinutesByType($ctsMinuteArray, $minuteTypes));
    }

    /**
     *
     * @param string $name
     * @param array $list
     */
    private function storeListInCache($name, $list)
    {
        $cacheItem = $this->cacheService->getItem($name);
        $cacheItem->set($list, $this->cacheTimeout);
    }

    /**
     * Filters out the returned minute types
     * @param array $ctsMinuteArray
     * @param array $minuteTypes
     * @return array
     */
    protected function filterMinutesByType(array $ctsMinuteArray, array $minuteTypes)
    {
        return array_filter($ctsMinuteArray, function ($minute) use ($minuteTypes) {
            return in_array($minute->getMinuteType(), $minuteTypes);
        });
    }
}

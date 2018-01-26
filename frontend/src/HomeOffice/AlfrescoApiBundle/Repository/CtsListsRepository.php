<?php

namespace HomeOffice\AlfrescoApiBundle\Repository;

use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Exception\RequestException;
use HomeOffice\AlfrescoApiBundle\Entity\Person;
use HomeOffice\AlfrescoApiBundle\Factory\PersonFactory;
use HomeOffice\AlfrescoApiBundle\Factory\UnitFactory;
use HomeOffice\AlfrescoApiBundle\Factory\TeamFactory;
use HomeOffice\ProcessManagerAuthenticatorBundle\Security\SessionTicketStorage;
use Tedivm\StashBundle\Service\CacheService;

class CtsListsRepository
{
 
    /**
     * @var Guzzle
     */
    private $apiClient;
 
   /**
     * @var Guzzle
     */
    private $listService;

    /**
     * @var SessionTicketStorage
     */
    private $tokenStorage;
 
    /**
     *
     * @var PersonFactory
     */
    private $personFactory;
 
    /**
     *
     * @var UnitFactory
     */
    private $unitFactory;
 
    /**
     *
     * @var TeamFactory
     */
    private $teamFactory;


    protected $cacheService;

    protected $cacheTimeout;
 
    /**
     *
     * @param \GuzzleHttp\Client $apiClient
     * @param \GuzzleHttp\Client $listService
     * @param SessionTicketStorage $tokenStorage
     * @param PersonFactory $personFactory
     * @param UnitFactory $unitFactory
     * @param TeamFactory $teamFactory
     */
    public function __construct(
        CacheService $cacheService,
        $cacheTimeout,
        Guzzle $apiClient,
        Guzzle $listService,
        SessionTicketStorage $tokenStorage,
        PersonFactory $personFactory,
        UnitFactory $unitFactory,
        TeamFactory $teamFactory
    ) {
        $this->apiClient = $apiClient;
        $this->apiClient->setDefaultOption('verify', true);
        $this->listService = $listService;
        $this->tokenStorage = $tokenStorage;
        $this->personFactory = $personFactory;
        $this->unitFactory = $unitFactory;
        $this->teamFactory = $teamFactory;

        $this->cacheService = $cacheService;
        $this->cacheTimeout = $cacheTimeout;
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
     *
     * @return array
     */
    public function getUnitsAndTeams()
    {
        $topicKey = "symfonyUnitsAndTeams";
        return $this->getUnitsAndTeamsFromCache($topicKey);
    }

    private function getUnitsAndTeamsFromCache($listKey)
    {
        $cacheItem = $this->cacheService->getItem($listKey);
        $list = $cacheItem->get();
        if ($cacheItem->isMiss()) {
            $this->getUnitsAndTeamsFromAlfresco($listKey);
            $list = $cacheItem->get();
        }
        return $list;
    }

    private function getUnitsAndTeamsFromAlfresco($listName)
    {
        try {
            $response = $this->apiClient->get('s/homeoffice/cts/allTeams', [
                'query' => ['alf_ticket' => $this->tokenStorage->getAuthenticationTicket()],
            ]);
        } catch (RequestException $exception) {
            $response = $exception->getResponse();
        }

        $responseBody = json_decode($response->getBody()->__toString());

        $ctsUnitAndTeamArray = array();
        foreach ($responseBody->units as $unit) {
            $teamArray = array();
            foreach ($unit->teams as $team) {
                $team = $this->teamFactory->build((array) $team);
                array_push($teamArray, $team);
            }
            $unit = $this->unitFactory->build((array) $unit);
            $unit->setTeams($teamArray);
            array_push($ctsUnitAndTeamArray, $unit);
        }
        $this->storeListInCache($listName, $ctsUnitAndTeamArray);
    }
 
    /**
     *
     * @return array
     */
    public function getPeopleInUsersTeams()
    {
        $topicKey = "symfonyPeopleInUsersTeams";
        return $this->getPeopleTeamsFromCache($topicKey);
    }

    private function getPeopleTeamsFromCache($listKey)
    {
        $cacheItem = $this->cacheService->getItem($listKey);
        $list = $cacheItem->get();
        if ($cacheItem->isMiss()) {
            $this->getPeopleInUsersTeamsFromAlfresco($listKey);
            $list = $cacheItem->get();
        }
        return $list;
    }

    private function getPeopleInUsersTeamsFromAlfresco($listName)
    {
        try {
            $response = $this->apiClient->get('service/homeoffice/cts/peopleInUsersTeams', [
                'query' => [
                    'alf_ticket' => $this->tokenStorage->getAuthenticationTicket(),
//                    'user' => $this->getUser()->getUsername()
                ],
            ]);
        } catch (RequestException $exception) {
            $response = $exception->getResponse();
        }

        $responseBody = json_decode($response->getBody()->__toString());
        $people = array();
        foreach ((array)$responseBody->users as $value) {
            $people[] = $this->personFactory->build($value);
        }

        $this->storeListInCache($listName, $people);
    }
 
    /**
     * @param string $group
     *
     * @return Person[]
     */
    public function getPeopleFromGroup($group)
    {
        $topicKey = "symfonyPeopleFromGroup" . $group;
        return $this->getPeopleFromCache($topicKey, $group);
    }

    private function getPeopleFromCache($listKey, $group)
    {
        $cacheItem = $this->cacheService->getItem($listKey);
        $list = $cacheItem->get();
        if ($cacheItem->isMiss()) {
            $this->getPeopleFromGroupFromAlfresco($group, $listKey);
            $list = $cacheItem->get();
        }
        return $list;
    }

    private function getPeopleFromGroupFromAlfresco($group, $listName)
    {
        try {
            $response = $this->apiClient->get('s/homeoffice/cts/teamUsers', [
                'query' => [
                    'alf_ticket' => $this->tokenStorage->getAuthenticationTicket(),
                    'group' => $group
                ],
            ]);
        } catch (RequestException $exception) {
            $response = $exception->getResponse();
        }

        $responseBody = json_decode($response->getBody()->__toString());
        $people = [];
        if (isset($responseBody->users)) {
            foreach ((array) $responseBody->users as $value) {
                $people[] = $this->personFactory->build($value);
            }
        }

        $this->storeListInCache($listName, $people);
    }
 
    /**
     *
     * @param string $fileName
     * @return string
     */
    public function getCsvList($fileName)
    {
        $response = $this->apiClient->get("s/cmis/p/CTS/Files/$fileName/content", [
            'query' => ['alf_ticket' => $this->tokenStorage->getAuthenticationTicket()],
        ]);
     
        if ($response->getStatusCode() != '200') {
            return false;
        }
     
        $responseBody = $response->getBody()->__toString();
     
        return $responseBody;
    }
 
    /**
     *
     * @param string $listName
     * @return string
     */
    public function getDataList($listName)
    {
        $topicKey = "symfonyDataLIST" . $listName;
        return $this->getDataListFromCache($topicKey, $listName);
    }

    private function getDataListFromCache($listKey, $listName)
    {
        $cacheItem = $this->cacheService->getItem($listKey);
        $list = $cacheItem->get();
        if ($cacheItem->isMiss()) {
            $this->getDataListFromAlfresco($listName, $listKey);
            $list = $cacheItem->get();
        }
        return $list;
    }

    private function getDataListFromAlfresco($listName, $keyname)
    {
        $response = $this->listService->get("list/$listName", []);

        if ($response->getStatusCode() != '200') {
            return false;
        }

        $responseBody = $response->getBody()->__toString();

        $this->storeListInCache($keyname, $responseBody);
    }

}

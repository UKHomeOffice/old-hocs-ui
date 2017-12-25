<?php

namespace HomeOffice\AlfrescoApiBundle\Repository;

use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Exception\RequestException;
use HomeOffice\AlfrescoApiBundle\Entity\Person;
use HomeOffice\AlfrescoApiBundle\Factory\PersonFactory;
use HomeOffice\AlfrescoApiBundle\Factory\UnitFactory;
use HomeOffice\AlfrescoApiBundle\Factory\TeamFactory;
use HomeOffice\ProcessManagerAuthenticatorBundle\Security\SessionTicketStorage;

class CtsListsRepository
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
 
    /**
     *
     * @param \GuzzleHttp\Client $apiClient
     * @param SessionTicketStorage $tokenStorage
     * @param PersonFactory $personFactory
     * @param UnitFactory $unitFactory
     * @param TeamFactory $teamFactory
     */
    public function __construct(
        Guzzle $apiClient,
        SessionTicketStorage $tokenStorage,
        PersonFactory $personFactory,
        UnitFactory $unitFactory,
        TeamFactory $teamFactory
    ) {
        $this->apiClient = $apiClient;
        $this->tokenStorage = $tokenStorage;
        $this->personFactory = $personFactory;
        $this->unitFactory = $unitFactory;
        $this->teamFactory = $teamFactory;
    }
 
    /**
     * Implemented in hocs-data-service - NM
     * @return array
     */
    public function getUnitsAndTeams()
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
        return $ctsUnitAndTeamArray;
    }
 
    /**
     *
     * @return array
     */
    public function getPeopleInUsersTeams()
    {
        try {
            $response = $this->apiClient->get('service/homeoffice/cts/peopleInUsersTeams', [
                'query' => [
                    'alf_ticket' => $this->tokenStorage->getAuthenticationTicket(),
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
     
        return $people;
    }
 
    /**
     * Implemented in hocs-data-service - NM
     * @param string $group
     *
     * @return Person[]
     */
    public function getPeopleFromGroup($group)
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
     
        return $people;
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
        $response = $this->apiClient->get("s/homeoffice/cts/$listName", [
            'query' => [
                'alf_ticket' => $this->tokenStorage->getAuthenticationTicket(),
                'listName' => $listName
            ],
        ]);
     
        if ($response->getStatusCode() != '200') {
            return false;
        }
     
        $responseBody = $response->getBody()->__toString();
     
        return $responseBody;
    }
}

<?php

namespace HomeOffice\AlfrescoApiBundle\Repository;

use GuzzleHttp\Client as Guzzle;
use HomeOffice\AlfrescoApiBundle\Entity\Team;
use HomeOffice\AlfrescoApiBundle\Entity\Unit;
use HomeOffice\AlfrescoApiBundle\Factory\PersonFactory;
use HomeOffice\AlfrescoApiBundle\Factory\UnitFactory;
use HomeOffice\AlfrescoApiBundle\Factory\TeamFactory;
use HomeOffice\AlfrescoApiBundle\Factory\PermissionsFactory;
use HomeOffice\ListBundle\Service\ListService;
use HomeOffice\ProcessManagerAuthenticatorBundle\Security\SessionTicketStorage;
use GuzzleHttp\Exception\RequestException;

class PersonRepository
{
    private $excludedTeams = [
      'GROUP_Units', 'GROUP_ALFRESCO_ADMINISTRATORS', 'GROUP_Manager', 'GROUP_EMAIL_CONTRIBUTORS',
    ];

    /**
     * @var \GuzzleHttp\Client
     */
    private $apiClient;

    /**
     * @var PersonFactory
     */
    private $personFactory;

    /**
     * @var UnitFactory
     */
    private $unitFactory;

    /**
     * @var TeamFactory
     */
    private $teamFactory;
 
    /**
     * @var PermissionsFactory
     */
    private $permissionsFactory;
 
    /**
     * @var SessionTicketStorage
     */
    private $tokenStorage;

    /**
     * @var ListService
     */
    private $listService;

    /**
     *
     * @param \GuzzleHttp\Client $apiClient
     * @param PersonFactory $personFactory
     * @param UnitFactory $unitFactory
     * @param TeamFactory $teamFactory
     * @param PermissionsFactory $permissionsFactory
     * @param SessionTicketStorage $tokenStorage
     * @param ListService $listService
     */
    public function __construct(
        Guzzle $apiClient,
        PersonFactory $personFactory,
        UnitFactory $unitFactory,
        TeamFactory $teamFactory,
        PermissionsFactory $permissionsFactory,
        SessionTicketStorage $tokenStorage,
        ListService $listService
    ) {
        $this->apiClient = $apiClient;
        $this->personFactory = $personFactory;
        $this->unitFactory = $unitFactory;
        $this->teamFactory = $teamFactory;
        $this->permissionsFactory = $permissionsFactory;
        $this->tokenStorage = $tokenStorage;
        $this->listService = $listService;
    }

    /**
     * @param $username
     * @return boolean | \HomeOffice\AlfrescoApiBundle\Entity\Person
     */
    public function getUserDetails($username)
    {
        try {
            $response = $this->apiClient->get("s/homeoffice/cts/user/$username", [
                'query' => ['alf_ticket' => $this->tokenStorage->getAuthenticationTicket()]
            ]);
        } catch (RequestException $exception) {
            return false;
        }
     
        if ($response->getStatusCode() != '200') {
            return false;
        }
     
        $responseBody = json_decode($response->getBody()->__toString());
        $user = $this->personFactory->build((array) $responseBody);

        /** @var Unit[] $units */
        $units = array();
        /** @var Team[] $teams */
        $teams = array();

        foreach ($responseBody->groups as $group) {
            if ($group->isUnit ) {
                array_push($units, $this->unitFactory->build((array) $group));
            }
            if ($group->isTeam && !in_array($group->authorityName, $this->excludedTeams)) {
                array_push($teams, $this->teamFactory->build((array) $group));
            }
        }
        $user->setUnits($units);
        $user->setTeams($teams);

        if ($responseBody->manager != null) {
            $user->setManager($responseBody->manager);
        }
     
        $casesPermissions = $this->permissionsFactory->build(
            (array) $responseBody->casesPermissions,
            "HomeOffice\AlfrescoApiBundle\Entity\CasesPermissions"
        );
        $documentTemplatesPermissions = $this->permissionsFactory->build(
            (array) $responseBody->documentTemplatesPermissions,
            "HomeOffice\AlfrescoApiBundle\Entity\DocumentTemplatesPermissions"
        );
        $standardLinesPermissions = $this->permissionsFactory->build(
            (array) $responseBody->standardLinesPermissions,
            "HomeOffice\AlfrescoApiBundle\Entity\StandardLinesPermissions"
        );
        $autoCreatePermissions = $this->permissionsFactory->build(
            (array) $responseBody->autoCreatePermissions,
            "HomeOffice\AlfrescoApiBundle\Entity\AutoCreatePermissions"
        );
        $user->setCasesPermissions($casesPermissions);
        $user->setDocumentTemplatesPermissions($documentTemplatesPermissions);
        $user->setStandardLinesPermissions($standardLinesPermissions);
        $user->setAutoCreatePermissions($autoCreatePermissions);
        return $user;
    }
 
    /**
     * @return array[\HomeOffice\AlfrescoApiBundle\Entity\Person]
     */
    public function findAllUsers()
    {
        $response = $this->apiClient->get("s/api/people", [
            'query' => ['alf_ticket' => $this->tokenStorage->getAuthenticationTicket()],
        ]);
     
        if ($response->getStatusCode() != '200') {
            return false;
        }
     
        $responseBody = json_decode($response->getBody()->__toString());
     
        $people = array();
        foreach ((array)$responseBody->people as $value) {
            $people[] = $this->personFactory->build($value);
        }
     
        return $people;
    }
 
    /**
     * Delete the alfresco ticket.
     */
    public function signoutUser()
    {
        $ticket = $this->tokenStorage->getAuthenticationTicket();
        try {
            $response = $this->apiClient->delete("service/api/login/ticket/$ticket", [
                'query' => ['alf_ticket' => $ticket]
            ]);
        } catch (RequestException $exception) {
            $response = $exception->getResponse();
        }
    }
 
    /**
     * Request to reset the password for a specified user.
     * @param string $username
     * @param string $oldPassword
     * @param string $newPassword
     * @return boolean
     */
    public function resetPassword($username, $oldPassword, $newPassword)
    {
        $bodyObject = new \StdClass();
        $bodyObject->oldpw = $oldPassword;
        $bodyObject->newpw = $newPassword;
        $body = json_encode($bodyObject);
        $ticket = $this->tokenStorage->getAuthenticationTicket();
        try {
            // no authentication required here, but alfresco will try to authenticate
            // using the username and current password before resetting it.
            $response = $this->apiClient->post("s/homeoffice/cts/util/changePassword/$username", [
                'query' => ['alf_ticket' => $ticket],
                'headers' => ['Content-Type' => 'application/json'],
                'body' => $body
            ]);
        } catch (RequestException $exception) {
            $response = json_decode($exception->getResponse()->getBody()->__toString());
            return array($exception->getResponse()->getStatusCode(), $response->message);
        }
        if ($response->getStatusCode() != '200') {
             array(-1, "Reset password failed.");
        }
        return true;
    }
}

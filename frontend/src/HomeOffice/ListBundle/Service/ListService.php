<?php

namespace HomeOffice\ListBundle\Service;

use HomeOffice\AlfrescoApiBundle\Entity\Member;
use HomeOffice\AlfrescoApiBundle\Entity\Team;
use HomeOffice\AlfrescoApiBundle\Entity\Unit;
use HomeOffice\AlfrescoApiBundle\Repository\CtsListsRepository as ListRepository;
use HomeOffice\AlfrescoApiBundle\Service\CTSHelper;

/**
 * Class ListService
 *
 * @package HomeOffice\ListBundle\Service
 */
class ListService
{
    /**
     * @var ListHandler
     */
    protected $listHandler;

    /**
     * @var ListRepository
     */
    protected $listRepository;

    /**
     * Constructor
     *
     * @param ListHandler $listHandler
     * @param ListRepository $listRepository
     */
    public function __construct(ListHandler $listHandler, ListRepository $listRepository)
    {
        $this->listHandler = $listHandler;
        $this->listRepository = $listRepository;
    }

    /**
     * Get ListHandler
     *
     * @return ListHandler
     */
    public function getListHandler()
    {
        return $this->listHandler;
    }

    /**
     * Get an array of units indexed by unit authority name
     *
     * @return array
     */
    public function getUnitArray()
    {
        $units = [];

        try {
            /** @var Unit $unit */
            foreach ($this->listHandler->getList('ctsUnitAndTeamList') as $unit) {
                $units[$unit->getAuthorityName()] = $unit->getDisplayName();
            }
        } catch (\Exception $e) { }

        return $units;
    }

    /**
     * @param string|null $unitName
     *
     * @return Team[]
     */
    public function getTeamsForUnit($unitName = null)
    {
        $teams = [];

        try {
            /** @var Unit $unit */
            foreach ($this->listHandler->getList('ctsUnitAndTeamList') as $unit) {
                if (is_null($unitName) || $unit->getAuthorityName() === $unitName) {
                    foreach ($unit->getTeams() as $team) {
                        $teams[] = $team;
                    }
                }
            }
        } catch (\Exception $e) { }

        return $teams;
    }

    /**
     * Get an array of teams for a unit indexed by team authority name
     *
     * @param string|null $unitName
     *
     * @return array
     */
    public function getTeamArrayForUnit($unitName = null)
    {
        $teams = [];

        foreach ($this->getTeamsForUnit($unitName) as $team) {
            $teams[$team->getAuthorityName()] = $team->getDisplayName();
        }

        return $teams;
    }



    /**
     * Get an array of users for a team or unit indexed by user name
     *
     * @param string|null $unit
     * @param string|null $team
     *
     * @return array
     */
    public function getUserArrayForTeamOrUnit($team, $unit)
    {
        $people = [];

        foreach ($this->listRepository->getPeopleFromGroup($team ?: $unit) as $person) {
            $people[$person->getUserName()] = $person->getFullName();
        }

        return $people;
    }

    /**
     * Get an array of members for a team or unit indexed by display name
     *
     * @param bool $includeLords
     *
     * @return array
     */
    public function getMemberArray($includeLords = true)
    {
        $members = [];

        try {
            /** @var Member $member */
            foreach ($this->listHandler->getList('ctsMemberList') as $member) {
                if ($includeLords === false && $member->getIsLords() === true) {
                    continue;
                }
                $members[$member->getDisplayName()] = $member->getDisplayName();
            }
        } catch (\Exception $e) {}

        return $members;
    }

    /**
     * Get an array of ministers indexed by group name
     *
     * @return array
     */
    public function getMinisterArray()
    {
        $ministers = [];
        try {
            $ministers = $this->listHandler->getList('ctsMinisterList');
        } catch (\Exception $e) {}

        return $ministers;
    }

    /**
     * Get an array of topics indexed by topic name
     *
     * @return array
     */
    public function getTopicsArray()
    {
        return array_reduce($this->listHandler->getList('ctsTopicList'), 'array_merge', []);
    }
}

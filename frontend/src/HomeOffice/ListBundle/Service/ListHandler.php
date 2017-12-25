<?php

namespace HomeOffice\ListBundle\Service;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use HomeOffice\AlfrescoApiBundle\Entity\Person;
use HomeOffice\AlfrescoApiBundle\Entity\Team;
use HomeOffice\AlfrescoApiBundle\Entity\Unit;
use HomeOffice\AlfrescoApiBundle\Entity\Member;
use HomeOffice\AlfrescoApiBundle\Repository\CtsListsRepository;
use Symfony\Component\HttpFoundation\Session\Session;
use Tedivm\StashBundle\Service\CacheService;

class ListHandler
{

    /**
     *
     * @var Session
     */
    private $session;
 
    /**
     * @var CacheService
     */
    private $cachePool;
 
    /**
     *
     * @var CtsListsRepository
     */
    private $ctsListsRepository;
 
    /**
     * @var CSVParser
     */
    private $csvParser;
 
    /**
     * @var array
     */
    private $listDefinitions;

    /**
     *
     * @param Session $session
     * @param CacheService $cachePool
     * @param $cacheTimeout
     * @param CtsListsRepository $ctsListsRepository
     * @param CSVParser $csvParser
     * @param array $listDefinitions
     */
    public function __construct(
        Session $session,
        CacheService $cachePool,
        $cacheTimeout,
        CtsListsRepository $ctsListsRepository,
        CSVParser $csvParser,
        $listDefinitions
    ) {
        $this->session = $session;
        $this->cachePool = $cachePool;
        $this->cacheTimeout = $cacheTimeout;
        $this->ctsListsRepository = $ctsListsRepository;
        $this->csvParser = $csvParser;
        $this->listDefinitions = $listDefinitions;
    }

    /**
     * @param string $listName
     *
     * @return array
     *
     * @throws \Exception
     */
    public function getList($listName)
    {
        if (!array_key_exists($listName, $this->listDefinitions)) {
            throw new \Exception("List not defined: $listName");
        }

        switch ($this->listDefinitions[$listName]['storage']) {
            case 'session':
                $list = $this->getListFromSession($listName);
                break;
            case 'cache':
                $list = $this->getListFromCache($listName);
                break;
            default:
                throw new \Exception("Storage not defined for list: $listName");
        }

        return $list;
    }
 
    /**
     * Load the lists when the user first logs in
     */
    public function loadListsOnLogin()
    {
        $this->getList('ctsMemberList');
    }
 
    /**
     *
     * @param string $listName
     * @throws \Exception
     */
    public function initialiseList($listName)
    {
        if (!array_key_exists($listName, $this->listDefinitions)) {
            throw new \Exception("List not defined: $listName");
        }
        $fileName = null;
        $type = null;
        $retrieveMethod = '';
        $prepareMethod = null;
        if (array_key_exists('file_name', $this->listDefinitions[$listName])) {
            $fileName = $this->listDefinitions[$listName]['file_name'];
        }
        if (array_key_exists('type', $this->listDefinitions[$listName])) {
            $type = $this->listDefinitions[$listName]['type'];
        }
        if (array_key_exists('retrieve_method_name', $this->listDefinitions[$listName])) {
            $retrieveMethod = $this->listDefinitions[$listName]['retrieve_method_name'];
        } else {
            if ($type == 'csv') {
                $retrieveMethod = 'getCsvList';
            }
            if ($type == 'dataList') {
                $retrieveMethod = 'getDataList';
            }
        }
        if (array_key_exists('prepare_method_name', $this->listDefinitions[$listName])) {
            $prepareMethod = $this->listDefinitions[$listName]['prepare_method_name'];
        }
        $storage = $this->listDefinitions[$listName]['storage'];
        if ($retrieveMethod == 'getCsvList') {
            // store the file response in the session as some files are used for multiple lists
            // this prevent multiple calls when they are not necessary
            $listString = $this->session->get($fileName);
            if ($listString == null) {
                $listString = $this->ctsListsRepository->getCsvList($fileName);
                $this->session->set($fileName, $listString);
            }
        } elseif ($retrieveMethod == 'getDataList') {
            // store the response in the session as some files are used for multiple lists
            // this prevent multiple calls when they are not necessary
            $alfrescoListName = $this->listDefinitions[$listName]['alfresco_list_name'];
            $listString = $this->session->get($alfrescoListName);
            if ($listString == null) {
                $listString = $this->ctsListsRepository->getDataList($alfrescoListName);
                $this->session->set($alfrescoListName, $listString);
            }
        } else {
            $listString = $this->ctsListsRepository->$retrieveMethod();
        }
        if ($prepareMethod != null) {
            $preparedList = $this->$prepareMethod($listString);
        } else {
            $preparedList = $listString;
        }
     
        switch ($storage) {
            case 'session':
                $this->storeListInSession($listName, $preparedList);
                break;
            case 'cache':
                $this->storeListInCache($listName, $preparedList);
                break;
            default:
                throw new \Exception("Storage type not defined for list: $listName");
        }
    }


    /**
     *
     * @param array $ctsCorrespondentList
     * @return array
     */
    private function prepareMemberList($ctsCorrespondentList)
    {
        $members = json_decode($ctsCorrespondentList);
        $membersList = array();

        foreach ($members->entities as $entity) {

            $membersList[$entity->value] = new Member($entity);

        }


        return $membersList;
    }
 
    /**
     *
     * @param array $ctsMinisterList
     * @return array
     */
    private function prepareMinisterList($ctsMinisterList)
    {
        $ministers = json_decode($ctsMinisterList);
        $ministerList = array();

        foreach($ministers->entities as $entity) {

            $ministerList[$entity->value] = $entity->text;

        }

        return $ministerList;
    }
 
    //TODO - create a minister list with the ids
 
    /**
     *
     * @param object $ctsUnitList
     * @return array
     */
    private function prepareUnitList($ctsUnitList)
    {
        $ctsUnitArray = array();
        foreach ($ctsUnitList as $unit) {
            $ctsUnitArray[$unit->getAuthorityName()] = $unit->getDisplayName();
        }
        return $ctsUnitArray;
    }

    /**
     *
     * @param string $name
     * @param array $list
     */
    private function storeListInSession($name, $list)
    {
        $this->session->set($name, $list);
    }
 
    /**
     *
     * @param string $name
     * @param array $list
     */
    private function storeListInCache($name, $list)
    {
        $cacheItem = $this->cachePool->getItem($name);
        $cacheItem->set($list, $this->cacheTimeout);
    }
 
    /**
     *
     * @param string $listName
     * @return array
     */
    private function getListFromSession($listName)
    {
        $list = $this->session->get($listName);
        if ($list == null) {
            $this->initialiseList($listName);
            $list = $this->session->get($listName);
        }
        return $list;
    }
 
    /**
     *
     * @param string $listName
     * @return array
     */
    private function getListFromCache($listName)
    {
        $cacheItem = $this->cachePool->getItem($listName);
        $list = $cacheItem->get();
        if ($cacheItem->isMiss()) {
            $this->initialiseList($listName);
            $list = $cacheItem->get();
        }
        return $list;
    }

    /**
     * @return Unit[]
     *
     * @throws \Exception
     */
    public function getAllUnits()
    {
        return $this->getList('ctsUnitAndTeamList');
    }

    /**
     * @param string $unitToCheck
     *
     * @return Team[]
     */
    public function getTeamsFromUnit($unitToCheck)
    {
        /** @var Unit $unit */
        foreach ($this->getList('ctsUnitAndTeamList') as $unit) {
            if ($unitToCheck == $unit->getAuthorityName()) {
                return $unit->getTeams();
            }
        }

        return [];
    }
 
    /**
     * @param Team $teamToCheck
     *
     * @return Unit|null
     */
    public function getUnitFromTeam($teamToCheck)
    {
        /** @var Unit $unit */
        foreach ($this->getList('ctsUnitAndTeamList') as $unit) {
            foreach ($unit->getTeams() as $team) {
                if ($teamToCheck->getAuthorityName() == $team->getAuthorityName()) {
                    return $unit;
                }
            }
        }

        return null;
    }

    /**
     * Get a ministers name from the array index
     *
     * @param string $index
     *
     * @return string|null
     */
    public function getMinisterName($index)
    {
        foreach ($this->getList('ctsMinisterList') as $key => $minister) {
            if ($key === $index) {
                return $minister;
            }
        }

        return null;
    }
 
    /**
     * @return Person[]|null
     */
    public function getPeopleFromUnitOrTeam()
    {
        $ctsPeopleListForGroup = null;
        $group = $this->session->get('groupForPersonQuery');
        if ($group != null) {
            $ctsPeopleListForGroup = $this->ctsListsRepository->getPeopleFromGroup($group);
        }
        return $ctsPeopleListForGroup;
    }
 
    /**
     * Used to work out which group to use in the person query. A group can be either a Unit
     * or a Team. If only a unit is selected (with no teams available) then it should select
     * use the unit as the group. If a team is selected, along with the unit then it should
     * select this team as the group.
     * @param CtsCase $ctsCase
     */
    public function extractSelectedGroupForPersonQuery($ctsCase)
    {
        $assignedUnit = $ctsCase->getAssignedUnit();
        $assignedTeam = $ctsCase->getAssignedTeam();
        $this->session->set('groupForPersonQuery', null);

        if ($assignedTeam != null && $assignedUnit != null) {
            // if both unit and team are set, then we need to get users in the team
            $this->session->set('groupForPersonQuery', $assignedTeam);
        } elseif ($assignedUnit != null) {
            // else if the unit is set, then we need to get users in the unit
            $this->session->set('groupForPersonQuery', $assignedUnit);
        }
    }
}

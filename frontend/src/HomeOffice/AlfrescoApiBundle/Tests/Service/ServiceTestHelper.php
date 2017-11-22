<?php

namespace HomeOffice\AlfrescoApiBundle\Tests\Service;

use HomeOffice\AlfrescoApiBundle\Entity\Unit;
use HomeOffice\AlfrescoApiBundle\Entity\Team;

class ServiceTestHelper extends \PHPUnit_Framework_TestCase
{
    public function createUnitWithTeam()
    {
        return array($this->createUnit('UNIT_NAME', 'Unit name', true));
    }
 
    public function createUnit($authorityName = 'UNIT_NAME', $displayName = 'Unit name', $createTeam = false)
    {
        $unit = new Unit();
        $unit->setAuthorityName($authorityName);
        $unit->setDisplayName($displayName);
        if ($createTeam) {
            $unit->setTeams(array($this->createTeam()));
        }
        return $unit;
    }
 
    public function createTeam($authorityName = 'TEAM_NAME', $displayName = 'Team name')
    {
        $team = new Team();
        $team->setAuthorityName($authorityName);
        $team->setDisplayName($displayName);
        return $team;
    }
}

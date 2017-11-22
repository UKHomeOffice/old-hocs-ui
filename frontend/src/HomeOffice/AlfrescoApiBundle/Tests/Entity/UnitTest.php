<?php

namespace HomeOffice\AlfrescoApiBundle\Tests\Entity;

use HomeOffice\AlfrescoApiBundle\Entity\Unit;
use HomeOffice\AlfrescoApiBundle\Entity\Team;

class UnitTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Unit
     */
    private $instance;

    protected function setUp()
    {
        $this->instance = new Unit();
    }
 
    private function createTeam($authorityName, $displayName)
    {
        $team = new Team();
        $team->setAuthorityName($authorityName);
        $team->setDisplayName($displayName);
        return $team;
    }

    public function testGetSetAuthorityName()
    {
        $this->instance->setAuthorityName("TESTING");
        $this->assertEquals("TESTING", $this->instance->getAuthorityName());
    }

    public function testGetSetDisplayName()
    {
        $this->instance->setDisplayName("Testing");
        $this->assertEquals("Testing", $this->instance->getDisplayName());
    }
 
    public function testGetSetTeams()
    {
        $team = $this->createTeam('TEAM', 'Team');
        $this->instance->setTeams(array($team));
        $this->assertEquals(array($team), $this->instance->getTeams());
    }
}

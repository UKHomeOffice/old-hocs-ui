<?php

namespace HomeOffice\AlfrescoApiBundle\Tests\Entity;

use HomeOffice\AlfrescoApiBundle\Entity\Person;
use HomeOffice\AlfrescoApiBundle\Entity\Unit;
use HomeOffice\AlfrescoApiBundle\Entity\Team;

class PersonTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Person
     */
    private $instance;
 
    private function createUnit($authorityName, $displayName)
    {
        $unit = new Unit();
        $unit->setAuthorityName($authorityName);
        $unit->setDisplayName($displayName);
        return $unit;
    }
 
    private function createTeam($authorityName, $displayName)
    {
        $team = new Team();
        $team->setAuthorityName($authorityName);
        $team->setDisplayName($displayName);
        return $team;
    }

    protected function setUp()
    {
        $this->instance = new Person();
    }
 
    public function testGetSetEmail()
    {
        $this->instance->setEmail("Testing");
        $this->assertEquals("Testing", $this->instance->getEmail());
    }
 
    public function testGetSetFirstName()
    {
        $this->instance->setFirstName("Testing");
        $this->assertEquals("Testing", $this->instance->getFirstName());
    }
 
    public function testGetSetLastName()
    {
        $this->instance->setLastName("Testing");
        $this->assertEquals("Testing", $this->instance->getLastName());
    }
 
    public function testGetSetUserName()
    {
        $this->instance->setUserName("Testing");
        $this->assertEquals("Testing", $this->instance->getUserName());
    }
 
    public function testGetPasswordExpiryDate()
    {
        $this->instance->setPasswordExpiryDate("Testing");
        $this->assertEquals("Testing", $this->instance->getPasswordExpiryDate());
    }

    public function testGetRoles()
    {
        $this->assertEquals(array('ROLE_USER'), $this->instance->getRoles());
    }
 
    public function testGetPassword()
    {
        $this->assertEquals('', $this->instance->getPassword());
    }
 
    public function testGetSalt()
    {
        $this->assertEquals('', $this->instance->getSalt());
    }
 
    public function testGetFullName()
    {
        $this->assertEquals('', $this->instance->getFullName());
     
        $this->instance->setFirstName('Fred');
        $this->instance->setLastName('');
        $this->assertEquals('Fred', $this->instance->getFullName());
     
        $this->instance->setFirstName('');
        $this->instance->setLastName('Flintstone');
        $this->assertEquals('Flintstone', $this->instance->getFullName());
     
        $this->instance->setFirstName('Fred');
        $this->instance->setLastName('Flintstone');
        $this->assertEquals('Fred Flintstone', $this->instance->getFullName());
    }
 
    public function testEraseCredentials()
    {
        // Just don't throw an exception
        // Do nothing
        $this->assertEquals(null, $this->instance->eraseCredentials());
    }
 
    public function testGetSetUnits()
    {
        $unit = $this->createUnit('UNIT', 'Unit');
        $this->instance->setUnits(array($unit));
        $this->assertEquals(array($unit), $this->instance->getUnits());
    }
 
    public function testGetSetTeams()
    {
        $teams = [$this->createTeam('TEAM', 'Team')];
        $units = [$this->createUnit('UNIT', 'Unit')->setTeams($teams)];
        $this->instance->setUnits($units);
        $this->instance->setTeams($teams);
        $this->assertEquals($teams, $this->instance->getTeams());
    }
 
    public function testGetSetIsManager()
    {
        $this->instance->setManager('true');
        $this->assertTrue($this->instance->isManager());
    }
}

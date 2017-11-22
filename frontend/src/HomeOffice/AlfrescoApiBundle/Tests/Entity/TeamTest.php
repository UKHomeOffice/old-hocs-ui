<?php

namespace HomeOffice\AlfrescoApiBundle\Tests\Entity;

use HomeOffice\AlfrescoApiBundle\Entity\Team;
use HomeOffice\AlfrescoApiBundle\Entity\Person;

class TeamTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Team
     */
    private $instance;

    protected function setUp()
    {
        $this->instance = new Team();
    }
 
    private function createPerson()
    {
        return new Person();
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
        $people = array(new Person());
        $this->instance->setPeople($people);
        $this->assertEquals($people, $this->instance->getPeople());
    }
}

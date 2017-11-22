<?php

namespace HomeOffice\AlfrescoApiBundle\Tests\Factory;

use HomeOffice\AlfrescoApiBundle\Factory\TeamFactory;

class TeamFactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var TeamFactory
     */
    private $instance;

    protected function setUp()
    {
        $this->instance = new TeamFactory();
    }

    public function testBuildReturnsATeam()
    {
        $actual = $this->instance->build(array());
        $this->assertInstanceOf("HomeOffice\AlfrescoApiBundle\Entity\Team", $actual);
    }

    public function testBuildSetsValidParameters()
    {
        $actual = $this->instance->build(array('authorityName' => "Testing"));
        $this->assertEquals("Testing", $actual->getAuthorityName());
    }

    public function testBuildIgnoresNoneExistentParameters()
    {
        $actual = $this->instance->build(array('authorityName' => "Testing", 'fake fake fake' => "Testing"));
        $this->assertEquals("Testing", $actual->getAuthorityName());
    }
}

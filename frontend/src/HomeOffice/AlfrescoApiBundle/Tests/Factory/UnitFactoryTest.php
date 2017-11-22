<?php

namespace HomeOffice\AlfrescoApiBundle\Tests\Factory;

use HomeOffice\AlfrescoApiBundle\Factory\UnitFactory;

class UnitFactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var UnitFactory
     */
    private $instance;

    protected function setUp()
    {
        $this->instance = new UnitFactory();
    }

    public function testBuildReturnsAUnit()
    {
        $actual = $this->instance->build(array());
        $this->assertInstanceOf("HomeOffice\AlfrescoApiBundle\Entity\Unit", $actual);
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

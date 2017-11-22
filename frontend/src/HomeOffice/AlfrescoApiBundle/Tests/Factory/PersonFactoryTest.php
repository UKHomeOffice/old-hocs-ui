<?php

namespace HomeOffice\AlfrescoApiBundle\Tests\Factory;

use HomeOffice\AlfrescoApiBundle\Factory\PersonFactory;

class PersonFactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var PersonFactory
     */
    private $instance;

    protected function setUp()
    {
        $this->instance = new PersonFactory();
    }

    public function testBuildReturnsAPerson()
    {
        $actual = $this->instance->build(array());
        $this->assertInstanceOf("HomeOffice\AlfrescoApiBundle\Entity\Person", $actual);
    }

    public function testBuildSetsValidParameters()
    {
        $actual = $this->instance->build(array('firstName' => "Testing"));
        $this->assertEquals("Testing", $actual->getFirstName());
    }

    public function testBuildIgnoresNoneExistentParameters()
    {
        $actual = $this->instance->build(array('firstName' => "Testing", 'fake fake fake' => "Testing"));
        $this->assertEquals("Testing", $actual->getFirstName());
    }
}

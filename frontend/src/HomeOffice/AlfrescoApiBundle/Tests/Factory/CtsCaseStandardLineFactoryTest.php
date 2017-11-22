<?php

namespace HomeOffice\AlfrescoApiBundle\Tests\Factory;

use HomeOffice\AlfrescoApiBundle\Factory\CtsCaseStandardLineFactory;

class CtsCaseStandardLineFactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var CtsCaseStandardLineFactory
     */
    private $instance;

    protected function setUp()
    {
        $this->instance = new CtsCaseStandardLineFactory('workspace', 'store');
    }

    public function testBuildReturnsACtsCaseStandardLine()
    {
        $actual = $this->instance->build(array());
        $this->assertInstanceOf("HomeOffice\AlfrescoApiBundle\Entity\CtsCaseStandardLine", $actual);
    }

    public function testBuildSetsValidParameters()
    {
        $actual = $this->instance->build(array('associatedTopic' => "TestingTopic", 'associatedUnit' => 'TestingUnit'));
        $this->assertEquals("TestingTopic", $actual->getAssociatedTopic());
        $this->assertEquals("TestingUnit", $actual->getAssociatedUnit());
    }

    public function testBuildIgnoresNoneExistentParameters()
    {
        $actual = $this->instance->build(array('associatedTopic' => "Testing", 'fake fake fake' => "Testing"));
        $this->assertEquals("Testing", $actual->getAssociatedTopic());
    }
}

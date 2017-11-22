<?php

namespace HomeOffice\AlfrescoApiBundle\Tests\Factory;

use HomeOffice\AlfrescoApiBundle\Factory\CtsCaseMinuteFactory;

class CtsCaseMinuteFactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var CtsCaseMinuteFactory
     */
    private $instance;

    protected function setUp()
    {
        $this->instance = new CtsCaseMinuteFactory();
    }

    public function testBuildReturnsACtsCaseMinute()
    {
        $actual = $this->instance->build(array());
        $this->assertInstanceOf("HomeOffice\AlfrescoApiBundle\Entity\CtsCaseMinute", $actual);
    }

    public function testBuildSetsValidParameters()
    {
        $actual = $this->instance->build(array('minuteType' => "Testing"));
        $this->assertEquals("Testing", $actual->getMinuteType());
    }

    public function testBuildIgnoresNoneExistentParameters()
    {
        $actual = $this->instance->build(array('minuteType' => "Testing", 'fake fake fake' => "Testing"));
        $this->assertEquals("Testing", $actual->getMinuteType());
    }
}

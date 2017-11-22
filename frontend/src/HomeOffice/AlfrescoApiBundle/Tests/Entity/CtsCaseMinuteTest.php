<?php

namespace HomeOffice\AlfrescoApiBundle\Tests\Entity;

use HomeOffice\AlfrescoApiBundle\Entity\CtsCaseMinute;

class CtsCaseMinuteTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var CtsCaseMinute
     */
    private $instance;

    protected function setUp()
    {
        $this->instance = new CtsCaseMinute();
    }

    public function testSetGetMinuteType()
    {
        $this->instance->setMinuteType("Type");
        $this->assertEquals("Type", $this->instance->getMinuteType());
    }

    public function testSetGetMinuteDateTime()
    {
        $this->instance->setMinuteDateTime("2014-01-01");
        $this->assertEquals("2014-01-01", $this->instance->getMinuteDateTime());
    }

    public function testSetGetUpdatedBy()
    {
        $this->instance->setMinuteUpdatedBy("Joe Bloggs");
        $this->assertEquals("Joe Bloggs", $this->instance->getMinuteUpdatedBy());
    }

    public function testSetGetMinuteAction()
    {
        $this->instance->setMinuteContent("Case created");
        $this->assertEquals("Case created", $this->instance->getMinuteContent());
    }

    public function testGetSetMinuteQaReviewOutcomes()
    {
        $outcomes = array ('QA Passed');
        $this->assertNull($this->instance->getMinuteQaReviewOutcomes());
        $this->assertTrue($this->instance->setMinuteQaReviewOutcomes($outcomes) instanceof CtsCaseMinute);
        $this->assertEquals($outcomes, $this->instance->getMinuteQaReviewOutcomes());
    }

    public function testGetSetTask()
    {
        $expected = 'Do this task now!';
        $this->assertNull($this->instance->getTask());
        $this->assertTrue($this->instance->setTask($expected) instanceof CtsCaseMinute);
        $this->assertEquals($expected, $this->instance->getTask());
    }
}

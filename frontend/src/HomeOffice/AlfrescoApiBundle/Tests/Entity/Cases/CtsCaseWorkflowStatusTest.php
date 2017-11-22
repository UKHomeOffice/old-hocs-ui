<?php

namespace HomeOffice\AlfrescoApiBundle\Tests\Entity\Cases;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCaseWorkflowStatus;

class CtsCaseWorkflowStatusTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CtsCase
     */
    private $instance;

    protected function setUp()
    {
        $this->instance = new CtsCaseWorkflowStatus(array('test'), array('test2'));
    }
 
    public function testProperties()
    {
        $this->assertObjectHasAttribute('transitions', $this->instance);
        $this->assertObjectHasAttribute('mandatoryFields', $this->instance);
    }
 
    public function testGetSetName()
    {
        $this->instance->setTransitions(array('transitions1'));
        $this->assertEquals(array('transitions1'), $this->instance->getTransitions());
    }
 
    public function testGetSetMessage()
    {
        $this->instance->setMandatoryFields(array('mandatoryFields1'));
        $this->assertEquals(array('mandatoryFields1'), $this->instance->getMandatoryFields());
    }
}

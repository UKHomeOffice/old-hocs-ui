<?php

namespace HomeOffice\AlfrescoApiBundle\Tests\Entity\Cases;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCaseWorkflowTransition;

class CtsCaseWorkflowTransitionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CtsCase
     */
    private $instance;

    protected function setUp()
    {
        $this->instance = new CtsCaseWorkflowTransition('Submit', 'SubmitForQA', true, 'Allocate for QA', 'green');
    }
 
    public function testProperties()
    {
        $this->assertObjectHasAttribute('label', $this->instance);
        $this->assertObjectHasAttribute('value', $this->instance);
        $this->assertObjectHasAttribute('manualAllocate', $this->instance);
        $this->assertObjectHasAttribute('allocateHeader', $this->instance);
        $this->assertObjectHasAttribute('colour', $this->instance);
    }
 
    public function testGetSetLabel()
    {
        $this->instance->setLabel('Label');
        $this->assertEquals('Label', $this->instance->getLabel());
    }
 
    public function testGetSetValue()
    {
        $this->instance->setValue('Value');
        $this->assertEquals('Value', $this->instance->getValue());
    }
 
    public function testGetSetManualAllocate()
    {
        $this->instance->setManualAllocate(false);
        $this->assertFalse($this->instance->getManualAllocate());
    }
 
    public function testGetSetAllocateHeader()
    {
        $this->instance->setAllocateHeader('Allocate header');
        $this->assertEquals('Allocate header', $this->instance->getAllocateHeader());
    }
 
    public function testGetSetColour()
    {
        $this->instance->setColour('blue');
        $this->assertEquals('blue', $this->instance->getColour());
    }
}

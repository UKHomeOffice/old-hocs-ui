<?php

namespace HomeOffice\AlfrescoApiBundle\Tests\Entity\Cases;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCaseWorkflowValidation;

class CtsCaseWorkflowValidationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CtsCase
     */
    private $instance;

    protected function setUp()
    {
        $this->instance = new CtsCaseWorkflowValidation('testProperty', 'This is a test message');
    }
 
    public function testProperties()
    {
        $this->assertObjectHasAttribute('name', $this->instance);
        $this->assertObjectHasAttribute('message', $this->instance);
    }
 
    public function testGetSetName()
    {
        $this->instance->setName('nametest');
        $this->assertEquals('nametest', $this->instance->getName());
    }
 
    public function testGetSetMessage()
    {
        $this->instance->setMessage('messagetest');
        $this->assertEquals('messagetest', $this->instance->getMessage());
    }
}

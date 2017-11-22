<?php

namespace HomeOffice\AlfrescoApiBundle\Tests\Entity;

use HomeOffice\AlfrescoApiBundle\Entity\BulkCaseEntry;

class BulkCaseEntryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var BulkCaseEntry
     */
    private $instance;

    protected function setUp()
    {
        $this->instance = new BulkCaseEntry();

    }

    public function testProperties()
    {
        $this->assertObjectHasAttribute('correspondenceType', $this->instance);
        $this->assertObjectHasAttribute('files', $this->instance);
        $this->assertObjectHasAttribute('assignedUnit', $this->instance);
        $this->assertObjectHasAttribute('assignedUser', $this->instance);
        $this->assertObjectHasAttribute('assignedTeam', $this->instance);
    }

    public function testGetSetCorrespondenceType()
    {
        $expected = "TYPE";
        $this->assertTrue($this->instance->setCorrespondenceType($expected) instanceof BulkCaseEntry);
        $this->assertEquals($expected, $this->instance->getCorrespondenceType());
    }

    public function testGetSetFiles()
    {
        $files = array(
            'file1' => array(),
            'file2' => array()
        );

        $this->assertTrue($this->instance->setFiles($files) instanceof BulkCaseEntry);
        $this->assertEquals($files, $this->instance->getFiles());
    }

    public function testGetSetAssignedUnit()
    {
        $expected = 'Default Unit';
        $this->assertNull($this->instance->getAssignedUnit());
        $this->assertTrue($this->instance->setAssignedUnit($expected) instanceof BulkCaseEntry);
        $this->assertEquals($expected, $this->instance->getAssignedUnit());
    }

    public function testGetSetAssignedTeam()
    {
        $expected = 'Default Team';
        $this->assertNull($this->instance->getAssignedTeam());
        $this->assertTrue($this->instance->setAssignedTeam($expected) instanceof BulkCaseEntry);
        $this->assertEquals($expected, $this->instance->getAssignedTeam());
    }

    public function testGetSetAssignedUser()
    {
        $expected = 'Default User';
        $this->assertNull($this->instance->getAssignedUser());
        $this->assertTrue($this->instance->setAssignedUser($expected) instanceof BulkCaseEntry);
        $this->assertEquals($expected, $this->instance->getAssignedUser());
    }
}

<?php

namespace HomeOffice\AlfrescoApiBundle\Tests\Entity;

use HomeOffice\AlfrescoApiBundle\Entity\CtsCaseEntry;
use HomeOffice\AlfrescoApiBundle\Service\DateHelper;

class CtsCaseEntryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var CtsCaseEntry
     */
    private $instance;

    protected function setUp()
    {
        $this->instance = new CtsCaseEntry();
    }

    public function testProperties()
    {
        $this->assertObjectHasAttribute('correspondenceType', $this->instance);
        $this->assertObjectHasAttribute('dateReceived', $this->instance);
        $this->assertObjectHasAttribute('originalDocument', $this->instance);
        $this->assertObjectHasAttribute('uin', $this->instance);
        $this->assertObjectHasAttribute('opDate', $this->instance);
        $this->assertObjectHasAttribute('caseResponseDeadline', $this->instance);
        $this->assertObjectHasAttribute('foiIsEir', $this->instance);
        $this->assertObjectHasAttribute('hmpoStage', $this->instance);
        $this->assertObjectHasAttribute('validate', $this->instance);
    }

    public function testGetSetCorrespondenceType()
    {
        $this->instance->setCorrespondenceType("TYPE");
        $this->assertEquals("TYPE", $this->instance->getCorrespondenceType());
    }

    public function testGetSetDateReceived()
    {
        $date = new \DateTime('01/01/1990');
        $this->instance->setDateReceived('01/01/1990');
        $this->assertEquals($date, $this->instance->getDateReceived());

        $date = new \DateTime('01/01/1991');
        $this->instance->setDateReceived($date);
        $this->assertEquals($date, $this->instance->getDateReceived());
    }

    public function testGetSetOpDate()
    {
        $date = new \DateTime('01/01/1990');
        $this->instance->setOpDate('01/01/1990');
        $this->assertEquals($date, $this->instance->getOpDate());

        $date = new \DateTime('01/01/1991');
        $this->instance->setOpDate($date);
        $this->assertEquals($date, $this->instance->getOpDate());
    }

    public function testGetSetCaseResponseDeadline()
    {
        $date = new \DateTime('01/01/1990');
        $this->instance->setCaseResponseDeadline('01/01/1990');
        $this->assertEquals($date, $this->instance->getCaseResponseDeadline());

        $date = new \DateTime('01/01/1991');
        $this->instance->setCaseResponseDeadline($date);
        $this->assertEquals($date, $this->instance->getCaseResponseDeadline());
    }

    public function testGetSetUin()
    {
        $this->instance->setUin("1234");
        $this->assertEquals("1234", $this->instance->getUin());
    }

    public function testGetSetValidate()
    {
        $this->instance->setValidate("1234");
        $this->assertEquals("1234", $this->instance->getValidate());
    }

    public function testGetSetFoiIsEirTrue()
    {
        $this->instance->setFoiIsEir('true');
        $this->assertEquals(true, $this->instance->getFoiIsEir());
    }

    public function testGetSetFoiIsEirFalse()
    {
        $this->instance->setFoiIsEir('false');
        $this->assertEquals(false, $this->instance->getFoiIsEir());
    }

    public function testGetSetHmpoStage()
    {
        $this->instance->setHmpoStage('stage1');
        $this->assertEquals('stage1', $this->instance->getHmpoStage());
    }

    public function testToArray()
    {
        $dateReceived         = new DateHelper('01/01/1990');
        $opDate               = new DateHelper('02/02/1991');
        $caseResponseDeadline = new DateHelper('02/02/1993');

        $instanceArray = [
            'correspondenceType'   => 'Type',
            'dateReceived'         => $dateReceived,
            'originalDocument'     => 'Document',
            'uin'                  => 'Uin ref',
            'opDate'               => $opDate,
            'caseResponseDeadline' => $caseResponseDeadline,
            'validate'             => false,
            'hmpoStage'            => 'stage1',
            'foiIsEir'             => null,
            'departureDateFromUK'  => null
        ];
        $this->instance->setCorrespondenceType('Type');
        $this->instance->setDateReceived('01-01-1990');
        $this->instance->setOriginalDocument('Document');
        $this->instance->setUin('Uin ref');
        $this->instance->setOpDate('02-02-1991');
        $this->instance->setCaseResponseDeadline('02/02/1993');
        $this->instance->setHmpoStage('stage1');
        $this->assertEquals($instanceArray, $this->instance->toArray());
    }
}

<?php

namespace HomeOffice\AlfrescoApiBundle\Tests\Entity\Cases;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsPqCase;

class CtsPqCaseTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var CtsPqCase
     */
    private $instance;

    protected function setUp()
    {
        $this->instance = new CtsPqCase('workspace', 'store');
    }

    private function createGroupedCase($id, $uin)
    {
        $ctsCase = new CtsPqCase('workspace', 'store');
        $ctsCase->setId($id);
        $ctsCase->setUin($uin);
        return $ctsCase;
    }

    public function testProperties()
    {
        // common properties
        $this->assertObjectHasAttribute('id', $this->instance);
        $this->assertObjectHasAttribute('workspace', $this->instance);
        $this->assertObjectHasAttribute('store', $this->instance);
        $this->assertObjectHasAttribute('objectTypeId', $this->instance);
        $this->assertObjectHasAttribute('folderName', $this->instance);
        $this->assertObjectHasAttribute('dateCreated', $this->instance);
        $this->assertObjectHasAttribute('caseStatus', $this->instance);
        $this->assertObjectHasAttribute('caseTask', $this->instance);
        $this->assertObjectHasAttribute('caseOwner', $this->instance);
        $this->assertObjectHasAttribute('urnSuffix', $this->instance);
        $this->assertObjectHasAttribute('correspondenceType', $this->instance);
        $this->assertObjectHasAttribute('markupDecision', $this->instance);
        $this->assertObjectHasAttribute('markupUnit', $this->instance);
        $this->assertObjectHasAttribute('markupTopic', $this->instance);
        $this->assertObjectHasAttribute('markupMinister', $this->instance);
        $this->assertObjectHasAttribute('secondaryTopic', $this->instance);
        $this->assertObjectHasAttribute('assignedUser', $this->instance);
        $this->assertObjectHasAttribute('assignedTeam', $this->instance);
        $this->assertObjectHasAttribute('assignedUser', $this->instance);
        $this->assertObjectHasAttribute('caseMinutes', $this->instance);
        $this->assertObjectHasAttribute('caseDocuments', $this->instance);
        $this->assertObjectHasAttribute('newMinute', $this->instance);
        $this->assertObjectHasAttribute('caseDocuments', $this->instance);
        $this->assertObjectHasAttribute('newDocument', $this->instance);
        $this->assertObjectHasAttribute('canUpdateProperties', $this->instance);
        $this->assertObjectHasAttribute('canAssignUser', $this->instance);
        $this->assertObjectHasAttribute('hrnsToLink', $this->instance);
        $this->assertObjectHasAttribute('linkedCases', $this->instance);
        $this->assertObjectHasAttribute('isLinkedCase', $this->instance);
        $this->assertObjectHasAttribute('caseResponseDeadline', $this->instance);
        $this->assertObjectHasAttribute('caseWorkflowStatus', $this->instance);
        // PQ specific properties
        $this->assertObjectHasAttribute('uin', $this->instance);
        $this->assertObjectHasAttribute('opDate', $this->instance);
        $this->assertObjectHasAttribute('woDate', $this->instance);
        $this->assertObjectHasAttribute('questionNumber', $this->instance);
        $this->assertObjectHasAttribute('questionText', $this->instance);
        $this->assertObjectHasAttribute('answeringMinister', $this->instance);
        $this->assertObjectHasAttribute('answeringMinisterId', $this->instance);
        $this->assertObjectHasAttribute('receivedType', $this->instance);
        $this->assertObjectHasAttribute('draftResponseTarget', $this->instance);
        $this->assertObjectHasAttribute('member', $this->instance);
        $this->assertObjectHasAttribute('constituency', $this->instance);
        $this->assertObjectHasAttribute('party', $this->instance);
        $this->assertObjectHasAttribute('signedByHomeSec', $this->instance);
        $this->assertObjectHasAttribute('signedByLordsMinister', $this->instance);
        $this->assertObjectHasAttribute('reviewedByPermSec', $this->instance);
        $this->assertObjectHasAttribute('reviewedBySpads', $this->instance);
        $this->assertObjectHasAttribute('uinsToGroup', $this->instance);
        $this->assertObjectHasAttribute('groupedCases', $this->instance);
        $this->assertObjectHasAttribute('lordsMinister', $this->instance);
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

    public function testGetSetWoDate()
    {
        $date = new \DateTime('01/01/1990');
        $this->instance->setWoDate('01/01/1990');
        $this->assertEquals($date, $this->instance->getWoDate());

        $date = new \DateTime('01/01/1991');
        $this->instance->setWoDate($date);
        $this->assertEquals($date, $this->instance->getWoDate());
    }

    public function testGetSetCaseResponseDeadline()
    {
        $date = new \DateTime('01/01/1992');
        $this->instance->setCaseResponseDeadline('01/01/1992');
        $this->assertEquals($date, $this->instance->getCaseResponseDeadline());

        $date = new \DateTime('01/01/1991');
        $this->instance->setCaseResponseDeadline($date);
        $this->assertEquals($date, $this->instance->getCaseResponseDeadline());
    }

    public function testGetSetDraftResponseDate()
    {
        $date = new \DateTime('01/01/1992');
        $this->instance->setDraftResponseTarget('01/01/1992');
        $this->assertEquals($date, $this->instance->getDraftResponseTarget());

        $date = new \DateTime('01/01/1991');
        $this->instance->setDraftResponseTarget($date);
        $this->assertEquals($date, $this->instance->getDraftResponseTarget());
    }

    public function testGetSetUinsToGroup()
    {
        $this->instance->setUinsToGroup('1234,5678');
        $this->assertEquals('1234,5678', $this->instance->getUinsToGroup());
    }

    public function testGetSetGroupedCases()
    {
        $groupedCases = array(
            $this->createGroupedCase('1234', 'UIN1234'),
            $this->createGroupedCase('5678', 'UIN5678')
        );
        $this->instance->setGroupedCases($groupedCases);
        $this->assertEquals($groupedCases, $this->instance->getGroupedCases());
    }

    public function testGetSetIsGroupedSlave()
    {
        $this->instance->setIsGroupedSlave('true');
        $this->assertTrue($this->instance->getIsGroupedSlave());

        $this->instance->setIsGroupedSlave(null);
        $this->assertFalse($this->instance->getIsGroupedSlave());
    }

    public function testGetSetIsGroupedMaster()
    {
        $this->instance->setIsGroupedMaster('true');
        $this->assertTrue($this->instance->getIsGroupedMaster());

        $this->instance->setIsGroupedMaster(null);
        $this->assertFalse($this->instance->getIsGroupedMaster());
    }

    public function testGetSetMasterNodeRef()
    {
        $this->instance->setMasterNodeRef('1234');
        $this->assertEquals('1234', $this->instance->getMasterNodeRef());
    }

    public function testGetSetReviewedBySpads()
    {
        $this->instance->setReviewedBySpads(false);
        $this->assertEquals(false, $this->instance->getReviewedBySpads());
    }

    public function testGetSetRoundRobin()
    {
        $this->instance->setRoundRobin(true);
        $this->assertEquals(true, $this->instance->getRoundRobin());
    }

    public function testGetSetCabinetOfficeGuidance()
    {
        $this->instance->setCabinetOfficeGuidance('yes');
        $this->assertEquals('yes', $this->instance->getCabinetOfficeGuidance());
    }

    public function testGetSetTransferDepartmentName()
    {
        $this->instance->setTransferDepartmentName('homeoffice');
        $this->assertEquals('homeoffice', $this->instance->getTransferDepartmentName());
    }

    public function testGetSetLordsMinister()
    {
        $expected = 'Lord Minister';
        $this->assertEmpty($this->instance->getLordsMinister());
        $this->assertTrue($this->instance->setLordsMinister($expected) instanceof CtsPqCase);
        $this->assertEquals($expected, $this->instance->getLordsMinister());
    }
}

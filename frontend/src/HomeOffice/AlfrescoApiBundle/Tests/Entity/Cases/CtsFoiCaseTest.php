<?php

namespace HomeOffice\AlfrescoApiBundle\Tests\Entity\Cases;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsFoiCase;

class CtsFoiCaseTest extends \PHPUnit_Framework_TestCase
{
 
    /**
     * @var CtsCase
     */
    private $instance;

    protected function setUp()
    {
        $this->instance = new CtsFoiCase('workspace', 'store');
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
        $this->assertObjectHasAttribute('linkedCases', $this->instance);
        $this->assertObjectHasAttribute('hrnsToLink', $this->instance);
        $this->assertObjectHasAttribute('isLinkedCase', $this->instance);
        $this->assertObjectHasAttribute('caseResponseDeadline', $this->instance);
        $this->assertObjectHasAttribute('caseWorkflowStatus', $this->instance);
        // Foi specific properties
        $this->assertObjectHasAttribute('dateReceived', $this->instance);
        $this->assertObjectHasAttribute('channel', $this->instance);
        $this->assertObjectHasAttribute('allocateTarget', $this->instance);
        $this->assertObjectHasAttribute('draftResponseTarget', $this->instance);
        $this->assertObjectHasAttribute('scsApprovalTarget', $this->instance);
        $this->assertObjectHasAttribute('finalApprovalTarget', $this->instance);
        $this->assertObjectHasAttribute('foiMinisterSignOff', $this->instance);
        $this->assertObjectHasAttribute('foiIsEir', $this->instance);
        $this->assertObjectHasAttribute('appeals', $this->instance);
        $this->assertObjectHasAttribute('correspondentTitle', $this->instance);
        $this->assertObjectHasAttribute('correspondentForename', $this->instance);
        $this->assertObjectHasAttribute('correspondentSurname', $this->instance);
        $this->assertObjectHasAttribute('correspondentAddressLine1', $this->instance);
        $this->assertObjectHasAttribute('correspondentAddressLine2', $this->instance);
        $this->assertObjectHasAttribute('correspondentAddressLine3', $this->instance);
        $this->assertObjectHasAttribute('correspondentPostcode', $this->instance);
        $this->assertObjectHasAttribute('correspondentCountry', $this->instance);
        $this->assertObjectHasAttribute('correspondentTelephone', $this->instance);
        $this->assertObjectHasAttribute('correspondentEmail', $this->instance);
        $this->assertObjectHasAttribute('exemptions', $this->instance);
        $this->assertObjectHasAttribute('pitExtension', $this->instance);
        $this->assertObjectHasAttribute('pitLetterSentDate', $this->instance);
        $this->assertObjectHasAttribute('pitQualifiedExemptions', $this->instance);
        $this->assertObjectHasAttribute('acpoConsultation', $this->instance);
        $this->assertObjectHasAttribute('foiDisclosure', $this->instance);
        $this->assertObjectHasAttribute('cabinetOfficeConsultation', $this->instance);
        $this->assertObjectHasAttribute('nslgConsultation', $this->instance);
        $this->assertObjectHasAttribute('royalsConsultation', $this->instance);
        $this->assertObjectHasAttribute('roundRobinAdviceConsultation', $this->instance);
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
 
    public function testGetSetChannel()
    {
        $this->instance->setChannel('Letter');
        $this->assertEquals('Letter', $this->instance->getChannel());
    }
 
    public function testGetSetAllocateTarget()
    {
        $date = new \DateTime('01/01/1990');
        $this->instance->setAllocateTarget('01/01/1990');
        $this->assertEquals($date, $this->instance->getAllocateTarget());
     
        $date = new \DateTime('01/01/1991');
        $this->instance->setAllocateTarget($date);
        $this->assertEquals($date, $this->instance->getAllocateTarget());
    }
 
    public function testGetSetDraftResponseTarget()
    {
        $date = new \DateTime('01/01/1990');
        $this->instance->setDraftResponseTarget('01/01/1990');
        $this->assertEquals($date, $this->instance->getDraftResponseTarget());
     
        $date = new \DateTime('01/01/1991');
        $this->instance->setDraftResponseTarget($date);
        $this->assertEquals($date, $this->instance->getDraftResponseTarget());
    }
 
    public function testGetSetScsApprovalTarget()
    {
        $date = new \DateTime('01/01/1990');
        $this->instance->setScsApprovalTarget('01/01/1990');
        $this->assertEquals($date, $this->instance->getScsApprovalTarget());
     
        $date = new \DateTime('01/01/1991');
        $this->instance->setScsApprovalTarget($date);
        $this->assertEquals($date, $this->instance->getScsApprovalTarget());
    }
 
    public function testGetSetFinalApprovalTarget()
    {
        $date = new \DateTime('01/01/1990');
        $this->instance->setFinalApprovalTarget('01/01/1990');
        $this->assertEquals($date, $this->instance->getFinalApprovalTarget());
     
        $date = new \DateTime('01/01/1991');
        $this->instance->setFinalApprovalTarget($date);
        $this->assertEquals($date, $this->instance->getFinalApprovalTarget());
    }
 
    public function testGetSetFoiMinisterSignOffTrue()
    {
        $this->instance->setFoiMinisterSignOff('true');
        $this->assertEquals(true, $this->instance->getFoiMinisterSignOff());
    }
 
    public function testGetSetFoiMinisterSignOffFalse()
    {
        $this->instance->setFoiMinisterSignOff('false');
        $this->assertEquals(false, $this->instance->getFoiMinisterSignOff());
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
 
    public function testGetSetAppeals()
    {
        $this->instance->setAppeals(array());
        $this->assertEquals(array(), $this->instance->getAppeals());
    }
 
    public function testGetSetCorrespondentTitle()
    {
        $this->instance->setCorrespondentTitle('Mr');
        $this->assertEquals('Mr', $this->instance->getCorrespondentTitle());
    }
 
    public function testGetSetCorrespondentForename()
    {
        $this->instance->setCorrespondentForename('Joe');
        $this->assertEquals('Joe', $this->instance->getCorrespondentForename());
    }
 
    public function testGetSetCorrespondentSurname()
    {
        $this->instance->setCorrespondentSurname('Bloggs');
        $this->assertEquals('Bloggs', $this->instance->getCorrespondentSurname());
    }
 
    public function testGetSetCorrespondentPostcode()
    {
        $this->instance->setCorrespondentPostcode('SW1P 4DF');
        $this->assertEquals('SW1P 4DF', $this->instance->getCorrespondentPostcode());
    }
 
    public function testGetSetCorrespondentAddressLine1()
    {
        $this->instance->setCorrespondentAddressLine1('1 High Street');
        $this->assertEquals('1 High Street', $this->instance->getCorrespondentAddressLine1());
    }
 
    public function testGetSetCorrespondentAddressLine2()
    {
        $this->instance->setCorrespondentAddressLine2('Village');
        $this->assertEquals('Village', $this->instance->getCorrespondentAddressLine2());
    }
 
    public function testGetSetCorrespondentAddressLine3()
    {
        $this->instance->setCorrespondentAddressLine3('Town');
        $this->assertEquals('Town', $this->instance->getCorrespondentAddressLine3());
    }
 
    public function testGetSetCorrespondentCountry()
    {
        $this->instance->setCorrespondentCountry('England');
        $this->assertEquals('England', $this->instance->getCorrespondentCountry());
    }
 
    public function testGetSetCorrespondentTelephone()
    {
        $this->instance->setCorrespondentTelephone('01234 567890');
        $this->assertEquals('01234 567890', $this->instance->getCorrespondentTelephone());
    }
 
    public function testGetSetCorrespondentEmail()
    {
        $this->instance->setCorrespondentEmail('joe.bloggs@example.com');
        $this->assertEquals('joe.bloggs@example.com', $this->instance->getCorrespondentEmail());
    }
 
    public function testGetSetExemptions()
    {
        $array = array('Exemption1' => 'Exemption1', 'Exemption2' => 'Exemption2');
        $this->instance->setExemptions('Exemption1,Exemption2');
        $this->assertEquals($array, $this->instance->getExemptions());
     
        $this->instance->setExemptions($array);
        $this->assertEquals($array, $this->instance->getExemptions());
    }
 
    public function testGetExemptionsString()
    {
        $this->instance->setExemptions(array('Exemption1' => 'Exemption1', 'Exemption2' => 'Exemption2'));
        $this->assertEquals('Exemption1, Exemption2', $this->instance->getExemptionsString());
    }
 
    public function testGetSetPitExtensionTrue()
    {
        $this->instance->setPitExtension('true');
        $this->assertEquals(true, $this->instance->getPitExtension());
    }
 
    public function testGetSetPitExtensionFalse()
    {
        $this->instance->setPitExtension('false');
        $this->assertEquals(false, $this->instance->getPitExtension());
    }
 
    public function testGetSetPitLetterSentDate()
    {
        $date = new \DateTime('01/01/1990');
        $this->instance->setPitLetterSentDate('01/01/1990');
        $this->assertEquals($date, $this->instance->getPitLetterSentDate());
     
        $date = new \DateTime('01/01/1991');
        $this->instance->setPitLetterSentDate($date);
        $this->assertEquals($date, $this->instance->getPitLetterSentDate());
    }
 
    public function testGetSetPitQualifiedExemptions()
    {
        $array = array('Exemption1' => 'Exemption1', 'Exemption2' => 'Exemption2');
        $this->instance->setPitQualifiedExemptions('Exemption1,Exemption2');
        $this->assertEquals($array, $this->instance->getPitQualifiedExemptions());
     
        $this->instance->setPitQualifiedExemptions($array);
        $this->assertEquals($array, $this->instance->getPitQualifiedExemptions());
    }
 
    public function testGetPitQualifiedExemptionsString()
    {
        $this->instance->setPitQualifiedExemptions(array('Exemption1' => 'Exemption1', 'Exemption2' => 'Exemption2'));
        $this->assertEquals('Exemption1, Exemption2', $this->instance->getPitQualifiedExemptionsString());
    }
 
    public function testGetSetFoiDisclosureTrue()
    {
        $this->instance->setFoiDisclosure('true');
        $this->assertEquals(true, $this->instance->getFoiDisclosure());
    }
 
    public function testGetSetFoiDisclosureFalse()
    {
        $this->instance->setFoiDisclosure('false');
        $this->assertEquals(false, $this->instance->getFoiDisclosure());
    }
 
    public function testGetSetAcpoConsultationTrue()
    {
        $this->instance->setAcpoConsultation('true');
        $this->assertEquals(true, $this->instance->getAcpoConsultation());
    }
 
    public function testGetSetAcpoConsultationFalse()
    {
        $this->instance->setAcpoConsultation('false');
        $this->assertEquals(false, $this->instance->getAcpoConsultation());
    }
 
    public function testGetSetCabinetOfficeConsultationTrue()
    {
        $this->instance->setCabinetOfficeConsultation('true');
        $this->assertEquals(true, $this->instance->getCabinetOfficeConsultation());
    }
 
    public function testGetSetCabinetOfficeConsultationFalse()
    {
        $this->instance->setCabinetOfficeConsultation('false');
        $this->assertEquals(false, $this->instance->getCabinetOfficeConsultation());
    }
 
    public function testGetSetNslgConsultationTrue()
    {
        $this->instance->setNslgConsultation('true');
        $this->assertEquals(true, $this->instance->getNslgConsultation());
    }
 
    public function testGetSetNslgConsultationFalse()
    {
        $this->instance->setNslgConsultation('false');
        $this->assertEquals(false, $this->instance->getNslgConsultation());
    }
 
    public function testGetSetRoyalsConsultationTrue()
    {
        $this->instance->setRoyalsConsultation('true');
        $this->assertEquals(true, $this->instance->getRoyalsConsultation());
    }
 
    public function testGetSetRoyalsConsultationFalse()
    {
        $this->instance->setRoyalsConsultation('false');
        $this->assertEquals(false, $this->instance->getRoyalsConsultation());
    }
 
    public function testGetSetRoundRobinAdviceConsultationTrue()
    {
        $this->instance->setRoundRobinAdviceConsultation('true');
        $this->assertEquals(true, $this->instance->getRoundRobinAdviceConsultation());
    }
 
    public function testGetSetRoundRobinAdviceConsultationFalse()
    {
        $this->instance->setRoundRobinAdviceConsultation('false');
        $this->assertEquals(false, $this->instance->getRoundRobinAdviceConsultation());
    }
}

<?php

namespace HomeOffice\AlfrescoApiBundle\Tests\Entity\Cases;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsDcuMinisterialCase;

class CtsDcuMinisterialCaseTest extends \PHPUnit_Framework_TestCase
{
 
    /**
     * @var CtsCase
     */
    private $instance;

    protected function setUp()
    {
        $this->instance = new CtsDcuMinisterialCase('workspace', 'store');
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
        // Ministerial specific properties
        $this->assertObjectHasAttribute('dateReceived', $this->instance);
        $this->assertObjectHasAttribute('dateOfLetter', $this->instance);
        $this->assertObjectHasAttribute('channel', $this->instance);
        $this->assertObjectHasAttribute('priority', $this->instance);
        $this->assertObjectHasAttribute('advice', $this->instance);
        $this->assertObjectHasAttribute('poTarget', $this->instance);
        $this->assertObjectHasAttribute('draftResponseTarget', $this->instance);
        $this->assertObjectHasAttribute('allocateTarget', $this->instance);
        $this->assertObjectHasAttribute('dispatchTarget', $this->instance);
        $this->assertObjectHasAttribute('member', $this->instance);
        $this->assertObjectHasAttribute('homeSecretaryReply', $this->instance);
        $this->assertObjectHasAttribute('mpRef', $this->instance);
        $this->assertObjectHasAttribute('replyToName', $this->instance);
        $this->assertObjectHasAttribute('replyToPostcode', $this->instance);
        $this->assertObjectHasAttribute('replyToAddressLine1', $this->instance);
        $this->assertObjectHasAttribute('replyToAddressLine2', $this->instance);
        $this->assertObjectHasAttribute('replyToAddressLine3', $this->instance);
        $this->assertObjectHasAttribute('replyToCountry', $this->instance);
        $this->assertObjectHasAttribute('replyToTelephone', $this->instance);
        $this->assertObjectHasAttribute('replyToEmail', $this->instance);
        $this->assertObjectHasAttribute('correspondentTitle', $this->instance);
        $this->assertObjectHasAttribute('correspondentForename', $this->instance);
        $this->assertObjectHasAttribute('correspondentSurname', $this->instance);
        $this->assertObjectHasAttribute('correspondentPostcode', $this->instance);
        $this->assertObjectHasAttribute('correspondentAddressLine1', $this->instance);
        $this->assertObjectHasAttribute('correspondentAddressLine2', $this->instance);
        $this->assertObjectHasAttribute('correspondentAddressLine3', $this->instance);
        $this->assertObjectHasAttribute('correspondentCountry', $this->instance);
        $this->assertObjectHasAttribute('correspondentTelephone', $this->instance);
        $this->assertObjectHasAttribute('correspondentEmail', $this->instance);
        $this->assertObjectHasAttribute('replyToNumberTenCopy', $this->instance);
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
 
    public function testGetSetDateOfLetter()
    {
        $date = new \DateTime('01/01/1990');
        $this->instance->setDateOfLetter('01/01/1990');
        $this->assertEquals($date, $this->instance->getDateOfLetter());
     
        $date = new \DateTime('01/01/1991');
        $this->instance->setDateOfLetter($date);
        $this->assertEquals($date, $this->instance->getDateOfLetter());
    }
 
    public function testGetSetChannel()
    {
        $this->instance->setChannel('Letter');
        $this->assertEquals('Letter', $this->instance->getChannel());
    }
 
    public function testGetSetPriorityTrue()
    {
        $this->instance->setPriority('true');
        $this->assertEquals(true, $this->instance->getPriority());
    }
 
    public function testGetSetPriorityFalse()
    {
        $this->instance->setPriority('false');
        $this->assertEquals(false, $this->instance->getPriority());
    }
 
    public function testGetSetAdviceTrue()
    {
        $this->instance->setAdvice('true');
        $this->assertEquals(true, $this->instance->getAdvice());
    }
 
    public function testGetSetAdviceFalse()
    {
        $this->instance->setAdvice('false');
        $this->assertEquals(false, $this->instance->getAdvice());
    }
 
    public function testGetSetPoTarget()
    {
        $date = new \DateTime('01/01/1990');
        $this->instance->setPoTarget('01/01/1990');
        $this->assertEquals($date, $this->instance->getPoTarget());
     
        $date = new \DateTime('01/01/1991');
        $this->instance->setPoTarget($date);
        $this->assertEquals($date, $this->instance->getPoTarget());
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
 
    public function testGetSetDispatchTarget()
    {
        $date = new \DateTime('01/01/1990');
        $this->instance->setDispatchTarget('01/01/1990');
        $this->assertEquals($date, $this->instance->getDispatchTarget());
     
        $date = new \DateTime('01/01/1991');
        $this->instance->setDispatchTarget($date);
        $this->assertEquals($date, $this->instance->getDispatchTarget());
    }
 
    public function testGetSetMember()
    {
        $this->instance->setMember('Member name');
        $this->assertEquals('Member name', $this->instance->getMember());
    }
 
    public function testGetSetHomeSecretaryReplyTrue()
    {
        $this->instance->setHomeSecretaryReply(true);
        $this->assertEquals(true, $this->instance->getHomeSecretaryReply());
    }
 
    public function testGetSetHomeSecretaryReplyFalse()
    {
        $this->instance->setHomeSecretaryReply(false);
        $this->assertEquals(false, $this->instance->getHomeSecretaryReply());
    }
 
    public function testGetSetMpRef()
    {
        $this->instance->setMPRef('MP Reference');
        $this->assertEquals('MP Reference', $this->instance->getMpRef());
    }
 
    public function testGetSetReplyToName()
    {
        $this->instance->setReplyToName('Joe Bloggs');
        $this->assertEquals('Joe Bloggs', $this->instance->getReplyToName());
    }
 
    public function testGetSetReplyToPostcode()
    {
        $this->instance->setReplyToPostcode('SW1P 4DF');
        $this->assertEquals('SW1P 4DF', $this->instance->getReplyToPostcode());
    }
 
    public function testGetSetReplyToAddressLine1()
    {
        $this->instance->setReplyToAddressLine1('1 High Street');
        $this->assertEquals('1 High Street', $this->instance->getReplyToAddressLine1());
    }
 
    public function testGetSetReplyToAddressLine2()
    {
        $this->instance->setReplyToAddressLine2('Village');
        $this->assertEquals('Village', $this->instance->getReplyToAddressLine2());
    }
 
    public function testGetSetReplyToAddressLine3()
    {
        $this->instance->setReplyToAddressLine3('Town');
        $this->assertEquals('Town', $this->instance->getReplyToAddressLine3());
    }
 
    public function testGetSetReplyToCountry()
    {
        $this->instance->setReplyToCountry('England');
        $this->assertEquals('England', $this->instance->getReplyToCountry());
    }
 
    public function testGetSetReplyToTelephone()
    {
        $this->instance->setReplyToTelephone('01234 567890');
        $this->assertEquals('01234 567890', $this->instance->getReplyToTelephone());
    }
 
    public function testGetSetReplyToEmail()
    {
        $this->instance->setReplyToEmail('joe.bloggs@example.com');
        $this->assertEquals('joe.bloggs@example.com', $this->instance->getReplyToEmail());
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
 
    public function testGetSetReplyToNumberTenCopyTrue()
    {
        $this->instance->setReplyToNumberTenCopy('true');
        $this->assertEquals(true, $this->instance->getReplyToNumberTenCopy());
    }
 
    public function testGetSetReplyToNumberTenCopyFalse()
    {
        $this->instance->setReplyToNumberTenCopy('false');
        $this->assertEquals(false, $this->instance->getReplyToNumberTenCopy());
    }
}

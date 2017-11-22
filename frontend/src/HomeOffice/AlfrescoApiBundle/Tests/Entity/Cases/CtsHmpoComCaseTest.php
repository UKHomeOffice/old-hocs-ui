<?php

namespace HomeOffice\AlfrescoApiBundle\Tests\Entity\Cases;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsHmpoComCase;

class CtsHmpoComCaseTest extends \PHPUnit_Framework_TestCase
{
 
    /**
     * @var CtsCase
     */
    private $instance;

    protected function setUp()
    {
        $this->instance = new CtsHmpoComCase('workspace', 'store');
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
        $this->assertObjectHasAttribute('passportNumber', $this->instance);
        $this->assertObjectHasAttribute('applicationNumber', $this->instance);
        // HMPO COM specific properties
        $this->assertObjectHasAttribute('dateReceived', $this->instance);
        $this->assertObjectHasAttribute('channel', $this->instance);
        $this->assertObjectHasAttribute('hmpoResponse', $this->instance);
        $this->assertObjectHasAttribute('hmpoStage', $this->instance);
        $this->assertObjectHasAttribute('replyToCorrespondent', $this->instance);
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
        $this->assertObjectHasAttribute('typeOfCorrespondent', $this->instance);
        $this->assertObjectHasAttribute('typeOfComplainant', $this->instance);
        $this->assertObjectHasAttribute('typeOfRepresentative', $this->instance);
        $this->assertObjectHasAttribute('replyToApplicant', $this->instance);
        $this->assertObjectHasAttribute('applicantTitle', $this->instance);
        $this->assertObjectHasAttribute('applicantForename', $this->instance);
        $this->assertObjectHasAttribute('applicantSurname', $this->instance);
        $this->assertObjectHasAttribute('applicantPostcode', $this->instance);
        $this->assertObjectHasAttribute('applicantAddressLine1', $this->instance);
        $this->assertObjectHasAttribute('applicantAddressLine2', $this->instance);
        $this->assertObjectHasAttribute('applicantAddressLine3', $this->instance);
        $this->assertObjectHasAttribute('applicantCountry', $this->instance);
        $this->assertObjectHasAttribute('applicantTelephone', $this->instance);
        $this->assertObjectHasAttribute('applicantEmail', $this->instance);
        $this->assertObjectHasAttribute('replyToComplainant', $this->instance);
        $this->assertObjectHasAttribute('complainantTitle', $this->instance);
        $this->assertObjectHasAttribute('complainantForename', $this->instance);
        $this->assertObjectHasAttribute('complainantSurname', $this->instance);
        $this->assertObjectHasAttribute('complainantPostcode', $this->instance);
        $this->assertObjectHasAttribute('complainantAddressLine1', $this->instance);
        $this->assertObjectHasAttribute('complainantAddressLine2', $this->instance);
        $this->assertObjectHasAttribute('complainantAddressLine3', $this->instance);
        $this->assertObjectHasAttribute('complainantCountry', $this->instance);
        $this->assertObjectHasAttribute('complainantTelephone', $this->instance);
        $this->assertObjectHasAttribute('complainantEmail', $this->instance);
        $this->assertObjectHasAttribute('hmpoRefundDecision', $this->instance);
        $this->assertObjectHasAttribute('hmpoRefundAmount', $this->instance);
        $this->assertObjectHasAttribute('hmpoComplaintOutcome', $this->instance);
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
 
    public function testGetSetHmpoResponse()
    {
        $this->instance->setHmpoResponse('Response');
        $this->assertEquals('Response', $this->instance->getHmpoResponse());
    }
 
    public function testGetSetHmpoStage()
    {
        $this->instance->setHmpoStage('stage1');
        $this->assertEquals('stage1', $this->instance->getHmpoStage());
    }
 
    public function testGetSetReplyToCorrespondentTrue()
    {
        $this->instance->setReplyToCorrespondent('true');
        $this->assertEquals(true, $this->instance->getReplyToCorrespondent());
    }
 
    public function testGetSetReplyToCorrespondentFalse()
    {
        $this->instance->setReplyToCorrespondent('false');
        $this->assertEquals(false, $this->instance->getReplyToCorrespondent());
    }

    public function testGetSetPassportNumber()
    {
        $this->instance->setPassportNumber('QWERT1234567890');
        $this->assertEquals('QWERT1234567890', $this->instance->getPassportNumber());
    }

    public function testGetSetApplicationNumber()
    {
        $this->instance->setApplicationNumber('QWERT1234567890');
        $this->assertEquals('QWERT1234567890', $this->instance->getApplicationNumber());
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
 
    public function testGetSetTypeOfCorrespondent()
    {
        $this->instance->setTypeOfCorrespondent('Applicant');
        $this->assertEquals('Applicant', $this->instance->getTypeOfCorrespondent());
    }
 
    public function testGetSetTypeOfComplainant()
    {
        $this->instance->setTypeOfComplainant('Relative');
        $this->assertEquals('Relative', $this->instance->getTypeOfComplainant());
    }
 
    public function testGetSetTypeOfRepresentative()
    {
        $this->instance->setTypeOfRepresentative('Lawyer');
        $this->assertEquals('Lawyer', $this->instance->getTypeOfRepresentative());
    }
 
    public function testGetSetReplyToApplicantTrue()
    {
        $this->instance->setReplyToApplicant('true');
        $this->assertEquals(true, $this->instance->getReplyToApplicant());
    }
 
    public function testGetSetReplyToApplicantFalse()
    {
        $this->instance->setReplyToApplicant('false');
        $this->assertEquals(false, $this->instance->getReplyToApplicant());
    }
 
    public function testGetSetApplicantTitle()
    {
        $this->instance->setApplicantTitle('Mr');
        $this->assertEquals('Mr', $this->instance->getApplicantTitle());
    }
 
    public function testGetSetApplicantForename()
    {
        $this->instance->setApplicantForename('Joe');
        $this->assertEquals('Joe', $this->instance->getApplicantForename());
    }
 
    public function testGetSetApplicantSurname()
    {
        $this->instance->setApplicantSurname('Bloggs');
        $this->assertEquals('Bloggs', $this->instance->getApplicantSurname());
    }
 
    public function testGetSetApplicantPostcode()
    {
        $this->instance->setApplicantPostcode('SW1P 4DF');
        $this->assertEquals('SW1P 4DF', $this->instance->getApplicantPostcode());
    }
 
    public function testGetSetApplicantAddressLine1()
    {
        $this->instance->setApplicantAddressLine1('1 High Street');
        $this->assertEquals('1 High Street', $this->instance->getApplicantAddressLine1());
    }
 
    public function testGetSetApplicantAddressLine2()
    {
        $this->instance->setApplicantAddressLine2('Village');
        $this->assertEquals('Village', $this->instance->getApplicantAddressLine2());
    }
 
    public function testGetSetApplicantAddressLine3()
    {
        $this->instance->setApplicantAddressLine3('Town');
        $this->assertEquals('Town', $this->instance->getApplicantAddressLine3());
    }
 
    public function testGetSetApplicantCountry()
    {
        $this->instance->setApplicantCountry('England');
        $this->assertEquals('England', $this->instance->getApplicantCountry());
    }
 
    public function testGetSetApplicantTelephone()
    {
        $this->instance->setApplicantTelephone('01234 567890');
        $this->assertEquals('01234 567890', $this->instance->getApplicantTelephone());
    }
 
    public function testGetSetApplicantEmail()
    {
        $this->instance->setApplicantEmail('joe.bloggs@example.com');
        $this->assertEquals('joe.bloggs@example.com', $this->instance->getApplicantEmail());
    }
 
    public function testGetSetReplyToComplainantTrue()
    {
        $this->instance->setReplyToComplainant('true');
        $this->assertEquals(true, $this->instance->getReplyToComplainant());
    }
 
    public function testGetSetReplyToComplainantFalse()
    {
        $this->instance->setReplyToComplainant('false');
        $this->assertEquals(false, $this->instance->getReplyToComplainant());
    }
 
    public function testGetSetComplainantTitle()
    {
        $this->instance->setComplainantTitle('Mr');
        $this->assertEquals('Mr', $this->instance->getComplainantTitle());
    }
 
    public function testGetSetComplainantForename()
    {
        $this->instance->setComplainantForename('Joe');
        $this->assertEquals('Joe', $this->instance->getComplainantForename());
    }
 
    public function testGetSetComplainantSurname()
    {
        $this->instance->setComplainantSurname('Bloggs');
        $this->assertEquals('Bloggs', $this->instance->getComplainantSurname());
    }
 
    public function testGetSetComplainantPostcode()
    {
        $this->instance->setComplainantPostcode('SW1P 4DF');
        $this->assertEquals('SW1P 4DF', $this->instance->getComplainantPostcode());
    }
 
    public function testGetSetComplainantAddressLine1()
    {
        $this->instance->setComplainantAddressLine1('1 High Street');
        $this->assertEquals('1 High Street', $this->instance->getComplainantAddressLine1());
    }
 
    public function testGetSetComplainantAddressLine2()
    {
        $this->instance->setComplainantAddressLine2('Village');
        $this->assertEquals('Village', $this->instance->getComplainantAddressLine2());
    }
 
    public function testGetSetComplainantAddressLine3()
    {
        $this->instance->setComplainantAddressLine3('Town');
        $this->assertEquals('Town', $this->instance->getComplainantAddressLine3());
    }
 
    public function testGetSetComplainantCountry()
    {
        $this->instance->setComplainantCountry('England');
        $this->assertEquals('England', $this->instance->getComplainantCountry());
    }
 
    public function testGetSetComplainantTelephone()
    {
        $this->instance->setComplainantTelephone('01234 567890');
        $this->assertEquals('01234 567890', $this->instance->getComplainantTelephone());
    }
 
    public function testGetSetComplainantEmail()
    {
        $this->instance->setComplainantEmail('joe.bloggs@example.com');
        $this->assertEquals('joe.bloggs@example.com', $this->instance->getComplainantEmail());
    }
 
    public function testGetSetHmpoRefundDecision()
    {
        $this->instance->setHmpoRefundDecision('Reimbursement');
        $this->assertEquals('Reimbursement', $this->instance->getHmpoRefundDecision());
    }
 
    public function testGetSetHmpoRefundAmount()
    {
        $this->instance->setHmpoRefundAmount('£9.99');
        $this->assertEquals('£9.99', $this->instance->getHmpoRefundAmount());
    }
 
    public function testGetSetHmpoComplaintOutcome()
    {
        $this->instance->setHmpoComplaintOutcome('Upheld');
        $this->assertEquals('Upheld', $this->instance->getHmpoComplaintOutcome());
    }
}

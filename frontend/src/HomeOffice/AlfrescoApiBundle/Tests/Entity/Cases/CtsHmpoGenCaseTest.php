<?php

namespace HomeOffice\AlfrescoApiBundle\Tests\Entity\Cases;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsHmpoGenCase;

class CtsHmpoGenCaseTest extends \PHPUnit_Framework_TestCase
{
 
    /**
     * @var CtsCase
     */
    private $instance;

    protected function setUp()
    {
        $this->instance = new CtsHmpoGenCase('workspace', 'store');
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
        // HMPO GEN specific properties
        $this->assertObjectHasAttribute('dateReceived', $this->instance);
        $this->assertObjectHasAttribute('channel', $this->instance);
        $this->assertObjectHasAttribute('hmpoResponse', $this->instance);
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
        $this->assertObjectHasAttribute('typeOfThirdParty', $this->instance);
        $this->assertObjectHasAttribute('consentAttached', $this->instance);
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
 
    public function testGetSetTypeOfThirdParty()
    {
        $this->instance->setTypeOfThirdParty('Applicant');
        $this->assertEquals('Applicant', $this->instance->getTypeOfThirdParty());
    }
 
    public function testGetSetConsentAttachedTrue()
    {
        $this->instance->setConsentAttached('true');
        $this->assertEquals(true, $this->instance->getConsentAttached());
    }
 
    public function testGetSetConsentAttachedFalse()
    {
        $this->instance->setConsentAttached('false');
        $this->assertEquals(false, $this->instance->getConsentAttached());
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
}

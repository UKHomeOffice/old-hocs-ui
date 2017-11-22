<?php

namespace HomeOffice\AlfrescoApiBundle\Tests\Entity\Cases;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsHmpoCollCase;
use \DateTime;

class CtsHmpoCollCaseTest extends \PHPUnit_Framework_TestCase
{
 
    /**
     * @var CtsCase
     */
    private $instance;

    private $context;

    private $violationBuilder;

    protected function setUp()
    {
        $this->instance = new CtsHmpoCollCase('workspace', 'store');
        $this->context = $this
            ->getMockBuilder('Symfony\Component\Validator\Context\ExecutionContextInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->violationBuilder = $this
            ->getMockBuilder('Symfony\Component\Validator\Violation\ConstraintViolationBuilder')
            ->disableOriginalConstructor()
            ->getMock();

        $this->context->expects($this->any())
            ->method($this->anything())
            ->willReturn($this->violationBuilder);

        $this->violationBuilder->expects($this->any())
            ->method($this->anything())
            ->willReturn($this->violationBuilder);
    }
 
    public function testProperties()
    {
        // Common properties
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
        // HMPO Collective specific properties
        $this->assertObjectHasAttribute('priority', $this->instance);
        $this->assertObjectHasAttribute('hardCopyReceived', $this->instance);
        $this->assertObjectHasAttribute('correspondingName', $this->instance);
        $this->assertObjectHasAttribute('numberOfChildren', $this->instance);
        $this->assertObjectHasAttribute('countryOfDestination', $this->instance);
        $this->assertObjectHasAttribute('otherCountriesToBeVisited', $this->instance);
        $this->assertObjectHasAttribute('countriesToBeTravelledThrough', $this->instance);
        $this->assertObjectHasAttribute('departureDateFromUK', $this->instance);
        $this->assertObjectHasAttribute('arrivingDateInUK', $this->instance);
        $this->assertObjectHasAttribute('individualHousehold', $this->instance);
        $this->assertObjectHasAttribute('feeIncluded', $this->instance);
        $this->assertObjectHasAttribute('leadersAddressAboard', $this->instance);
        $this->assertObjectHasAttribute('partyLeaderLastName', $this->instance);
        $this->assertObjectHasAttribute('partyLeaderOtherNames', $this->instance);
        $this->assertObjectHasAttribute('partyLeaderPassportIssuedAt', $this->instance);
        $this->assertObjectHasAttribute('partyLeaderPassportIssuedOn', $this->instance);
        $this->assertObjectHasAttribute('partyLeaderDeputyLastName', $this->instance);
        $this->assertObjectHasAttribute('partyLeaderDeputyOtherNames', $this->instance);
        $this->assertObjectHasAttribute('partyLeaderDeputyPassportNumber', $this->instance);
        $this->assertObjectHasAttribute('partyLeaderDeputyPassportIssuedAt', $this->instance);
        $this->assertObjectHasAttribute('partyLeaderDeputyPassportIssuedOn', $this->instance);
        $this->assertObjectHasAttribute('deliveryType', $this->instance);
        $this->assertObjectHasAttribute('examinerSecurityCheck', $this->instance);
        $this->assertObjectHasAttribute('passportStatus', $this->instance);
        $this->assertObjectHasAttribute('bringUpDate', $this->instance);
        $this->assertObjectHasAttribute('deferDispatch', $this->instance);
        $this->assertObjectHasAttribute('dispatchedDate', $this->instance);
        $this->assertObjectHasAttribute('deliveryNumber', $this->instance);
        $this->assertObjectHasAttribute('amendments', $this->instance);
    }

    public function testGetAndSetDepartureDateFromUk() {
        $this->instance->setDepartureDateFromUk(new DateTime('today'));
        $this->assertNotEquals(null, $this->instance->getDepartureDateFromUk());
    }

    public function testGetAndSetArrivingDateInUk() {
        $this->instance->setArrivingDateInUk(new DateTime('today'));
        $this->assertNotEquals(null, $this->instance->getArrivingDateInUK());
    }
 
    public function testValidateDepartureDateFromUkPastDate()
    {

        $this->violationBuilder->expects($this->once())
            ->method('addViolation');

        $this->instance->setDepartureDateFromUk(new DateTime('yesterday'));
        $this->instance->validateDepartureDateFromUk($this->context);

    }

    public function testValidateDepartureDateFromUkPresentDate()
    {

        $this->violationBuilder->expects($this->never())
            ->method('addViolation');

        $this->instance->setDepartureDateFromUk(new DateTime('today'));
        $this->instance->validateDepartureDateFromUk($this->context);

    }

    public function testValidateArrivingDateInUkPastDate()
    {

        $this->violationBuilder->expects($this->once())
            ->method('addViolation');

        $this->instance->setArrivingDateInUk(new DateTime('yesterday'));
        $this->instance->validateArrivingDateInUk($this->context);

    }

    public function testValidateArrivingDateInUkInDate()
    {

        $this->violationBuilder->expects($this->never())
            ->method('addViolation');

        $this->instance->setArrivingDateInUk(new DateTime('today'));
        $this->instance->validateArrivingDateInUk($this->context);

    }
}

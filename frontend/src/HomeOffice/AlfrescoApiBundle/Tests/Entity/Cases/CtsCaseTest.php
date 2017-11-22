<?php

namespace HomeOffice\AlfrescoApiBundle\Tests\Entity\Cases;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use HomeOffice\AlfrescoApiBundle\Entity\CtsCaseMinute;
use HomeOffice\AlfrescoApiBundle\Entity\CtsCaseDocument;
use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCaseWorkflowTransition;

class CtsCaseTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var CtsCase
     */
    private $instance;

    protected function setUp()
    {
        $this->instance = new CtsCase('workspace', 'store');
    }

    private function createLinkedCase($id, $correspondenceType, $urnSuffix)
    {
        $ctsCase = new CtsCase('workspace', 'store');
        $ctsCase->setId($id);
        $ctsCase->setCorrespondenceType($correspondenceType);
        $ctsCase->setUrnSuffix($urnSuffix);
        return $ctsCase;
    }

    private function createCtsCaseDocument()
    {
        $caseDocument = new CtsCaseDocument('workspace', 'store');
        $caseDocument->setId('123');
        $caseDocument->setDocumentType('TYPE');
        $caseDocument->setDocumentDescription('DESC');
        $caseDocument->setVersionNumber('1.0');
        $caseDocument->setMimeType('text/plain');
        return $caseDocument;
    }

    private function createCtsCaseMinute()
    {
        $caseMinute = new CtsCaseMinute();
        $caseMinute->setMinuteType('MANUAL');
        $caseMinute->setMinuteDateTime('24-07-2014');
        $caseMinute->setMinuteContent('Create case');
        $caseMinute->setMinuteUpdatedBy('Bob');
        return $caseMinute;
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
        $this->assertObjectHasAttribute('caseResponseDeadline', $this->instance);
        $this->assertObjectHasAttribute('markupDecision', $this->instance);
        $this->assertObjectHasAttribute('markupUnit', $this->instance);
        $this->assertObjectHasAttribute('markupTopic', $this->instance);
        $this->assertObjectHasAttribute('markupMinister', $this->instance);
        $this->assertObjectHasAttribute('secondaryTopic', $this->instance);
        $this->assertObjectHasAttribute('assignedUnit', $this->instance);
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
        $this->assertObjectHasAttribute('caseWorkflowStatus', $this->instance);
    }

    public function testGetSetId()
    {
        $this->instance->setId(1);
        $this->assertEquals(1, $this->instance->getId());
    }

    public function testGetSetFolderName()
    {
        $this->instance->setFolderName("Test folder name");
        $this->assertEquals("Test folder name", $this->instance->getFolderName());
    }

    public function testGetSetCaseStatus()
    {
        $this->instance->setCaseStatus('New');
        $this->assertEquals('New', $this->instance->getCaseStatus());
    }

    public function testGetSetCaseTask()
    {
        $this->instance->setCaseTask('Create case');
        $this->assertEquals('Create case', $this->instance->getCaseTask());
    }

    public function testGetSetCaseOwner()
    {
        $this->instance->setCaseOwner('Joe Bloggs');
        $this->assertEquals('Joe Bloggs', $this->instance->getCaseOwner());
    }

    public function testGetSetUrnSuffix()
    {
        $this->instance->setUrnSuffix('Suffix');
        $this->assertEquals('Suffix', $this->instance->getUrnSuffix());
    }

    public function testGetSetCorrespondenceType()
    {
        $this->instance->setCorrespondenceType("TYPE");
        $this->assertEquals("TYPE", $this->instance->getCorrespondenceType());
    }

    public function testGetSetAssignedUnit()
    {
        $this->instance->setAssignedUnit('Test Unit');
        $this->assertEquals('Test Unit', $this->instance->getAssignedUnit());
    }

    public function testGetSetAssignedTeam()
    {
        $this->instance->setAssignedTeam('Test Team');
        $this->assertEquals('Test Team', $this->instance->getAssignedTeam());
    }

    public function testGetSetMarkupDecision()
    {
        $this->instance->setMarkupDecision('Decision');
        $this->assertEquals('Decision', $this->instance->getMarkupDecision());
    }

    public function testGetSetMarkupUnit()
    {
        $this->instance->setMarkupUnit('Unit');
        $this->assertEquals('Unit', $this->instance->getMarkupUnit());
    }

    public function testGetSetMarkupTopic()
    {
        $this->instance->setMarkupTopic('Topic');
        $this->assertEquals('Topic', $this->instance->getMarkupTopic());
    }

    public function testGetSetMarkupMinister()
    {
        $this->instance->setMarkupMinister('Minister');
        $this->assertEquals('Minister', $this->instance->getMarkupMinister());
    }

    public function testGetSetSecondaryTopic()
    {
        $this->instance->setSecondaryTopic('Topic');
        $this->assertEquals('Topic', $this->instance->getSecondaryTopic());
    }

    public function testGetSetAssignedUser()
    {
        $this->instance->setAssignedUser('Joe Bloggs');
        $this->assertEquals('Joe Bloggs', $this->instance->getAssignedUser());
    }

    public function testGetSetCanUpdatePropertiesTrue()
    {
        $this->instance->setCanUpdateProperties('true');
        $this->assertTrue($this->instance->getCanUpdateProperties());
    }

    public function testGetSetCanUpdatePropertiesFalse()
    {
        $this->assertFalse($this->instance->getCanUpdateProperties());
        $this->instance->setCanUpdateProperties('false');
        $this->assertFalse($this->instance->getCanUpdateProperties());
    }

    public function testGetSetCanAssignUserTrue()
    {
        $this->instance->setCanAssignUser('true');
        $this->assertTrue($this->instance->getCanAssignUser());
    }

    public function testGetSetCanAssignUserFalse()
    {
        $this->assertFalse($this->instance->getCanAssignUser());
        $this->instance->setCanAssignUser('false');
        $this->assertFalse($this->instance->getCanAssignUser());
    }

    public function testGetSetDateCreated()
    {
        $date = new \DateTime('01/01/1990');
        $this->instance->setDateCreated('01/01/1990');
        $this->assertEquals($date, $this->instance->getDateCreated());
    }

    public function testGetSetCaseMinutesArray()
    {
        $caseMinuteArray = array(
            array(
                'minuteType' => 'MANUAL',
                'minuteDateTime' => '24-07-2014',
                'minuteUpdatedBy' => 'Bob',
                'minuteContent' => 'Create case'
            )
        );
        $caseMinutes = array($this->createCtsCaseMinute());
        $this->instance->setCaseMinutes($caseMinuteArray);
        $this->assertEquals($caseMinutes, $this->instance->getCaseMinutes());
        $caseMinuteArrayEmpty = array();
        $this->instance->setCaseMinutes($caseMinuteArrayEmpty);
        $this->assertEquals(array(), $this->instance->getCaseMinutes());
    }

    public function testGetSetCaseMinutesObject()
    {
        $caseMinutes = array($this->createCtsCaseMinute());
        $this->instance->setCaseMinutes($caseMinutes);
        $this->assertEquals($caseMinutes, $this->instance->getCaseMinutes());
        $caseMinutesEmpty = array();
        $this->instance->setCaseMinutes($caseMinutesEmpty);
        $this->assertEquals($caseMinutesEmpty, $this->instance->getCaseMinutes());
    }

    public function testGetSetCaseDocumentsArray()
    {
        $caseDocumentArray = array(
            array(
                'id' => '123',
                'documentType' => 'TYPE',
                'documentDescription' => 'DESC',
                'versionNumber' => '1.0',
                'mimeType' => 'text/plain'
            )
        );
        $this->instance->setCaseDocuments($caseDocumentArray);
        $this->assertEquals(array($this->createCtsCaseDocument()), $this->instance->getCaseDocuments());
        $caseDocumentArrayEmpty = array();
        $this->instance->setCaseDocuments($caseDocumentArrayEmpty);
        $this->assertEquals(array(), $this->instance->getCaseDocuments());
    }

    public function testGetSetCaseDocumentsObject()
    {
        $caseDocuments = array($this->createCtsCaseDocument());
        $this->instance->setCaseDocuments($caseDocuments);
        $this->assertEquals($caseDocuments, $this->instance->getCaseDocuments());
        $caseDocumentsEmpty = array();
        $this->instance->setCaseDocuments($caseDocumentsEmpty);
        $this->assertEquals($caseDocumentsEmpty, $this->instance->getCaseDocuments());
    }

    public function testAddDocument()
    {
        $this->instance->addCaseDocument($this->createCtsCaseDocument());
        $this->assertCount(1, $this->instance->getCaseDocuments());
    }

    public function testGetNodeId()
    {
        $this->instance->setId('workspace://store/12345');
        $this->assertEquals('12345', $this->instance->getNodeId());
    }

    public function testGetUrn()
    {
        $this->assertEquals('', $this->instance->getUrn());
        $this->instance->setCorrespondenceType('TEST');
        $this->instance->setUrnSuffix('');
        $this->assertEquals('', $this->instance->getUrn());
        $this->instance->setCorrespondenceType('');
        $this->instance->setUrnSuffix('1234');
        $this->assertEquals('', $this->instance->getUrn());
        $this->instance->setCorrespondenceType('TEST');
        $this->instance->setUrnSuffix('1234');
        $this->assertEquals('TEST/1234', $this->instance->getUrn());
    }

    public function testGetSetIsLinkedCaseTrue()
    {
        $this->instance->setIsLinkedCase('true');
        $this->assertEquals(true, $this->instance->getIsLinkedCase());
    }

    public function testGetSetIsLinkedCaseFalse()
    {
        $this->instance->setIsLinkedCase('false');
        $this->assertEquals(false, $this->instance->getIsLinkedCase());
    }

    public function testGetSetHrnsToLink()
    {
        $this->instance->setHrnsToLink('1234,5678');
        $this->assertEquals('1234,5678', $this->instance->getHrnsToLink());
    }

    public function testGetSetLinkedCases()
    {
        $linkedCases = array(
            $this->createLinkedCase('1234', 'MIN', '0000001/14'),
            $this->createLinkedCase('5678', 'TRO', '0000002/14')
        );
        $this->instance->setLinkedCases($linkedCases);
        $this->assertEquals($linkedCases, $this->instance->getLinkedCases());
    }

    public function testGetNextStateTransitions()
    {
        $this->assertEquals(null, $this->instance->getNextStateTransitions());
        $transition = new CtsCaseWorkflowTransition('Allocate', 'Next', false, 'Allocate for drafting', 'green');
        // @codingStandardsIgnoreStart
        $this->instance->setCaseWorkflowStatus('{"transitions":[{"label":"Allocate","value": "Next","manualAllocate": false,"allocateHeader": "Allocate for drafting","colour": "green"}]}', false);
        // @codingStandardsIgnoreEnd
        $this->assertEquals($transition, $this->instance->getNextStateTransitions()[0]);
    }

    public function testGetMultipleNextStateTransitions()
    {
        $this->assertEquals(null, $this->instance->getNextStateTransitions());
        $transition = new CtsCaseWorkflowTransition('Allocate', 'Next', false, 'Allocate for drafting', 'green');
        $second = new CtsCaseWorkflowTransition('Second', 'Dispatch', false, 'Dispatch test', 'green');
        // @codingStandardsIgnoreStart
        $this->instance->setCaseWorkflowStatus('{"transitions":[{"label":"Allocate","value": "Next","manualAllocate": false,"allocateHeader": "Allocate for drafting","colour": "green"}, '
                . '{"label":"Second","value": "Dispatch","manualAllocate": false,"allocateHeader": "Dispatch test","colour": "green"}]}', false);
        // @codingStandardsIgnoreEnd
        $this->assertEquals($transition, $this->instance->getNextStateTransitions()[0]);
        $this->assertEquals($second, $this->instance->getNextStateTransitions()[1]);
    }

    public function testSetCaseWorkflowStatusWithTransitions()
    {
        //@codingStandardsIgnoreStart
        $this->instance->setCaseWorkflowStatus('{"transitions":[{"label":"Allocate","value": "Next","manualAllocate": false,"allocateHeader": "Allocate for drafting","colour": "green"}]}', false);
        //@codingStandardsIgnoreEnd
        $this->assertEquals('Allocate', $this->instance->getCaseWorkflowStatus()->getTransitions()['Next']->getLabel());
        $this->assertEquals('Next', $this->instance->getCaseWorkflowStatus()->getTransitions()['Next']->getValue());
        //@codingStandardsIgnoreStart
        $this->assertEquals(false, $this->instance->getCaseWorkflowStatus()->getTransitions()['Next']->getManualAllocate());
        //@codingStandardsIgnoreEnd
        $this->assertEquals(
            'Allocate for drafting',
            $this->instance->getCaseWorkflowStatus()->getTransitions()['Next']->getAllocateHeader()
        );
        $this->assertEquals('green', $this->instance->getCaseWorkflowStatus()->getTransitions()['Next']->getColour());
    }

    public function testSetCaseWorkflowStatusWithTransitionsAndValidation()
    {
        //@codingStandardsIgnoreStart
        $this->instance->setCaseWorkflowStatus('{"transitions":[{"label":"Allocate","value": "Next","manualAllocate": false,"allocateHeader": "Allocate for drafting","colour": "green"}], "mandatoryFields":[{"name": "markupMinister", "message": "Markup minister field must be populated!"}]}', false);
        //@codingStandardsIgnoreEnd
        $this->assertEquals('Allocate', $this->instance->getCaseWorkflowStatus()->getTransitions()['Next']->getLabel());
        $this->assertEquals('Next', $this->instance->getCaseWorkflowStatus()->getTransitions()['Next']->getValue());
        //@codingStandardsIgnoreStart
        $this->assertEquals(false, $this->instance->getCaseWorkflowStatus()->getTransitions()['Next']->getManualAllocate());
        $this->assertEquals('markupMinister', $this->instance->getCaseWorkflowStatus()->getMandatoryFields()['markupMinister']->getName());
        $this->assertEquals('Markup minister field must be populated!', $this->instance->getCaseWorkflowStatus()->getMandatoryFields()['markupMinister']->getMessage());
        //@codingStandardsIgnoreEnd
    }

    public function testMultipleSetCaseWorkflowStatus()
    {
        //@codingStandardsIgnoreStart
        $this->instance->setCaseWorkflowStatus('{"transitions":[{"label":"Allocate","value": "Next","manualAllocate": false,"allocateHeader": "Allocate for drafting","colour": "green"},{"label":"Test","value": "Previous","manualAllocate": true,"allocateHeader": "Allocate test","colour": "blue"}]}', false);
        //@codingStandardsIgnoreEnd
        $this->assertEquals('Allocate', $this->instance->getCaseWorkflowStatus()->getTransitions()['Next']->getLabel());
        $this->assertEquals('Next', $this->instance->getCaseWorkflowStatus()->getTransitions()['Next']->getValue());
        //@codingStandardsIgnoreStart
        $this->assertEquals(false, $this->instance->getCaseWorkflowStatus()->getTransitions()['Next']->getManualAllocate());
        //@codingStandardsIgnoreEnd
        $this->assertEquals(
            'Allocate for drafting',
            $this->instance->getCaseWorkflowStatus()->getTransitions()['Next']->getAllocateHeader()
        );
        $this->assertEquals('green', $this->instance->getCaseWorkflowStatus()->getTransitions()['Next']->getColour());
        $this->assertEquals('Test', $this->instance->getCaseWorkflowStatus()->getTransitions()['Previous']->getLabel());
        //@codingStandardsIgnoreStart
        $this->assertEquals('Previous', $this->instance->getCaseWorkflowStatus()->getTransitions()['Previous']->getValue());
        $this->assertEquals(true, $this->instance->getCaseWorkflowStatus()->getTransitions()['Previous']->getManualAllocate());
        $this->assertEquals('Allocate test', $this->instance->getCaseWorkflowStatus()->getTransitions()['Previous']->getAllocateHeader());
        $this->assertEquals('blue', $this->instance->getCaseWorkflowStatus()->getTransitions()['Previous']->getColour());
        //@codingStandardsIgnoreEnd
    }

    public function testSetCaseWorkflowStatusWithBlankAllocateHeader()
    {
        //@codingStandardsIgnoreStart
        $this->instance->setCaseWorkflowStatus('{"transitions":[{"label":"Allocate","value": "Next","manualAllocate": false,"colour": "green"}]}', false);
        //@codingStandardsIgnoreEnd
        $this->assertEquals('Allocate', $this->instance->getCaseWorkflowStatus()->getTransitions()['Next']->getLabel());
        $this->assertEquals('Next', $this->instance->getCaseWorkflowStatus()->getTransitions()['Next']->getValue());
        //@codingStandardsIgnoreStart
        $this->assertEquals(false, $this->instance->getCaseWorkflowStatus()->getTransitions()['Next']->getManualAllocate());
        $this->assertEquals('', $this->instance->getCaseWorkflowStatus()->getTransitions()['Next']->getAllocateHeader());
        //@codingStandardsIgnoreEnd
        $this->assertEquals('green', $this->instance->getCaseWorkflowStatus()->getTransitions()['Next']->getColour());
    }

    public function testPqMandatoryFields()
    {
        //@codingStandardsIgnoreStart
        $this->instance->setCaseWorkflowStatus('{"transitions":[{"label":"Reallocate","value": "Reallocate","manualAllocate": true,"allocateHeader": "Reallocate","colour": "green"},{"label":"Allocate for draft","value": "Next","manualAllocate": true,"colour": "green","allocateHeader": "Allocate"}], "mandatoryFields": [{"name": "lordsMinister", "message": "Lord\'s Minister is required"}]}', false);
        //@codingStandardsIgnoreEnd
        $this->assertEquals(
            "lordsMinister",
            $this->instance->getCaseWorkflowStatus()->getMandatoryFields()['lordsMinister']->getName()
        );
        $this->assertEquals("Lord's Minister is required", $this->instance->getCaseWorkflowStatus()
        ->getMandatoryFields()
        ['lordsMinister']->getMessage());
    }

    public function testGetSetCaseMandatoryFields()
    {
        $seedJSON = '{"caseMandatoryValues": [' .
            '{"name": "channel","message": "Value is required"}, ' .
            '{"name": "hoCaseOfficer","message": "Value is required"}, ' .
            '{"name": "markupDecision","message": "Value is required"}, ' .
            '{"name": "markupUnit","message": "Value is required"}, ' .
            '{"name": "markupTopic","message": "Value is required"}, ' .
            '{"name": "pitLetterSentDate","message": "Value is required"}, ' .
            '{"name": "pitQualifiedExemptions","message": "Value is required"}, ' .
            '{"name": "exemptions","message": "Value is required"}' .
        ' ]}';

        $defaultRequired = "Value is required";

        $expectedArray = array(
            "channel"                   => $defaultRequired,
            "hoCaseOfficer"             => $defaultRequired,
            "markupDecision"            => $defaultRequired,
            "markupUnit"                => $defaultRequired,
            "markupTopic"               => $defaultRequired,
            "pitLetterSentDate"         => $defaultRequired,
            "pitQualifiedExemptions"    => $defaultRequired,
            "exemptions"                => $defaultRequired,
        );


        $this->assertEmpty($this->instance->getCaseMandatoryFields());
        $this->assertTrue($this->instance->setCaseMandatoryFields($seedJSON) instanceof CtsCase);

        $this->assertNotEmpty($this->instance->getCaseMandatoryFields());

        $this->assertEquals($expectedArray, $this->instance->getCaseMandatoryFields());

    }

    public function testGetSetCaseMandatoryFieldStatus()
    {
        $seedJSON = '{"cts:caseMandatoryFieldStatus": ["Draft"]}';

        $expectedArray = array(
            "Draft"
        );


        $this->assertEmpty($this->instance->getCaseMandatoryFieldStatus());
        $this->assertTrue($this->instance->setCaseMandatoryFieldStatus($seedJSON) instanceof CtsCase);

        $this->assertNotEmpty($this->instance->getCaseMandatoryFieldStatus());

        $this->assertEquals($expectedArray, $this->instance->getCaseMandatoryFieldStatus());

    }

    public function testGetSetCaseMandatoryFieldDependencies()
    {
        $seedJSON = '{"cts:caseMandatoryFieldDependencies": [' .
            '{"pitLetterSentDate": "pitExtension"}, ' .
            '{"pitQualifiedExemptions": "pitExtension"}' .
        ']}';

        $expectedArray = array(
            "pitLetterSentDate"         => "pitExtension",
            "pitQualifiedExemptions"    => "pitExtension",
        );

        $this->assertEmpty($this->instance->getCaseMandatoryFieldDependencies());
        $this->assertTrue($this->instance->setCaseMandatoryFieldDependencies($seedJSON) instanceof CtsCase);
        $this->assertNotEmpty($this->instance->getCaseMandatoryFieldDependencies());
        $this->assertEquals($expectedArray, $this->instance->getCaseMandatoryFieldDependencies());
    }
}

<?php

namespace HomeOffice\CtsBundle\Tests\Form\Type;

use HomeOffice\CtsBundle\Tests\Form\Type\CtsTypeTestCase as TypeTestCase;
use HomeOffice\CtsBundle\Form\Type\CtsDcuMinisterialCaseType;
use HomeOffice\AlfrescoApiBundle\Factory\CtsCaseFactory;
use HomeOffice\AlfrescoApiBundle\Entity\CtsCaseMinute;
use HomeOffice\AlfrescoApiBundle\Entity\CtsCaseDocument;
use HomeOffice\AlfrescoApiBundle\Service\CTSHelper;

class CtsDcuMinisterialCaseTypeTest extends TypeTestCase
{

    public function testSubmitValidData()
    {

        $formData = array(
            'caseStatus' => 'New',
            'caseTask' => 'Create case',
            'urn' => 'MIN/0000001/14',
            'correspondenceType' => 'MIN',
            'markupDecision' => 'FAQ',
            'ogdName' => 'Other department',
            'markupUnit' => 'UNIT 1',
            'markupTopic' => 'Every',
            'markupMinister' => null,
            'secondaryTopic' => 'Every',
            'hrnsToLink' => 'MIN/0000002/14, MIN/0000003/14',
            'caseResponseDeadline' => null,
            'channel' => 'Letter',
            'priority' => true,
            'advice' => true,
            'homeSecretaryReply' => false,
            'dateReceived' => null, // new \DateTime(),
            'dateOfLetter' => null, // new \DateTime(),
            'member' => 'Dennis Skinner',
            'mpRef' => 'REF/123',
            'replyToName' => 'Fred Flintsone',
            'replyToAddressLine1' => '1 High Street',
            'replyToAddressLine2' => 'Village',
            'replyToAddressLine3' => 'Town',
            'replyToPostcode' => 'SW1P 4DF',
            'replyToCountry' => 'United Kingdom',
            'replyToTelephone' => '01234 567890',
            'replyToEmail' => 'fred.flintstone@bedrock.com',
            'correspondentTitle' => 'Dr',
            'correspondentForename' => 'Bob',
            'correspondentSurname' => 'Hopkins',
            'correspondentAddressLine1' => '12 Station Road',
            'correspondentAddressLine2' => 'Town',
            'correspondentAddressLine3' => 'County',
            'correspondentPostcode' => 'AB12 3CD',
            'correspondentCountry' => 'United Kingdom',
            'correspondentTelephone' => '09876 543210',
            'correspondentEmail' => 'bob.hopkins@test.com',
            'replyToNumberTenCopy' => true,
        );

        $newData = array(
            'caseStatus' => 'New',
            'caseTask' => 'Create case',
            'urn' => 'MIN/0000001/14',
            'correspondenceType' => 'MIN',
            'markupDecision' => 'Policy response',
            'ogdName' => 'New department',
            'markupUnit' => 'UNIT 2',
            'markupTopic' => 'Every',
            'markupMinister' => null,
            'secondaryTopic' => 'Every',
            'hrnsToLink' => 'MIN/0000004/14, MIN/0000005/14',
            'caseResponseDeadline' => null,
            'channel' => 'Email',
            'priority' => true,
            'advice' => true,
            'homeSecretaryReply' => false,
            'dateReceived' => null, // new \DateTime(),
            'dateOfLetter' => null, // new \DateTime(),
            'member' => 'Dennis Skinner',
            'mpRef' => 'REF/456',
            'replyToName' => 'Wilma Flintsone',
            'replyToAddressLine1' => '2 High Street',
            'replyToAddressLine2' => 'Village 2',
            'replyToAddressLine3' => 'Town 2',
            'replyToPostcode' => 'SW2P 4DF',
            'replyToCountry' => 'United Kingdom',
            'replyToTelephone' => '07894 561230',
            'replyToEmail' => 'wilma.flintstone@bedrock.com',
            'correspondentTitle' => 'Mr',
            'correspondentForename' => 'Hop',
            'correspondentSurname' => 'Bopkins',
            'correspondentAddressLine1' => '34 Station Road',
            'correspondentAddressLine2' => 'Towbn 2',
            'correspondentAddressLine3' => 'County 2',
            'correspondentPostcode' => 'AB12 4CD',
            'correspondentCountry' => 'United Kingdom',
            'correspondentTelephone' => '01234 543210',
            'correspondentEmail' => 'hob.bopkins@test.com',
            'replyToNumberTenCopy' => false
        );

        $ctsListHandler = $this->getListHandlerMock();
        $ctsHelper = new CTSHelper('security.context', $ctsListHandler);

        $factory = new CtsCaseFactory('workspace', 'store', $ctsHelper);
        $ctsCase = $factory->build($formData, '\HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsDcuMinisterialCase');
        $ctsCaseMinute = new CtsCaseMinute();
        $ctsCaseDocument = new CtsCaseDocument('workspace', 'store');
        $ctsCase->setNewMinute($ctsCaseMinute);
        $ctsCase->setNewDocument($ctsCaseDocument);
        $ctsNewCase = $factory->build($newData, '\HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsDcuMinisterialCase');
        $ctsNewCase->setNewMinute($ctsCaseMinute);
        $ctsNewCase->setNewDocument($ctsCaseDocument);

        $type = new CtsDcuMinisterialCaseType('edit', $ctsListHandler, $ctsHelper);
        $type->setWorkspace('workspace');
        $type->setStore('store');
        $form = $this->factory->create($type, $ctsCase);

        $form->submit($newData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($ctsNewCase, $form->getData());
        $this->assertEquals($ctsCaseMinute, $form->getData()->getNewMinute());
        $this->assertEquals($ctsCaseDocument, $form->getData()->getNewDocument());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}

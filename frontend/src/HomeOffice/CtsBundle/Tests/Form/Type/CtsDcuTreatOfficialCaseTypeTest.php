<?php

namespace HomeOffice\CtsBundle\Tests\Form\Type;

use HomeOffice\CtsBundle\Tests\Form\Type\CtsTypeTestCase as TypeTestCase;
use HomeOffice\CtsBundle\Form\Type\CtsDcuTreatOfficialCaseType;
use HomeOffice\AlfrescoApiBundle\Factory\CtsCaseFactory;
use HomeOffice\AlfrescoApiBundle\Entity\CtsCaseMinute;
use HomeOffice\AlfrescoApiBundle\Entity\CtsCaseDocument;
use HomeOffice\AlfrescoApiBundle\Service\CTSHelper;

class CtsDcuTreatOfficialCaseTypeTest extends TypeTestCase
{

    public function testSubmitValidData()
    {

        $formData = array(
            'caseStatus' => 'New',
            'caseTask' => 'Create case',
            'urn' => 'TRO/0000001/14',
            'correspondenceType' => 'MIN',
            'markupDecision' => 'FAQ',
            'ogdName' => 'Other department',
            'markupUnit' => 'UNIT 1',
            'markupTopic' => 'Every',
            'markupMinister' => null,
            'secondaryTopic' => 'Every',
            'hrnsToLink' => 'TRO/0000002/14, TRO/0000003/14',
            'caseResponseDeadline' => null,
            'channel' => 'Letter',
            'priority' => true,
            'advice' => true,
            'dateReceived' => null, // new \DateTime(),
            'dateOfLetter' => null, // new \DateTime(),
            'mpRef' => 'REF/123',
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
        );

        $newData = array(
            'caseStatus' => 'New',
            'caseTask' => 'Create case',
            'urn' => 'TRO/0000001/14',
            'correspondenceType' => 'MIN',
            'markupDecision' => 'Policy response',
            'ogdName' => 'New department',
            'markupUnit' => 'UNIT 2',
            'markupTopic' => 'Every',
            'markupMinister' => null,
            'secondaryTopic' => 'Every',
            'hrnsToLink' => 'TRO/0000004/14, TRO/0000005/14',
            'caseResponseDeadline' => null,
            'channel' => 'Email',
            'priority' => true,
            'advice' => true,
            'dateReceived' => null, // new \DateTime(),
            'dateOfLetter' => null, // new \DateTime(),
            'mpRef' => 'REF/456',
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
        );

        $ctsListHandler = $this->getListHandlerMock();
        $ctsHelper = new CTSHelper('security.context', $ctsListHandler);

        $factory = new CtsCaseFactory('workspace', 'store', $ctsHelper);
        $ctsCase = $factory->build($formData, '\HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsDcuTreatOfficialCase');
        $ctsCaseMinute = new CtsCaseMinute();
        $ctsCaseDocument = new CtsCaseDocument('workspace', 'store');
        $ctsCase->setNewMinute($ctsCaseMinute);
        $ctsCase->setNewDocument($ctsCaseDocument);
        $ctsNewCase = $factory->build($newData, '\HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsDcuTreatOfficialCase');
        $ctsNewCase->setNewMinute($ctsCaseMinute);
        $ctsNewCase->setNewDocument($ctsCaseDocument);

        $type = new CtsDcuTreatOfficialCaseType('edit', $ctsListHandler, $ctsHelper);
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

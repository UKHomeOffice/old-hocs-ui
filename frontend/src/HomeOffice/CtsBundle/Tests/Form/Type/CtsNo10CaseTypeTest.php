<?php

namespace HomeOffice\CtsBundle\Tests\Form\Type;

use HomeOffice\CtsBundle\Tests\Form\Type\CtsTypeTestCase as TypeTestCase;
use HomeOffice\CtsBundle\Form\Type\CtsNo10CaseType;
use HomeOffice\AlfrescoApiBundle\Factory\CtsCaseFactory;
use HomeOffice\AlfrescoApiBundle\Entity\CtsCaseMinute;
use HomeOffice\AlfrescoApiBundle\Entity\CtsCaseDocument;
use HomeOffice\AlfrescoApiBundle\Service\CTSHelper;

class CtsNo10CaseTypeTest extends TypeTestCase
{

    public function testSubmitValidData()
    {
        $formData = array(
            'caseStatus' => 'New',
            'caseTask' => 'Create case',
            'urn' => 'MIN/0000001/14',
            'correspondenceType' => 'DTEN',
            'markupDecision' => 'FAQ',
            'markupUnit' => 'UNIT 1',
            'markupTopic' => 'Every',
            'markupMinister' => null,
            'hrnsToLink' => 'TEN/0000002/14, TEN/0000003/14',
            'caseResponseDeadline' => null,
            'channel' => 'Letter',
            'priority' => true,
            'advice' => true,
            'dateReceived' => null, // new \DateTime(),
            'dateOfLetter' => null, // new \DateTime(),
            'member' => 'Dennis Skinner',
            'mpRef' => 'REF/123',
            'replyToAddressLine1' => '1 High Street',
            'replyToAddressLine2' => 'Village',
            'replyToAddressLine3' => 'Town',
            'replyToPostcode' => 'SW1P 4DF',
            'replyToCountry' => 'United Kingdom',
            'replyToTelephone' => '01234 567890',
            'replyToEmail' => 'fred.flintstone@bedrock.com',
        );

        $newData = array(
            'caseStatus' => 'New',
            'caseTask' => 'Create case',
            'urn' => 'MIN/0000001/14',
            'correspondenceType' => 'DTEN',
            'markupDecision' => 'Policy response',
            'markupUnit' => 'UNIT 2',
            'markupTopic' => 'Every',
            'markupMinister' => null,
            'hrnsToLink' => 'TEN/0000002/14, TEN/0000003/14',
            'caseResponseDeadline' => null,
            'channel' => 'Email',
            'priority' => true,
            'advice' => true,
            'dateReceived' => null, // new \DateTime(),
            'dateOfLetter' => null, // new \DateTime(),
            'member' => 'Dennis Skinner',
            'mpRef' => 'REF/456',
            'replyToAddressLine1' => '2 High Street',
            'replyToAddressLine2' => 'Village 2',
            'replyToAddressLine3' => 'Town 2',
            'replyToPostcode' => 'SW2P 4DF',
            'replyToCountry' => 'United Kingdom',
            'replyToTelephone' => '07894 561230',
            'replyToEmail' => 'wilma.flintstone@bedrock.com'
        );

        $ctsListHandler = $this->getListHandlerMock();
        $ctsHelper = new CTSHelper('security.context', $ctsListHandler);

        $factory = new CtsCaseFactory('workspace', 'store', $ctsHelper);
        $ctsCase = $factory->build($formData, '\HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsNo10Case');
        $ctsCaseMinute = new CtsCaseMinute();
        $ctsCaseDocument = new CtsCaseDocument('workspace', 'store');
        $ctsCase->setNewMinute($ctsCaseMinute);
        $ctsCase->setNewDocument($ctsCaseDocument);
        $ctsNewCase = $factory->build($newData, '\HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsNo10Case');
        $ctsNewCase->setNewMinute($ctsCaseMinute);
        $ctsNewCase->setNewDocument($ctsCaseDocument);

        $type = new CtsNo10CaseType('edit', $ctsListHandler, $ctsHelper);
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

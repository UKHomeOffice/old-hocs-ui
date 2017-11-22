<?php

namespace HomeOffice\CtsBundle\Tests\Form\Type;

use HomeOffice\CtsBundle\Tests\Form\Type\CtsTypeTestCase as TypeTestCase;
use HomeOffice\CtsBundle\Form\Type\CtsPqCaseType;
use HomeOffice\AlfrescoApiBundle\Factory\CtsCaseFactory;
use HomeOffice\AlfrescoApiBundle\Entity\CtsCaseMinute;
use HomeOffice\AlfrescoApiBundle\Entity\CtsCaseDocument;
use HomeOffice\AlfrescoApiBundle\Service\CTSHelper;

class CtsPqCaseTypeTest extends TypeTestCase
{

    public function testSubmitValidData()
    {
        $formData = array(
            'caseStatus' => 'New',
            'caseTask' => 'Create case',
            'urn' => 'LPQ/0000001/14',
            'correspondenceType' => 'LPQ',
            'markupDecision' => 'Allocate to policy unit',
            'ogdName' => 'Other department',
            'markupUnit' => 'UNIT 1',
            'markupTopic' => 'Every',
            'markupMinister' => null,
            'hrnsToLink' => 'LPQ/0000002/14, LPQ/0000003/14',
            'caseResponseDeadline' => null,
            'uin' => '1234',
            'opDate' => null,
            'woDate' => null,
            'questionNumber' => '1',
            'questionText' => "Who's the daddy?",
            'answeringMinister' => null,
            'answeringMinisterId' => "123",
            'receivedType' => 'Direct',
            'draftResponseTarget' => null,
            'member' => 'Dennis Skinner',
            'constituency' => 'London',
            'party' => 'Green',
            'signedByHomeSec' => true,
            'reviewedByPermSec' => true,
            'reviewedBySpads' => true,
            'uinsToGroup' => '123,456'
        );
     
        $newData = array(
            'caseStatus' => 'New',
            'caseTask' => 'Create case',
            'urn' => 'LPQ/0000001/14',
            'correspondenceType' => 'LPQ',
            'markupDecision' => 'Refer to OGD',
            'ogdName' => 'New department',
            'markupUnit' => 'UNIT 2',
            'markupTopic' => 'Every',
            'markupMinister' => null,
            'hrnsToLink' => 'LPQ/0000003/14,LPQ/0000004/14',
            'caseResponseDeadline' => null,
            'uin' => '1234',
            'opDate' => null,
            'woDate' => null,
            'questionNumber' => '2',
            'questionText' => "Who's the mummy?",
            'answeringMinister' => null,
            'answeringMinisterId' => "456",
            'receivedType' => 'Transfer',
            'draftResponseTarget' => null,
            'member' => 'Dennis Skinner',
            'constituency' => 'Bedford',
            'party' => 'Monster',
            'signedByHomeSec' => false,
            'reviewedByPermSec' => false,
            'reviewedBySpads' => false,
            'uinsToGroup' => '456,789',
        );
     
        $ctsListHandler = $this->getListHandlerMock();
        $ctsHelper = new CTSHelper('security.context', $ctsListHandler);
     
        $factory = new CtsCaseFactory('workspace', 'store', $ctsHelper);
        $ctsCase = $factory->build($formData, '\HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsPqCase');
        $ctsCaseMinute = new CtsCaseMinute();
        $ctsCaseDocument = new CtsCaseDocument('workspace', 'store');
        $ctsCase->setNewMinute($ctsCaseMinute);
        $ctsCase->setNewDocument($ctsCaseDocument);
        $ctsNewCase = $factory->build($newData, '\HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsPqCase');
        $ctsNewCase->setNewMinute($ctsCaseMinute);
        $ctsNewCase->setNewDocument($ctsCaseDocument);

        $type = new CtsPqCaseType('edit', $ctsListHandler, $ctsHelper);
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

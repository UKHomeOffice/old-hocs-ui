<?php

namespace HomeOffice\CtsBundle\Tests\Form\Type;

use HomeOffice\CtsBundle\Tests\Form\Type\CtsTypeTestCase as TypeTestCase;
use HomeOffice\CtsBundle\Form\Type\CtsHmpoComCaseType;
use HomeOffice\AlfrescoApiBundle\Factory\CtsCaseFactory;
use HomeOffice\AlfrescoApiBundle\Entity\CtsCaseMinute;
use HomeOffice\AlfrescoApiBundle\Entity\CtsCaseDocument;
use HomeOffice\AlfrescoApiBundle\Service\CTSHelper;

class CtsHmpoComCaseTypeTest extends TypeTestCase
{

    public function testSubmitValidData()
    {
        $formData = array(
            'caseStatus' => 'New',
            'caseTask' => 'Create case',
            'urn' => 'COM/0000001/14',
            'correspondenceType' => 'COM',
            'passportNumber' => 'QWERT1234567890',
            'applicationNumber' => '1234567890QWERT',
            'markupDecision' => 'Refer to OGD',
            'ogdName' => 'Other department',
            'markupUnit' => 'UNIT 1',
            'markupTopic' => 'Every',
            'markupMinister' => null,
            'secondaryTopic' => 'Every',
            'hrnsToLink' => 'COM/0000002/14, COM/0000003/14',
            'caseResponseDeadline' => null,
            'channel' => 'Letter',
            'dateReceived' => null, // new \DateTime(),
            'hmpoResponse' => null,
            'replyToCorrespondent' => false,
            'typeOfCorrespondent' => 'Applicant',
            'typeOfComplainant' => null,
            'typeOfRepresentative' => null,
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
            'replyToApplicant' => false,
            'applicantTitle' => 'Mr',
            'applicantForename' => 'Hop',
            'applicantSurname' => 'Bopkins',
            'applicantAddressLine1' => '34 Station Road',
            'applicantAddressLine2' => 'Towbn 2',
            'applicantAddressLine3' => 'County 2',
            'applicantPostcode' => 'AB12 4CD',
            'applicantCountry' => 'United Kingdom',
            'applicantTelephone' => '01234 543210',
            'applicantEmail' => 'hob.bopkins@test.com',
            'replyToComplainant' => false,
            'complainantTitle' => 'Mr',
            'complainantForename' => 'Hop',
            'complainantSurname' => 'Bopkins',
            'complainantAddressLine1' => '34 Station Road',
            'complainantAddressLine2' => 'Town 2',
            'complainantAddressLine3' => 'County 2',
            'complainantPostcode' => 'AB12 4CD',
            'complainantCountry' => 'United Kingdom',
            'complainantTelephone' => '01234 543210',
            'complainantEmail' => 'hob.bopkins@test.com',
            'hmpoRefundDecision' => 'Reimbursement',
            'hmpoRefundAmount' => '£5.50',
            'hmpoComplaintOutcome' => 'Dismissed',
        );

        $newData = array(
            'caseStatus' => 'New',
            'caseTask' => 'Create case',
            'urn' => 'COM/0000001/14',
            'correspondenceType' => 'COM',
            'passportNumber' => '1234567890QWERT',
            'applicationNumber' => 'QWERT1234567890',
            'markupDecision' => 'Phone call resolution',
            'ogdName' => 'New department',
            'markupUnit' => 'UNIT 2',
            'markupTopic' => 'Every',
            'markupMinister' => null,
            'secondaryTopic' => 'Every',
            'hrnsToLink' => 'COM/0000004/14, COM/0000005/14',
            'caseResponseDeadline' => null,
            'channel' => 'Email',
            'dateReceived' => null, // new \DateTime(),
            'hmpoResponse' => null,
            'replyToCorrespondent' => true,
            'typeOfCorrespondent' => 'Complainant',
            'typeOfComplainant' => null,
            'typeOfRepresentative' => null,
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
            'replyToApplicant' => true,
            'applicantTitle' => 'Mr',
            'applicantForename' => 'Hop',
            'applicantSurname' => 'Bopkins',
            'applicantAddressLine1' => '34 Station Road',
            'applicantAddressLine2' => 'Town 2',
            'applicantAddressLine3' => 'County 2',
            'applicantPostcode' => 'AB12 4CD',
            'applicantCountry' => 'United Kingdom',
            'applicantTelephone' => '01234 543210',
            'applicantEmail' => 'hob.bopkins@test.com',
            'replyToComplainant' => true,
            'complainantTitle' => 'Mr',
            'complainantForename' => 'Hop',
            'complainantSurname' => 'Bopkins',
            'complainantAddressLine1' => '34 Station Road',
            'complainantAddressLine2' => 'Towbn 2',
            'complainantAddressLine3' => 'County 2',
            'complainantPostcode' => 'AB12 4CD',
            'complainantCountry' => 'United Kingdom',
            'complainantTelephone' => '01234 543210',
            'complainantEmail' => 'hob.bopkins@test.com',
            'hmpoRefundDecision' => 'Refund',
            'hmpoRefundAmount' => '£10.10',
            'hmpoComplaintOutcome' => 'Upheld',
        );

        $ctsListHandler = $this->getListHandlerMock();
        $ctsHelper = new CTSHelper('security.context', $ctsListHandler);

        $factory = new CtsCaseFactory('workspace', 'store', $ctsHelper);
        $ctsCase = $factory->build($formData, '\HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsHmpoComCase');
        $ctsCaseMinute = new CtsCaseMinute();
        $ctsCaseDocument = new CtsCaseDocument('workspace', 'store');
        $ctsCase->setNewMinute($ctsCaseMinute);
        $ctsCase->setNewDocument($ctsCaseDocument);
        $ctsNewCase = $factory->build($newData, '\HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsHmpoComCase');
        $ctsNewCase->setNewMinute($ctsCaseMinute);
        $ctsNewCase->setNewDocument($ctsCaseDocument);

        $type = new CtsHmpoComCaseType('edit', $ctsListHandler, $ctsHelper);
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

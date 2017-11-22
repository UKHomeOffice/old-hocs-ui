<?php

namespace HomeOffice\ProcessManagerAuthenticatorBundle\Tests\Controller;

use HomeOffice\CtsBundle\Tests\Controller\CtsWebTestCase as WebTestCase;

class StatusControllerTest extends WebTestCase
{

    private function setupAndTestAllocate($caseTypeClass, $caseType, $allocateTo)
    {
        $this->client = $this->mockSuccessCrawler();

        $this->client
            ->expects($this->any())
            ->method('text')
            ->willReturn('Home Office Correspondence Service');
     
        $ctsCase = new $caseTypeClass('workspace', 'SpacesStore');
        $ctsCase->setId('workspace://SpacesStore/1234');
        $ctsCase->setCanAssignUser('true');

        $this->mockCtsCaseRepo('getCase', $ctsCase);
        $this->mockCtsCaseRepo('update', true);
        $this->mockCtsCaseDocumentRepo('getDocumentsForCase', array());
        $this->mockCtsCaseDocumentTemplateRepo('getDocumentTemplates', array());
        $this->mockCtsCaseMinuteRepo('getMinutesForCase', array());
        $this->mockPersonRepo('findAllUsers', array());
        $this->mockCtsListsRepo('getCsvList', $this->list);
        $this->mockCtsListsRepo('getDataList', $this->dataList);
        $this->mockCtsListsRepo('getUnitsAndTeams', $this->createUnitWithTeam());
        $this->mockListsRepo('getPeopleFromGroup', array());
        $this->mockCtsWorkflowRepo('updateWorkflow', '[]');
        $this->mockCtsCaseRepo('getTodoQueue', array($ctsCase));

        $crawler = $this->client->request(
            'POST',
            '/cts/status/allocate',
            array( 'allocation' => array(
                'id' => 'workspace://SpacesStore/1234',
                'correspondenceType' => $caseType,
                'assignedUser' => 'admin',
                'assignedUnit' => 'unitTest',
                'assignedTeam' => 'teamTest',
                'allocateTo' => $allocateTo,
                'statusChange' => null
                )
            )
        );
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("Home Office Correspondence Service", $crawler->filter('div#intro h1')->text());
    }

    public function testAllocateDcu()
    {
        $this->setupAndTestAllocate(
            'HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsDcuMinisterialCase',
            'MIN',
            'Colleague'
        );
    }

    public function testAllocateDcuToMe()
    {
        $this->setupAndTestAllocate(
            'HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsDcuMinisterialCase',
            'MIN',
            'Me'
        );
    }

    public function testAllocatePq()
    {
        $this->setupAndTestAllocate(
            'HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsPqCase',
            'NPQ',
            'Colleague'
        );
    }

    public function testAllocatePqToMe()
    {
        $this->setupAndTestAllocate(
            'HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsPqCase',
            'NPQ',
            'Me'
        );
    }
}

<?php

namespace HomeOffice\ProcessManagerAuthenticatorBundle\Tests\Controller;

use HomeOffice\AlfrescoApiBundle\Entity\Team;
use HomeOffice\AlfrescoApiBundle\Entity\Unit;
use HomeOffice\CtsBundle\Tests\Controller\CtsWebTestCase as WebTestCase;

class BulkCaseControllerTest extends WebTestCase
{

    public function setUp()
    {
        parent::setUp();

        $team = new Team();
        $team->setAuthorityName('TEAM_NAME');
        $team->setDisplayName('Team name');

        $unit = new Unit();
        $unit->setAuthorityName('GROUP_NAME');
        $unit->setDisplayName('Group name');
        $unit->setTeams(array($team));

        $this->mockListsRepo('getUnitsAndTeams', array($unit));
    }

    public function testCreateAction()
    {
        $this->client = $this->mockSuccessCrawler();

        $this->client
            ->expects($this->any())
            ->method('text')
            ->will(
                $this->onConsecutiveCalls(
                    "Home Office Correspondence Service",
                    "Bulk create case"
                )
            );

        $crawler = $this->client->request('GET', '/cts/cases/bulk/create');
        $response = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("Home Office Correspondence Service", $crawler->filter('div#intro h1')->text());
        $this->assertEquals("Bulk create case", $crawler->filter('div.panel h2')->text());
    }

    public function testFailuresAction()
    {
        $this->mockBulkDocumentRepo('getBulkCreateErrors', array());

        $this->client = $this->mockSuccessCrawler();

        $this->client
            ->expects($this->any())
            ->method('text')
            ->will(
                $this->onConsecutiveCalls(
                    "Home Office Correspondence Service",
                    "Auto create case failures"
                )
            );

        $crawler = $this->client->request('GET', '/cts/cases/bulk/failures');
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("Home Office Correspondence Service", $crawler->filter('div#intro h1')->text());
        $this->assertEquals("Auto create case failures", $crawler->filter('div#outer h2')->text());
    }

    public function testDelete()
    {
        $this->mockBulkDocumentRepo('delete', true);
        $this->mockBulkDocumentRepo('getBulkCreateErrors', array());

        $this->client = $this->mockSuccessCrawler();

        $this->client
            ->expects($this->any())
            ->method('text')
            ->will(
                $this->onConsecutiveCalls(
                    "Home Office Correspondence Service",
                    "Auto create case failures"
                )
            );

        $crawler = $this->client->request(
            'DELETE',
            '/cts/cases/bulk/delete',
            array('del_456' => array( 'id' => 'workspace://SpacesStore/456' ))
        );
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("Home Office Correspondence Service", $crawler->filter('div#intro h1')->text());
        $this->assertEquals("Auto create case failures", $crawler->filter('div#outer h2')->text());
    }

    public function testUploadInvalid()
    {
        $this->client = $this->mockFailureCrawler();

        $this->client
            ->expects($this->any())
            ->method('getContent')
            ->willReturn("Form not valid, please ensure you have selected the case type and added files.");

        $this->client->request(
            'POST',
            '/cts/cases/bulk/upload',
            array('ctsBulkCaseEntry' => array( 'correspondenceType' => '' ))
        );

        $response = $this->client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());

        $this->assertEquals(
            'Form not valid, please ensure you have selected the case type and added files.',
            $response->getContent()
        );

    }
}

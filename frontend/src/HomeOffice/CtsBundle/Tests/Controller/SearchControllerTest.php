<?php

namespace HomeOffice\ProcessManagerAuthenticatorBundle\Tests\Controller;

use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsCase;
use HomeOffice\CtsBundle\Tests\Controller\CtsWebTestCase as WebTestCase;
use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsDcuMinisterialCase;

class SearchControllerTest extends WebTestCase
{

    private function setupAndTestGlobalSearch($caseTypeClass, $caseType)
    {

        $this->client = $this->mockSuccessCrawler();

        $this->client
            ->expects($this->any())
            ->method('count')
            ->will($this->onConsecutiveCalls(1, 0));

        $crawler = $this->client->request(
            'POST',
            '/cts/globalSearch',
            array('gs' => array('searchButton' => ''))
        );

        $response = $crawler->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $urn = $caseType.'/0000001/14';
        $this->assertEquals(1, $crawler->filter('html:contains("'.$urn.'")')->count());
        $this->assertEquals(0, $crawler->filter('html:contains("No results found.")')->count());
    }

    public function testGlobalSearchWithDcuResults()
    {
        $this->setupAndTestGlobalSearch('HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsDcuMinisterialCase', 'MIN');
    }

    public function testGlobalSearchWithPqResults()
    {
        $this->setupAndTestGlobalSearch('HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsPqCase', 'LPQ');
    }

    public function testGlobalSearchNoResults()
    {
        $this->client = $this->mockSuccessCrawler();

        $this->client
            ->expects($this->any())
            ->method('count')
            ->willReturn(1);

        $crawler = $this->client->request(
            'POST',
            '/cts/globalSearch',
            array('gs' => array())
        );

        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(1, $crawler->filter('html:contains("No results found.")')->count());
    }

    public function testCorrespondentSearch()
    {
        $this->client = $this->mockSuccessCrawler();
        $this->client
            ->expects($this->any())
            ->method('count')
            ->willReturn(0);

        $crawler = $this->client->request(
            'GET',
            '/cts/correspondentSearch?correspondentSearchByField[searchButton]='
        );

        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(0, $crawler->filter('html:contains("No results found.")')->count());
    }
}

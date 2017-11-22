<?php

namespace HomeOffice\CtsBundle\Tests\Controller;

use HomeOffice\CtsBundle\Tests\Controller\CtsWebTestCase as WebTestCase;
use HomeOffice\AlfrescoApiBundle\Entity\CtsTsoFeed;
use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsDcuMinisterialCase;

class CaseControllerTest extends WebTestCase
{

    public function testCreateCase()
    {
        $this->client = $this->mockSuccessCrawler();

        $this->client
            ->expects($this->any())
            ->method('text')
            ->willReturn('Create case');

        $crawler = $this->client->request('GET', '/cts/cases/createSimple');
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("Create case", $crawler->filter('h2')->text());
    }

    private function setupAndTestCreateForm($caseType)
    {
        $this->client = $this->mockSuccessCrawler();

        $this->client
            ->expects($this->any())
            ->method('count')
            ->willReturn(1);

        $crawler = $this->client->request(
            'POST',
            '/cts/cases/createSimple',
            array('ctsCaseEntry' => array( 'correspondenceType' => $caseType, 'oldCorrespondenceType' => $caseType ))
        );

        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        if ($caseType == 'OPQ' || $caseType == 'LPQ' || $caseType == 'NPQ') {
            $this->assertEquals(1, $crawler->filter('html:contains("UIN ref")')->count());
            $this->assertEquals(1, $crawler->filter('html:contains("Deadline")')->count());
            $this->assertEquals(1, $crawler->filter('html:contains("Order Paper date")')->count());
        } elseif ($caseType == 'DTEN') {
            $this->assertEquals(1, $crawler->filter('html:contains("Original document")')->count());
            $this->assertEquals(1, $crawler->filter('html:contains("Deadline")')->count());
        } else {
            $this->assertEquals(1, $crawler->filter('html:contains("Date received")')->count());
            $this->assertEquals(1, $crawler->filter('html:contains("Original document")')->count());
        }
    }

    public function testCreateCaseDcuMin()
    {
        $this->setupAndTestCreateForm('MIN');
    }

    public function testCreateCaseDcuTro()
    {
        $this->setupAndTestCreateForm('TRO');
    }

    public function testCreateCaseDcuTen()
    {
        $this->setupAndTestCreateForm('DTEN');
    }

    public function testCreateCaseUkviTen()
    {
        $this->setupAndTestCreateForm('UTEN');
    }

    public function testCreateCasePq()
    {
        $this->setupAndTestCreateForm('OPQ');
    }

    public function testCreateCaseUkviBRef()
    {
        $this->setupAndTestCreateForm('IMCB');
    }

    public function testCreateCaseUkviMRef()
    {
        $this->setupAndTestCreateForm('IMCM');
    }

    public function testCreateCaseFoi()
    {
        $this->setupAndTestCreateForm('FOI');
    }

    public function testCreateCaseHmpoCom()
    {
        $this->setupAndTestCreateForm('COM');
    }

    public function testCreateCaseHmpoGen()
    {
        $this->setupAndTestCreateForm('GEN');
    }

    private function setupAndTestViewCase($caseTypeClass, $caseType)
    {
        $this->client = $this->mockSuccessCrawler();
        $this->client
            ->expects($this->any())
            ->method('text')
            ->willReturn('Home Office Correspondence Service');

        $crawler = $this->client->request('GET', '/cts/cases/view/1234');
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("Home Office Correspondence Service", $crawler->filter('div#intro h1')->text());
    }

    public function testViewDcuMinCase()
    {
        $this->setupAndTestViewCase('HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsDcuMinisterialCase', 'MIN');
    }

    public function testViewDcuTroCase()
    {
        $this->setupAndTestViewCase('HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsDcuTreatOfficialCase', 'TRO');
    }

    public function testViewDcuTenCase()
    {
        $this->setupAndTestViewCase('HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsNo10Case', 'DTEN');
    }

    public function testViewUkviTenCase()
    {
        $this->setupAndTestViewCase('HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsNo10Case', 'UTEN');
    }

    public function testViewPqCase()
    {
        $this->setupAndTestViewCase('HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsPqCase', 'NPQ');
    }

    public function testViewUkviBRefCase()
    {
        $this->setupAndTestViewCase('HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsUkviCase', 'IMCB');
    }

    public function testViewUkviMRefCase()
    {
        $this->setupAndTestViewCase('HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsUkviCase', 'IMCM');
    }

    public function testViewFoiCase()
    {
        $this->setupAndTestViewCase('HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsFoiCase', 'FOI');
    }

    public function testViewHmpoComCase()
    {
        $this->setupAndTestViewCase('HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsHmpoComCase', 'COM');
    }

    public function testViewHmpoGenCase()
    {
        $this->setupAndTestViewCase('HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsHmpoGenCase', 'GEN');
    }

    private function setupAndTestEditCase($caseTypeClass, $caseType)
    {
        $this->client = $this->mockSuccessCrawler();
        $this->client
            ->expects($this->any())
            ->method('text')
            ->willReturn('Home Office Correspondence Service');

        $crawler = $this->client->request('GET', '/cts/cases/edit/1234');
        $response = $this->client->getResponse();


        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("Home Office Correspondence Service", $crawler->filter('div#intro h1')->text());
    }

    public function testEditDcuMinCase()
    {
        $this->setupAndTestEditCase('HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsDcuMinisterialCase', 'MIN');
    }

    public function testEditDcuTroCase()
    {
        $this->setupAndTestEditCase('HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsDcuTreatOfficialCase', 'TRO');
    }

    public function testEditDcuTenCase()
    {
        $this->setupAndTestEditCase('HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsNo10Case', 'DTEN');
    }

    public function testEditUkviTenCase()
    {
        $this->setupAndTestEditCase('HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsNo10Case', 'UTEN');
    }

    public function testEditPqCase()
    {
        $this->setupAndTestEditCase('HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsPqCase', 'OPQ');
    }

    public function testEditUkviBRefCase()
    {
        $this->setupAndTestEditCase('HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsUkviCase', 'IMCB');
    }

    public function testEditUkviMRefCase()
    {
        $this->setupAndTestEditCase('HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsUkviCase', 'IMCM');
    }

    public function testEditFoiCase()
    {
        $this->setupAndTestEditCase('HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsFoiCase', 'FOI');
    }

    public function testEditHmpoComCase()
    {
        $this->setupAndTestEditCase('HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsHmpoComCase', 'COM');
    }

    public function testEditHmpoGenCase()
    {
        $this->setupAndTestEditCase('HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsHmpoGenCase', 'GEN');
    }

    private function setupAndTestEditCaseNoPermissions($caseTypeClass, $caseType)
    {
        $this->client = $this->mockSuccessCrawler();

        $this->client
            ->expects($this->any())
            ->method('text')
            ->willReturn('Home Office Correspondence Service');

        $this->client
            ->expects($this->once())
            ->method('count')
            ->willReturn(1);

        $crawler = $this->client->request('GET', '/cts/cases/edit/1234');
        $response = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("Home Office Correspondence Service", $crawler->filter('div#intro h1')->text());
        $this->assertEquals(
            1,
            $crawler->filter('html:contains("You do not have permission to edit this case")')->count()
        );
    }

    public function testEditDcuMinCaseNoUpdatePermissions()
    {
        //@codingStandardsIgnoreStart
        $this->setupAndTestEditCaseNoPermissions('HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsDcuMinisterialCase', 'MIN');
        //@codingStandardsIgnoreEnd
    }

    public function testEditDcuTroCaseNoUpdatePermissions()
    {
        //@codingStandardsIgnoreStart
        $this->setupAndTestEditCaseNoPermissions('HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsDcuTreatOfficialCase', 'TRO');
        //@codingStandardsIgnoreEnd
    }

    public function testEditDcuTenCaseNoUpdatePermissions()
    {
        $this->setupAndTestEditCaseNoPermissions('HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsNo10Case', 'DTEN');
    }

    public function testEditUkviTenCaseNoUpdatePermissions()
    {
        $this->setupAndTestEditCaseNoPermissions('HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsNo10Case', 'UTEN');
    }

    public function testEditPqCaseNoUpdatePermissions()
    {
        $this->setupAndTestEditCaseNoPermissions('HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsPqCase', 'NPQ');
    }

    public function testEditUkviBRefCaseNoUpdatePermissions()
    {
        $this->setupAndTestEditCaseNoPermissions('HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsUkviCase', 'IMCB');
    }

    public function testEditUkviMRefCaseNoUpdatePermissions()
    {
        $this->setupAndTestEditCaseNoPermissions('HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsUkviCase', 'IMCM');
    }

    public function testEditFoiCaseNoUpdatePermissions()
    {
        $this->setupAndTestEditCaseNoPermissions('HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsFoiCase', 'FOI');
    }

    public function testEditHmpoComCaseNoUpdatePermissions()
    {
        $this->setupAndTestEditCaseNoPermissions('HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsHmpoComCase', 'COM');
    }

    public function testEditHmpoGenCaseNoUpdatePermissions()
    {
        $this->setupAndTestEditCaseNoPermissions('HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsHmpoGenCase', 'GEN');
    }

    private function setupAndTestEditCaseNoAllocatePermissions($caseTypeClass, $caseType)
    {

        $this->client = $this->mockSuccessCrawler();

        $this->client
            ->expects($this->any())
            ->method('text')
            ->willReturn('Home Office Correspondence Service');

        $this->client
            ->expects($this->once())
            ->method('count')
            ->willReturn(1);

        $crawler = $this->client->request('GET', '/cts/cases/view/1234?actionType=allocate');
        $response = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("Home Office Correspondence Service", $crawler->filter('div#intro h1')->text());
        //@codingStandardsIgnoreStart
        $this->assertEquals(1, $crawler->filter('html:contains("You do not have permission to allocate this case")')->count());
        //@codingStandardsIgnoreEnd
    }

    public function testEditDcuMinCaseNoAllocatePermissions()
    {
        //@codingStandardsIgnoreStart
        $this->setupAndTestEditCaseNoAllocatePermissions('HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsDcuMinisterialCase', 'MIN');
        //@codingStandardsIgnoreEnd
    }

    public function testEditDcuTroCaseNoAllocatePermissions()
    {
        //@codingStandardsIgnoreStart
        $this->setupAndTestEditCaseNoAllocatePermissions('HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsDcuTreatOfficialCase', 'TRO');
        //@codingStandardsIgnoreEnd
    }

    public function testEditDcuTenCaseNoAllocatePermissions()
    {
        //@codingStandardsIgnoreStart
        $this->setupAndTestEditCaseNoAllocatePermissions('HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsNo10Case', 'DTEN');
        //@codingStandardsIgnoreEnd
    }

    public function testEditUkviTenCaseNoAllocatePermissions()
    {
        //@codingStandardsIgnoreStart
        $this->setupAndTestEditCaseNoAllocatePermissions('HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsNo10Case', 'UTEN');
        //@codingStandardsIgnoreEnd
    }

    public function testEditPqCaseNoAllocatePermissions()
    {
        //@codingStandardsIgnoreStart
        $this->setupAndTestEditCaseNoAllocatePermissions('HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsPqCase', 'NPQ');
        //@codingStandardsIgnoreEnd
    }

    public function testEditUkviCaseNoAllocatePermissions()
    {
        //@codingStandardsIgnoreStart
        $this->setupAndTestEditCaseNoAllocatePermissions('HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsUkviCase', 'VOI');
        //@codingStandardsIgnoreEnd
    }

    public function testEditFoiCaseNoAllocatePermissions()
    {
        //@codingStandardsIgnoreStart
        $this->setupAndTestEditCaseNoAllocatePermissions('HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsFoiCase', 'FOI');
        //@codingStandardsIgnoreEnd
    }

    public function testEditHmpoComCaseNoAllocatePermissions()
    {
        //@codingStandardsIgnoreStart
        $this->setupAndTestEditCaseNoAllocatePermissions('HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsHmpoComCase', 'COM');
        //@codingStandardsIgnoreEnd
    }

    public function testEditHmpoGenCaseNoAllocatePermissions()
    {
        //@codingStandardsIgnoreStart
        $this->setupAndTestEditCaseNoAllocatePermissions('HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsHmpoGenCase', 'GEN');
        //@codingStandardsIgnoreEnd
    }

    public function testViewCaseWithTopicForStandardLine()
    {

        $this->client = $this->mockSuccessCrawler();

        $this->client
            ->expects($this->any())
            ->method('text')
            ->willReturn('Home Office Correspondence Service');

        $crawler = $this->client->request('GET', '/cts/cases/view/1234');
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("Home Office Correspondence Service", $crawler->filter('div#intro h1')->text());
    }

    public function testUploadFeeds()
    {
        $this->client = $this->mockSuccessCrawler();

        $ctsTsoFeed = new CtsTsoFeed();
        $this->mockCtsTsoFeedRepo('upload', $ctsTsoFeed);

        $this->client->request('GET', '/cts/cases/upload');
        $response = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
    }
}

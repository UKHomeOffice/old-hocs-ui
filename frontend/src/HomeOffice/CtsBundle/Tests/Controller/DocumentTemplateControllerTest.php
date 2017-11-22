<?php

namespace HomeOffice\ProcessManagerAuthenticatorBundle\Tests\Controller;

use HomeOffice\CtsBundle\Tests\Controller\CtsWebTestCase as WebTestCase;
use HomeOffice\AlfrescoApiBundle\Entity\CtsCaseDocumentTemplate;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use org\bovigo\vfs\vfsStream;

class DocumentTemplateControllerTest extends WebTestCase
{

    /**
     * @var  vfsStreamDirectory
     */
    private $tmp;

    private $fileExample;

    protected function setUp()
    {
        parent::setUp();
        $this->createVirtualFile();
    }

    private function createVirtualFile()
    {
        $this->tmp = vfsStream::setup('tmp');
        vfsStream::newFile('5678')->at($this->tmp)->setContent("test file contents");
        $this->fileExample = vfsStream::url('tmp/5678');
    }

    private function createCtsCaseDocumentTemplate()
    {
        $ctsCaseDocumentTemplate = new CtsCaseDocumentTemplate('workspace', 'store');
        $ctsCaseDocumentTemplate->setId('workspace://store/5678');
        $ctsCaseDocumentTemplate->setAppliesToCorrespondenceType('MIN');
        $ctsCaseDocumentTemplate->setTemplateName('Test template name');
        $ctsCaseDocumentTemplate->setName('testFile.txt');
        $ctsCaseDocumentTemplate->setMimeType('text/plain');
        $ctsCaseDocumentTemplate->setFile(new UploadedFile($this->fileExample, '5678', null, null, null, true));
        return $ctsCaseDocumentTemplate;
    }

    public function testManage()
    {

        $this->client = $this->mockSuccessCrawler();

        $this->client
            ->expects($this->once())
            ->method('text')
            ->willReturn('Home Office Correspondence Service');

        $this->client
            ->expects($this->once())
            ->method('count')
            ->willReturn(1);

        $crawler = $this->client->request('GET', '/cts/admin/manageDocumentTemplates');
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("Home Office Correspondence Service", $crawler->filter('div#intro h1')->text());
        $this->assertEquals(1, $crawler->filter('html:contains("Manage templates")')->count());
    }

    public function testAddGet()
    {
        $this->client = $this->mockSuccessCrawler();

        $this->client
            ->expects($this->once())
            ->method('text')
            ->willReturn('Home Office Correspondence Service');

        $this->client
            ->expects($this->once())
            ->method('count')
            ->willReturn(1);

        $crawler = $this->client->request('GET', '/cts/admin/manageDocumentTemplates/add');
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("Home Office Correspondence Service", $crawler->filter('div#intro h1')->text());
        $this->assertEquals(1, $crawler->filter('html:contains("Add template")')->count());
    }

    public function testEdit()
    {
        $this->client = $this->mockSuccessCrawler();

        $this->client
            ->expects($this->once())
            ->method('text')
            ->willReturn('Home Office Correspondence Service');

        $this->client
            ->expects($this->once())
            ->method('count')
            ->willReturn(1);

        $crawler = $this->client->request('GET', '/cts/admin/manageDocumentTemplates/edit/5678');
        $response = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("Home Office Correspondence Service", $crawler->filter('div#intro h1')->text());
        $this->assertEquals(1, $crawler->filter('html:contains("Edit template")')->count());
    }

    public function testEditPost()
    {
        $ctsCaseDocumentTemplate = $this->createCtsCaseDocumentTemplate();
        $this->mockCtsCaseDocumentTemplateRepo('getDocumentTemplates', array());
        $this->mockCtsCaseDocumentTemplateRepo('getDocumentTemplate', $ctsCaseDocumentTemplate);
        $this->mockCtsCaseDocumentTemplateRepo('update', true);

        $crawler = $this->client->request('GET', '/cts/admin/manageDocumentTemplates/edit/5678');

        $buttonCrawlerNode = $crawler->selectButton('Save template');
        $form = $buttonCrawlerNode->form();
        $crawler = $this->client->submit($form);

        $this->assertEquals("Home Office Correspondence Service", $crawler->filter('div#intro h1')->text());
        $this->assertEquals(1, $crawler->filter('html:contains("Manage templates")')->count());
    }

    public function testDeleteSuccess()
    {
        $ctsCaseDocumentTemplate = $this->createCtsCaseDocumentTemplate();
        $this->client = $this->mockSuccessCrawler();

        $this->client
            ->expects($this->once())
            ->method('text')
            ->willReturn('Home Office Correspondence Service');

        $this->client
            ->expects($this->any())
            ->method('count')
            ->will($this->onConsecutiveCalls(1, 0));

        $crawler = $this->client->request(
            'DELETE',
            '/cts/admin/manageDocumentTemplates/delete',
            array('form' => array( 'id' => $ctsCaseDocumentTemplate->getId() ))
        );

        $response = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("Home Office Correspondence Service", $crawler->filter('div#intro h1')->text());
        $this->assertEquals(1, $crawler->filter('html:contains("Manage templates")')->count());
        //@codingStandardsIgnoreStart
        $this->assertEquals(0, $crawler->filter('html:contains("There was a problem deleting the document. Please try again later.")')->count());
        //@codingStandardsIgnoreEnd
    }

    public function testDeleteFailed()
    {
        $ctsCaseDocumentTemplate = $this->createCtsCaseDocumentTemplate();
        $this->mockCtsCaseDocumentTemplateRepo('getDocumentTemplates', array());
        $this->mockCtsCaseDocumentTemplateRepo('deleteDocumentTemplate', false);

        $crawler = $this->client->request(
            'DELETE',
            '/cts/admin/manageDocumentTemplates/delete',
            array('form' => array( 'id' => $ctsCaseDocumentTemplate->getId() ))
        );

        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("Home Office Correspondence Service", $crawler->filter('div#intro h1')->text());
        $this->assertEquals(1, $crawler->filter('html:contains("Manage templates")')->count());
        //@codingStandardsIgnoreStart
        $this->assertEquals(1, $crawler->filter('html:contains("There was a problem deleting the document. Please try again later.")')->count());
        //@codingStandardsIgnoreEND
    }
}

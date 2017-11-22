<?php

namespace HomeOffice\ProcessManagerAuthenticatorBundle\Tests\Controller;

use HomeOffice\CtsBundle\Tests\Controller\CtsWebTestCase as WebTestCase;
use HomeOffice\AlfrescoApiBundle\Entity\CtsCaseDocument;
use HomeOffice\AlfrescoApiBundle\Entity\Cases\CtsDcuMinisterialCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use org\bovigo\vfs\vfsStream;

class DocumentControllerTest extends WebTestCase
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

    private function createCtsCaseDocument()
    {
        $ctsCaseDocument = new CtsCaseDocument('workspace', 'store');
        $ctsCaseDocument->setId('workspace://store/5678');
        $ctsCaseDocument->setDocumentType('Original');
        $ctsCaseDocument->setDocumentDescription('Test description');
        $ctsCaseDocument->setVersionNumber('1.0');
        $ctsCaseDocument->setCreatedBy('Dave');
        $ctsCaseDocument->setName('testFile.txt');
        $ctsCaseDocument->setMimeType('text/plain');
        $ctsCaseDocument->setFile(new UploadedFile($this->fileExample, '5678', null, null, null, true));
        return $ctsCaseDocument;
    }

    public function testAdd()
    {
        $ctsCaseDocument = $this->createCtsCaseDocument();

        $this->client = $this->mockSuccessCrawler();

        $this->client
            ->expects($this->once())
            ->method('text')
            ->willReturn('Home Office Correspondence Service');

        $crawler = $this->client->request(
            'POST',
            '/cts/document/add',
            array('ctsCaseDocument' => array( 'id' => 'workspace://SpacesStore/1234' )),
            array('file' => $ctsCaseDocument->getFile())
        );
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("Home Office Correspondence Service", $crawler->filter('div#intro h1')->text());
    }

    public function testGetDocumentListAction()
    {

        $this->client = $this->mockSuccessCrawler();

        $this->client
            ->expects($this->once())
            ->method('text')
            ->willReturn('Home Office Correspondence Service');

        $crawler = $this->client->request('GET', '/cts/cases/documents/1234');
        $response = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("Home Office Correspondence Service", $crawler->filter('div#intro h1')->text());
    }

    public function testGetDocumentListWithVersionsAction()
    {

        $this->client = $this->mockSuccessCrawler();

        $this->client
            ->expects($this->once())
            ->method('text')
            ->willReturn('Home Office Correspondence Service');

        $crawler = $this->client->request('GET', '/cts/cases/documents/1234?documentRefForVersion=456');
        $response = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("Home Office Correspondence Service", $crawler->filter('div#intro h1')->text());
    }

    public function testUploadDocumentVersionAction()
    {

        $this->client = $this->mockSuccessCrawler();

        $this->client
            ->expects($this->once())
            ->method('text')
            ->willReturn('Home Office Correspondence Service');

        $ctsCaseDocument = $this->createCtsCaseDocument();

        $crawler = $this->client->request(
            'POST',
            '/cts/cases/document/upload?documentNodeRef=456',
            array('upload_456' => array(
                'id' => 'workspace://SpacesStore/1234',
                'caseId' => '5678',
                'mimeType' => 'text/plain'
            )),
            array('upload_456' => array( 'file' => $ctsCaseDocument->getFile()) )
        );
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("Home Office Correspondence Service", $crawler->filter('div#intro h1')->text());
    }

    public function testDeleteDocument()
    {

        $this->client = $this->mockSuccessCrawler();

        $this->client
            ->expects($this->once())
            ->method('text')
            ->willReturn('Home Office Correspondence Service');

        $crawler = $this->client->request(
            'DELETE',
            '/cts/document/delete?documentNodeRef=456',
            array('del_456' => array( 'id' => 'workspace://SpacesStore/456', 'caseId' => '1234' ))
        );
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("Home Office Correspondence Service", $crawler->filter('div#intro h1')->text());
    }

    public function testDeleteDocumentNoUpdatePermissions()
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
     
        $crawler = $this->client->request(
            'DELETE',
            '/cts/document/delete?documentNodeRef=456',
            array('del_456' => array( 'id' => 'workspace://SpacesStore/456', 'caseId' => '1234' ))
        );
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals("Home Office Correspondence Service", $crawler->filter('div#intro h1')->text());
        //@codingStandardsIgnoreStart
        $this->assertEquals(1, $crawler->filter('html:contains("You do not have permission to delete this document")')->count());
        //@codingStandardsIgnoreEnd
    }
}

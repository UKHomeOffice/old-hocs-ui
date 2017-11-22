<?php

namespace HomeOffice\AlfrescoApiBundle\Tests\Entity;

use HomeOffice\AlfrescoApiBundle\Entity\BulkDocument;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use org\bovigo\vfs\vfsStream;

class BulkDocumentTest extends \PHPUnit_Framework_TestCase
{
 
    /**
     * @var BulkDocument
     */
    private $instance;
 
    /**
     * @var  vfsStreamDirectory
     */
    private $root;
 
    private $fileExample;

    protected function setUp()
    {
        $this->instance = new BulkDocument('workspace', 'store');
        $this->createVirtualFile();
    }

    private function createVirtualFile()
    {
        $this->root = vfsStream::setup('root');
        vfsStream::newFile('testFile.txt')->at($this->root)->setContent("test file contents");
        $this->fileExample = vfsStream::url('root/testFile.txt');
    }
 
    public function testProperties()
    {
        // common properties
        $this->assertObjectHasAttribute('id', $this->instance);
        $this->assertObjectHasAttribute('workspace', $this->instance);
        $this->assertObjectHasAttribute('store', $this->instance);
        $this->assertObjectHasAttribute('autoCreateFailureMessage', $this->instance);
        $this->assertObjectHasAttribute('autoCreateFailureDateTime', $this->instance);
        $this->assertObjectHasAttribute('name', $this->instance);
        $this->assertObjectHasAttribute('file', $this->instance);
        $this->assertObjectHasAttribute('deleteForm', $this->instance);
    }

    public function testGetSetId()
    {
        $this->instance->setId(1);
        $this->assertEquals(1, $this->instance->getId());
    }
 
    public function testGetAutoCreateFailureMessage()
    {
        $this->instance->setAutoCreateFailureMessage('failed');
        $this->assertEquals('failed', $this->instance->getAutoCreateFailureMessage());
    }

    public function testGetAutoCreateFailureDateTime()
    {
        $this->instance->setAutoCreateFailureDatetime('01/01/1990');
        $this->assertEquals('01/01/1990', $this->instance->getAutoCreateFailureDatetime());
    }

    public function testGetSetName()
    {
        $this->instance->setName("File name");
        $this->assertEquals("File name", $this->instance->getName());
    }

    public function testGetSetDeleteForm()
    {
        $this->instance->setDeleteForm("DeleteForm");
        $this->assertEquals("DeleteForm", $this->instance->getDeleteForm());
    }

    public function testGetSetFile()
    {
        $file = new UploadedFile($this->fileExample, 'testFile.txt', null, null, null, true);
        $this->instance->setFile($file);
        $this->assertEquals($this->fileExample, $this->instance->getFile());
    }
 
    public function testGetWebPath()
    {
        $this->instance->setName('Some Document.txt');
        $this->assertEquals('/tmp/BULK_CREATE_DOCUMENT_Some Document.txt', $this->instance->getWebPath('1234'));
    }
}

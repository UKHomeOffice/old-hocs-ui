<?php

namespace HomeOffice\AlfrescoApiBundle\Tests\Entity;

use HomeOffice\AlfrescoApiBundle\Entity\CtsCaseDocument;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use org\bovigo\vfs\vfsStream;

class CtsCaseDocumentTest extends \PHPUnit_Framework_TestCase
{
 
    /**
     * @var CtsCase
     */
    private $instance;
 
    /**
     * @var  vfsStreamDirectory
     */
    private $root;
 
    private $fileExample;

    protected function setUp()
    {
        $this->instance = new CtsCaseDocument('workspace', 'store');
        $this->createVirtualFile();
    }

    private function createVirtualFile()
    {
        $this->root = vfsStream::setup('root');
        vfsStream::newFile('testFile.txt')->at($this->root)->setContent("test file contents");
        $this->fileExample = vfsStream::url('root/testFile.txt');
    }

    public function testGetSetId()
    {
        $this->instance->setId(1);
        $this->assertEquals(1, $this->instance->getId());
    }
 
    public function testGetSetDocumentType()
    {
        $this->instance->setDocumentType('Type');
        $this->assertEquals('Type', $this->instance->getDocumentType());
    }
 
    public function testGetSetDocumentDescription()
    {
        $this->instance->setDocumentDescription('Description');
        $this->assertEquals('Description', $this->instance->getDocumentDescription());
    }

    public function testGetSetCreatedDate()
    {
        $this->instance->setCreatedDate("16-07-2014");
        $this->assertEquals("16-07-2014", $this->instance->getCreatedDate());
    }

    public function testGetSetName()
    {
        $this->instance->setName("File name");
        $this->assertEquals("File name", $this->instance->getName());
    }

    public function testGetSetFile()
    {
        $file = new UploadedFile($this->fileExample, 'testFile.txt', null, null, null, true);
        $this->instance->setFile($file);
        $this->assertEquals($this->fileExample, $this->instance->getFile());
    }
 
    public function testGetSetVersionNumber()
    {
        $this->instance->setVersionNumber('1.0');
        $this->assertEquals('1.0', $this->instance->getVersionNumber());
    }
 
    public function testGetSetCreatedBy()
    {
        $this->instance->setCreatedBy('Dave Thompson');
        $this->assertEquals('Dave Thompson', $this->instance->getCreatedBy());
    }
 
    public function testGetSetMimeType()
    {
        $this->instance->setMimeType('text/plain');
        $this->assertEquals('text/plain', $this->instance->getMimeType());
    }
 
    public function testGetWebPath()
    {
        $this->instance->setName('Some Document.txt');
        $this->assertEquals('/tmp/1234_Some Document.txt', $this->instance->getWebPath('1234'));
    }
 
    public function testGetSetFileVersionUrl()
    {
        $this->instance->setFileVersionUrl('http://www.getmyfile.com/content');
        $this->assertEquals('http://www.getmyfile.com/content', $this->instance->getFileVersionUrl());
    }
}

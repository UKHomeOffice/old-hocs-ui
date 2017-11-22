<?php

namespace HomeOffice\AlfrescoApiBundle\Tests\Entity;

use HomeOffice\AlfrescoApiBundle\Entity\CtsCaseDocumentTemplate;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use org\bovigo\vfs\vfsStream;

class CtsCaseDocumentTemplateTest extends \PHPUnit_Framework_TestCase
{
 
    /**
     * @var CtsCaseDocumentTemplate
     */
    private $instance;
 
    /**
     * @var  vfsStreamDirectory
     */
    private $root;
 
    private $fileExample;

    protected function setUp()
    {
        $this->instance = new CtsCaseDocumentTemplate('workspace', 'store');
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
        $this->assertObjectHasAttribute('appliesToCorrespondenceType', $this->instance);
        $this->assertObjectHasAttribute('templateName', $this->instance);
        $this->assertObjectHasAttribute('createdDate', $this->instance);
        $this->assertObjectHasAttribute('validFromDate', $this->instance);
        $this->assertObjectHasAttribute('validToDate', $this->instance);
        $this->assertObjectHasAttribute('name', $this->instance);
        $this->assertObjectHasAttribute('file', $this->instance);
        $this->assertObjectHasAttribute('mimeType', $this->instance);
        $this->assertObjectHasAttribute('deleteForm', $this->instance);
    }

    public function testGetSetId()
    {
        $this->instance->setId(1);
        $this->assertEquals(1, $this->instance->getId());
    }
 
    public function testGetSetAppliesToCorrespondenceType()
    {
        $this->instance->setAppliesToCorrespondenceType('Type');
        $this->assertEquals('Type', $this->instance->getAppliesToCorrespondenceType());
    }
 
    public function testGetSetTemplateName()
    {
        $this->instance->setTemplateName('Type');
        $this->assertEquals('Type', $this->instance->getTemplateName());
    }
 
    public function testGetSetValidateFile()
    {
        $this->instance->setValidateFile(true);
        $this->assertEquals(true, $this->instance->getValidateFile());
    }

    public function testGetSetCreatedDate()
    {
        $date = new \DateTime('01/01/1990');
        $this->instance->setCreatedDate('01/01/1990');
        $this->assertEquals($date, $this->instance->getCreatedDate());
     
        $date = new \DateTime('01/01/1991');
        $this->instance->setCreatedDate($date);
        $this->assertEquals($date, $this->instance->getCreatedDate());
    }

    public function testGetSetValidFromDate()
    {
        $date = new \DateTime('01/01/1990');
        $this->instance->setValidFromDate('01/01/1990');
        $this->assertEquals($date, $this->instance->getValidFromDate());
     
        $date = new \DateTime('01/01/1991');
        $this->instance->setValidFromDate($date);
        $this->assertEquals($date, $this->instance->getValidFromDate());
    }

    public function testGetSetValidToDate()
    {
        $date = new \DateTime('01/01/1990');
        $this->instance->setValidToDate('01/01/1990');
        $this->assertEquals($date, $this->instance->getValidToDate());
     
        $date = new \DateTime('01/01/1991');
        $this->instance->setValidToDate($date);
        $this->assertEquals($date, $this->instance->getValidToDate());
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
 
    public function testGetSetMimeType()
    {
        $this->instance->setMimeType('text/plain');
        $this->assertEquals('text/plain', $this->instance->getMimeType());
    }
 
    public function testGetWebPath()
    {
        $this->instance->setName('Some Document.txt');
        $this->assertEquals('/tmp/DOCUMENT_TEMPLATE_Some Document.txt', $this->instance->getWebPath('1234'));
    }
}

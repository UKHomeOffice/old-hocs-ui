<?php

namespace HomeOffice\AlfrescoApiBundle\Tests\Entity;

use HomeOffice\AlfrescoApiBundle\Entity\CtsCaseStandardLine;
use org\bovigo\vfs\vfsStreamDirectory;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use org\bovigo\vfs\vfsStream;

class CtsCaseStandardLineTest extends \PHPUnit_Framework_TestCase
{
 
    /**
     * @var CtsCaseStandardLine
     */
    private $instance;
 
    /**
     * @var vfsStreamDirectory
     */
    private $root;
 
    private $fileExample;

    protected function setUp()
    {
        $this->instance = new CtsCaseStandardLine('workspace', 'store');
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
        $this->assertObjectHasAttribute('associatedTopic', $this->instance);
        $this->assertObjectHasAttribute('associatedUnit', $this->instance);
        $this->assertObjectHasAttribute('createdDate', $this->instance);
        $this->assertObjectHasAttribute('reviewDate', $this->instance);
        $this->assertObjectHasAttribute('file', $this->instance);
        $this->assertObjectHasAttribute('mimeType', $this->instance);
    }
 
    public function testSetGetCreatedDate()
    {
        $date = new \DateTime('01/01/1991');
        $this->instance->setCreatedDate($date);
        $this->assertEquals($date, $this->instance->getCreatedDate());
    }
 
    public function testSetGetReviewDate()
    {
        $date = new \DateTime('01/01/1991');
        $this->instance->setReviewDate($date);
        $this->assertEquals($date, $this->instance->getReviewDate());
    }
 
    public function testSetGetAssociatedTopic()
    {
        $this->instance->setAssociatedTopic("Test topic");
        $this->assertEquals("Test topic", $this->instance->getAssociatedTopic());
    }
 
    public function testSetGetAssociatedUnit()
    {
        $this->instance->setAssociatedUnit("Test unit");
        $this->assertEquals("Test unit", $this->instance->getAssociatedUnit());
    }
 
    public function testSetGetFile()
    {
        $file = new UploadedFile($this->fileExample, 'testFile.txt', null, null, null, true);
        $this->instance->setFile($file);
        $this->assertEquals($this->fileExample, $this->instance->getFile());
    }
 
    public function testSetGetMimeType()
    {
        $this->instance->setMimeType('text/plain');
        $this->assertEquals('text/plain', $this->instance->getMimeType());
    }
 
    public function testIsReviewRequired()
    {
        $this->instance->setReviewDate(new \DateTime());
        $this->assertEquals(true, $this->instance->isReviewRequired());
        $this->instance->setReviewDate(new \DateTime('tomorrow'));
        $this->assertEquals(false, $this->instance->isReviewRequired());
    }
}

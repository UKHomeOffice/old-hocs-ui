<?php

namespace HomeOffice\AlfrescoApiBundle\Tests\Entity;

use \HomeOffice\AlfrescoApiBundle\Entity\CtsTsoFeed;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use org\bovigo\vfs\vfsStream;

class CtsTsoFeedTest extends \PHPUnit_Framework_TestCase
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
        $this->instance = new CtsTsoFeed();
        $this->createVirtualFile();
    }

    private function createVirtualFile()
    {
        $this->root = vfsStream::setup('root');
        vfsStream::newFile('testFile.txt')->at($this->root)->setContent("test file contents");
        $this->fileExample = vfsStream::url('root/testFile.txt');
    }
 
    public function testGetSetName()
    {
        $this->instance->setName('file name');
        $this->assertEquals('file name', $this->instance->getName());
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
        $this->assertEquals('/tmp/TSOFEED_Some Document.txt', $this->instance->getWebPath('1234'));
    }
}

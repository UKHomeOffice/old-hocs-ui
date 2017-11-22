<?php namespace HomeOffice\CtsBundle\Tests\Form\Type;

use Symfony\Component\Form\Test\TypeTestCase;
use HomeOffice\AlfrescoApiBundle\Entity\CtsTsoFeed;
use HomeOffice\CtsBundle\Form\Type\CtsCaseUploadType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use org\bovigo\vfs\vfsStream;

class CtsCaseUploadTypeTest extends TypeTestCase
{
 
    /**
     * @var  vfsStreamDirectory
     */
    private $root;
 
    private $fileExample;

    protected function setUp()
    {
        parent::setUp();
        $this->createVirtualFile();
    }

    private function createVirtualFile()
    {
        $this->root = vfsStream::setup('root');
        vfsStream::newFile('testFile.txt')->at($this->root)->setContent("test file contents");
        $this->fileExample = vfsStream::url('root/testFile.txt');
    }
 
    public function testCtsCaseUploadSubmitData()
    {
        $file = new UploadedFile($this->fileExample, 'testFile.txt', null, null, null, true);
        $formData = array(
            'file' => $file
        );
       
        $type = new CtsCaseUploadType();
        $form = $this->factory->create($type);
     
        $ctsCaseUpload = new CtsTsoFeed();
        $ctsCaseUpload->setFile($file);
     
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($ctsCaseUpload, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}

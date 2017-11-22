<?php namespace HomeOffice\CtsBundle\Tests\Form\Type;

use Symfony\Component\Form\Test\TypeTestCase;
use HomeOffice\CtsBundle\Form\Type\CtsCaseDocumentType;
use HomeOffice\AlfrescoApiBundle\Factory\CtsCaseDocumentFactory;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use org\bovigo\vfs\vfsStream;

class CtsCaseDocumentTypeTest extends TypeTestCase
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
 
    public function testCtsCaseDocumentSubmitData()
    {
        $file = new UploadedFile($this->fileExample, 'testFile.txt', null, null, null, true);
        $formData = array(
            'documentType' => 'Original',
            'documentDescription' => 'Test description',
            'file' => $file
        );
       
        $type = new CtsCaseDocumentType('create', 'workspace', 'store');
        $form = $this->factory->create($type);
     
        $factory = new CtsCaseDocumentFactory('workspace', 'store');
        $ctsCaseDocument = $factory->build($formData);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($ctsCaseDocument, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
